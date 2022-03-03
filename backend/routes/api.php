<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MMRController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RecordController;
use App\Models\Record;
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



//현재로그인 확인
Route::middleware('auth:sanctum')->group(function () {
    //유저확인, 로그아웃
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    // 운동기록 업로드
    Route::prefix('post')->group(function () {
        Route::post('/store', [PostController::class, 'store']);
        Route::post('/index', [PostController::class, 'index']);
        Route::get('/show/{id}', [PostController::class, 'show']);
        Route::put('/update/{id}', [PostController::class, "update"]);
        Route::delete('/{id}', [PostController::class, "destroy"]);
    });

    // 팔로우
    Route::post('/follow/{user}', [FollowsController::class, 'store']);

    //게시글 좋아요
    Route::post('/like/{post}', [LikeController::class, 'store']);

    //댓글
    Route::prefix('comment')->group(function () {
        Route::post('/store/{id}', [CommentController::class, 'store']);
        Route::get('/index/{id}', [CommentController::class, 'index']);
        Route::delete('/destroy/{id}', [CommentController::class, 'destroy']);
    });

    //mmr
    Route::post('/match', [MMRController::class, 'match']);

    //전적 기록저장, 불러오기
    Route::prefix('/record')->group(function () {
        Route::get('/store', [RecordController::class, 'store'])->name('record.store');
        Route::get('/index/{id}', [RecordController::class, 'index'])->name('record.index');
    });
});
