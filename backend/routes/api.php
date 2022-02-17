<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//로그인, 회원가입
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    //유저확인, 로그아웃
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    // 운동기록 업로드
    Route::post('/store', [PostController::class, 'store']);

    // 팔로우
    // Route::post('/follow/{user}', [FollowsController::class, 'store']);
    Route::post('/follow/{user}', [FollowsController::class, 'store']);
});
