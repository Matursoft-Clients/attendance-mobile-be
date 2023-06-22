<?php

namespace App\Http\Middleware;

use App\Helpers\TokenHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        // Token
        $token = $request->bearerToken();

        try {
            $decoded_token = TokenHelper::decode($token);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 406,
                'msg' => "Your Token is Broken",
            ], 406);
        }

        if ($decoded_token->expired_at < time()) {
            return response()->json([
                'code' => 498,
                'msg' => "Your Token is Expired",
            ], 498);
        } else {
            return $next($request);
        }
    }
}
