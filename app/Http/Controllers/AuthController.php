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

class AuthController extends Controller
{
    use ApiResponse;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'user' => $user,
            'token'      => $token,
            'token_type' => 'bearer',
        ];
        return $this->created($response,  'Registration was successful');
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

        // $user = User::query()->where('email', $request->email)->first();
        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     return $this->unauthorized('Invalid credentials');
        // }
        // // $token = $user->createToken('auth_token')->plainTextToken;
        // $response = [
        //     'user' => $user,
        //     'token'      => $token,
        //     'token_type' => 'bearer',
        // ];

        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        return $this->respondWithToken($token)

            // $response = [
            //     'access_token' => $token,
            //     'token_type' => 'bearer',
            //     'expires_in' => config('jwt.ttl') * 60
            // ];
        ;

        // return $this->success($response,  'Login successfully');
    }

    public function me()
    {
        return $this->success(JWTAuth::user(),  'User profile retrieved successfully');
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        $cookie = cookie()->forget('token');
        return $this->success(message: 'Logged out successfully')->withCookie($cookie);
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
