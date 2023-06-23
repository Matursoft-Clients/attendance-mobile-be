<?php

namespace App\Helpers;

use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;

class SendEmailHelper
{
    public static function sendEmail($user_name, $user_email, $code)
    {
        $email_content = [
            'title' => 'Forgot Password',
            'body_name' => 'Halo ' . $user_name . ',',
            'body' => 'Kode lupa sandi akun Anda adalah: ',
            'code' => $code,
        ];

        Mail::to($user_email)->send(new EmailVerification($email_content));
    }
}
