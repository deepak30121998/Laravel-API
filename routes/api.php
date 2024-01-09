<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermisionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('chart', [DashboardController::class, 'chart']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::get('user', [UserController::class, 'user']);
    Route::put('user/info', [UserController::class, 'updateInfo']);
    Route::put('user/password', [UserController::class, 'updatePassword']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('products', ProductController::class);
    Route::get('export', [OrderController::class, 'export']);

    Route::post('upload', [ImageController::class, 'upload']);

    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
    Route::apiResource('permissions', PermisionController::class)->only(['index']);

});



