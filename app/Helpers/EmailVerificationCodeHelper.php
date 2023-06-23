<?php

namespace App\Helpers;

use App\Models\Employee;

class EmailVerificationCodeHelper
{
    public static function emailVerificationCode($email)
    {
        $code = str_pad((string)rand(00000, 99999), 5, '0', STR_PAD_LEFT);

        $employee = Employee::where('email', $email)->first();

        while ($employee->token !== null && json_decode($employee->token)->token == $code) {
            $code = str_pad((string)rand(00000, 99999), 5, '0', STR_PAD_LEFT);
        }
        return $code;
    }
}
