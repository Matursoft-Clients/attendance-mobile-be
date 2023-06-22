<?php

namespace App\Http\Controllers;

use App\Helpers\TokenHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;

            if (!$employee = Employee::where('email', $email)->first()) {
                return response()->json([
                    'code' => 422,
                    'msg' => "Email or Password is Wrong",
                ], 422);
            }

            if (Hash::check($password, $employee->password) == false) {
                return response()->json([
                    'code' => 422,
                    'msg' => "Email or Password is Wrong",
                ], 422);
            } else {
                // Generate Token Login
                $employee_uuid = $employee->uuid;
                $token = TokenHelper::encode($employee_uuid, $email);

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
}
