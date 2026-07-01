<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // 1. Check for Bearer token in Headers first
            $token = $request->bearerToken();

            // 2. If no Header token, look for the 'token' cookie
            if (!$token) {
                $token = $request->cookie('token');
            }

            // 3. If neither exists, reject immediately
            if (!$token) {
                return response()->json(['error' => 'Authorization Token or Cookie not found'], 401);
            }

            // 4. Manually set the extracted token into the JWTAuth facade
            JWTAuth::setToken($token);

            // 5. Authenticate the user
            $user = JWTAuth::authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'Token is Invalid'], 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'Token is Expired'], 401);
            } else {
                return response()->json(['error' => 'Authorization issue: ' . $e->getMessage()], 401);
            }
        }

        return $next($request);
    }
}
