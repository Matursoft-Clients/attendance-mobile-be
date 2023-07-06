<?php

namespace App\Http\Controllers;

use App\Helpers\EmailVerificationCodeHelper;
use App\Helpers\GetCurrentUserHelper;
use App\Helpers\ModelFileUploadHelper;
use App\Helpers\SendEmailHelper;
use App\Helpers\TokenHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Employee\UpdateUserRequest;
use App\Http\Requests\ResetPassword\CheckTokenRequest;
use App\Http\Requests\ResetPassword\ForgotPasswordRequest;
use App\Http\Requests\ResetPassword\ResetPasswordRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $email    = $request->email;
            $password = $request->password;

            if (!$employee = Employee::where('email', $email)->first()) {
                return response()->json([
                    'code' => 422,
                    'msg'  => "Email or Password is Wrong",
                ], 422);
            }

            if (Hash::check($password, $employee->password) == false) {
                return response()->json([
                    'code' => 422,
                    'msg'  => "Email or Password is Wrong",
                ], 422);
            } else {
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
            dd($th);
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

            return response()->json([
                'code' => 200,
                'msg'  => "Here is the User",
                'data' =>
                [
                    'uuid'  => $user->uuid,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo ? config('app.base_url') . "/storage/EMPLOYEES/photo/" . $user->photo : null,
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

            $user->update([
                'name'     => $request->name,
                'password' => Hash::make($request->password),
                'photo'    => ModelFileUploadHelper::modelFileUpdate($user, 'photo', $request->file('photo')),
            ]);

            return response()->json([
                'code' => 200,
                'msg'  => "User Has Been Successfully Updated",
                'data' => [
                    'uuid'  => $user->uuid,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo ? config('app.base_url') . "/storage/EMPLOYEES/photo/" . $user->photo : null,
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
