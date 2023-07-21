<?php

namespace App\Http\Controllers;

use App\Helpers\EmailVerificationCodeHelper;
use App\Helpers\GetCurrentUserHelper;
use App\Helpers\SendEmailHelper;
use App\Helpers\TokenHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Employee\UpdateUserRequest;
use App\Http\Requests\ResetPassword\CheckTokenRequest;
use App\Http\Requests\ResetPassword\ForgotPasswordRequest;
use App\Http\Requests\ResetPassword\ResetPasswordRequest;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $device_id   = $request->device_id;
            $device_name = $request->device_name;
            $email       = $request->email;
            $password    = $request->password;

            if (!$employee = Employee::where('email', $email)->orWhere('nrp', $email)->first()) {
                return response()->json([
                    'code' => 422,
                    'msg'  => "Email, NRP or Password is Wrong",
                ], 422);
            }

            if ($employee->device_id) {
                if ($employee->device_id !== $device_id || $employee->device_name !== $device_name) {
                    return response()->json([
                        'code' => 422,
                        'msg'  => "Please Login With the Associated Phone",
                    ], 422);
                }
            }

            if (Hash::check($password, $employee->password) == false) {
                return response()->json([
                    'code' => 422,
                    'msg'  => "Email, NRP or Password is Wrong",
                ], 422);
            } else {
                if (!$employee->device_id) {
                    // Update device id and device name
                    $employee->update([
                        'device_id'   => $device_id,
                        'device_name' => $device_name,
                    ]);
                }

                // Generate Token Login
                $employee_uuid = $employee->uuid;
                $employee_name = $employee->name;
                $token         = TokenHelper::encode($employee_uuid, $email, $employee_name);

                return response()->json([
                    'code' => 200,
                    'msg'  => "You Have Successfully Login",
                    'data' => [
                        'token' => $token
                    ]
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => "Error, You Can't Login!",
            ], 400);
        }
    }

    public function getCurrentUser(Request $request)
    {
        try {
            // Get Current User
            $user = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            $branch = Branch::where('uuid', $user->branch_uuid)->first();

            $jobPosition = JobPosition::where('uuid', $user->job_position_uuid)->first()->name;

            return response()->json([
                'code' => 200,
                'msg'  => "Here is the User",
                'data' =>
                [
                    'uuid'            => $user->uuid,
                    'nik'             => $user->nik,
                    'nrp'             => $user->nrp,
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'whatsapp_number' => $user->whatsapp_number,
                    'photo'           => $user->photo ? config('app.web_url') . "employee/" . $user->photo : null,
                    'branch'          => $branch,
                    'job_position'    => $jobPosition,
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => "Error, Can't Get Current User!",
            ], 400);
        }
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            // Get Current User
            $user = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            if ($request->file('photo')) {
                $uploadPhoto = Http::attach(
                    'photo',
                    $request->file('photo')->getContent(),
                    $request->file('photo')->getFilename() . '.' . $request->file('photo')->getClientOriginalExtension()
                )->post(config('app.web_be_url') . 'employees/' . $user->uuid);
            }

            $user->update([
                'name'            => $request->name,
                'whatsapp_number' => $request->whatsapp_number,
                'password'        => $request->password ? Hash::make($request->password) : $user->password,
                'photo'           => $request->file('photo') ? $uploadPhoto->body() : $user->photo,
            ]);

            return response()->json([
                'code' => 200,
                'msg'  => "User Has Been Successfully Updated",
                'data' => [
                    'uuid'  => $user->uuid,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo ? config('app.web_url') . "employee/" . $user->photo : null,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $email = $request->email;

            $employee = Employee::where('email', $email)->first();

            //Generate Kode Verification
            $token = EmailVerificationCodeHelper::emailVerificationCode($email);

            //Kirim Email
            $name = $employee->name;
            SendEmailHelper::sendEmail($name, $email, $token);

            $employee->update([
                'token' => [
                    'key'     => 'reset_password',
                    'token'   => $token,
                    'expired' => date("Y-m-d H:i:s", strtotime("1 hour"))
                ]
            ]);

            return response()->json([
                'code' => 200,
                'msg'  => "Forgot Password Code has been Sent to Your Email, Please check Your Email.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function checkToken(CheckTokenRequest $request)
    {
        try {
            $email = $request->email;
            $key   = $request->key;
            $token = $request->token;

            $employee = Employee::where('email', $email)->first();

            // Cek Expired
            if (json_decode($employee->token)->expired < date("Y-m-d H:i:s")) {
                return response()->json([
                    'code' => 422,
                    'msg'  => "The Code is Expired.",
                    'data' => false
                ], 422);
            }

            if (json_decode($employee->token)->key == $key && json_decode($employee->token)->token == $token) {
                return response()->json([
                    'code' => 200,
                    'msg'  => "The Code is Correct.",
                    'data' => true
                ], 200);
            }

            return response()->json([
                'code' => 422,
                'msg'  => "The Code is Wrong.",
                'data' => false
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $email        = $request->email;
            $new_password = $request->new_password;
            $token        = $request->token;

            $employee = Employee::where('email', $email)->first();

            if (json_decode($employee->token)->token == $token) {
                $employee->update([
                    'password' => Hash::make($new_password),
                    'token'    => NULL
                ]);

                return response()->json([
                    'code' => 200,
                    'msg'  => "Your Password has been Changed Successfully",
                    'data' => true
                ], 200);
            }
            return response()->json([
                'code' => 200,
                'msg'  => "Your Password has Failed to Change",
                'data' => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }
}
