<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Helpers\ModelFileUploadHelper;
use App\Helpers\TokenHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Employee\UpdateUserRequest;
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
                $token         = TokenHelper::encode($employee_uuid, $email);

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
            dd($th);
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
            dd($th);
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }
}
