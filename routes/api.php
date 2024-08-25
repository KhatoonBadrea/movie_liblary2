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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('movies', MovieController::class);
Route::post('movies/{movie}/rate', [MovieController::class, 'rateMovie'])->middleware('auth:sanctum');
Route::post('movies/{movie}/favorite', [MovieController::class, 'addToFavorites'])->middleware('auth:sanctum');







// مسارات تسجيل الدخول والتسجيل وتسجيل الخروج
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
