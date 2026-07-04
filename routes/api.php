<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

// Auth routes
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Welcome to the CMS API',
        'data' => [
            'version' => '1.0.0',
        ],
    ], Response::HTTP_OK);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('jwt.verify')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::middleware('jwt.verify')->get('/validate-token', function () {
//     return response()->json([
//         'valid'   => true,
//         'user_id' => Auth::guard('api')->id(),
//         'email'   => Auth::guard('api')->user()->email,
//         'name'    => Auth::guard('api')->user()->name,
//     ]);
// });

// Route::middleware('jwt.verify')->group(function () {
//     Route::get('/users', function (Request $request) {
//         return response()->json([
//             'message'  => 'All user list',
//             'data' => \App\Models\User::all(),
//             'success' => true,
//         ], Response::HTTP_OK);
//     });
// });

// ? create api for admin panel
Route::apiResources([
    'users' => UserController::class,
]);
