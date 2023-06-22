<?php

namespace App\Helpers;

use App\Models\User;

class GetCurrentUserHelper
{
    public static function getCurrentUser($token, $model)
    {
        try {
            // Decoded Token
            $decoded_token = TokenHelper::decode($token);

            $user_uuid = $decoded_token->user_uuid;
            return $model::where('uuid', $user_uuid)->first();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg' => 'Error, Please Contact Admin!',
            ], 400);
        }
    }
}
