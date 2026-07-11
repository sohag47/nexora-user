<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Use JWTAuth here too, to stay consistent with login/me/refresh/logout
        $token = JWTAuth::fromUser($user);

        $response = [
            'user'       => $user,
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];

        return $this->created($response, 'Registration was successful');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $key = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return $this->unauthorized("Too many attempts. Try again in {$seconds} seconds.");
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            RateLimiter::hit($key, 60);
            return $this->unauthorized('Invalid credentials');
        }

        RateLimiter::clear($key);

        $response = [
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];

        return $this->success($response, 'Login successfully');
    }

    public function me()
    {
        return $this->success(auth('api')->user(), 'User profile retrieved successfully');
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
        } catch (JWTException $e) {
            return $this->unauthorized('Token could not be refreshed');
        }

        return $this->success([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ], 'Token refreshed successfully');
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return $this->unauthorized('Token not provided or already invalid');
        }

        return $this->success(message: 'Logged out successfully');
    }


    protected function respondWithToken($token)
    {
        $minutes = config('jwt.ttl'); // Read expiration minutes from config
        $cookie = cookie(
            'token',          // Cookie name
            $token,           // Cookie value
            $minutes,         // Expiration in minutes
            '/',              // Path
            '.nexora.test',   // Domain
            false,            // Secure (Set to true if using HTTPS in production)
            true,             // HttpOnly (Crucial: JavaScript cannot access this)
            false,            // Raw
            'Lax'             // SameSite attribute
        );
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Logged in successfully.'
        // ])->withCookie($cookie);

        return $this->success('Login successfully')->withCookie($cookie);
    }
}
