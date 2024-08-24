<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;

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

Route::post('movies/{movie}/rate', [MovieController::class, 'rateMovie'])->middleware('auth:sanctum');

// استرجاع معلومات المستخدم المسجل
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// تسجيل المسارات الأساسية للأفلام
Route::apiResource('movies', MovieController::class);

// تقييم فيلم معين




Route::post('test-post', function () {
    return response()->json(['message' => 'POST method is working']);
});


// مسارات تسجيل الدخول والتسجيل وتسجيل الخروج
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
