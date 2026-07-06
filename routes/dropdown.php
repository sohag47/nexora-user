<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Welcome to the Dropdown API',
        'data' => [
            'version' => '1.0.0',
            'details' => 'all the dropdown api will be /api/dropdown/*',
        ],
    ], Response::HTTP_OK);
});

Route::get('users', [UserController::class, 'dropdown']);
Route::get('branches', [BranchController::class, 'dropdown']);
Route::get('designations', [DesignationsController::class, 'dropdown']);
Route::get('departments', [DepartmentController::class, 'dropdown']);
Route::get('clients', [ClientController::class, 'dropdown']);
