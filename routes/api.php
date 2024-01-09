<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Opinion\OpinionController;
use App\Http\Controllers\Topic\TopicController;
use App\Http\Controllers\Topic\TopicOpinionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserTopicController;
use App\Http\Controllers\User\UserTopicOpinionController;
use Illuminate\Http\Response;
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

// 사용자 인증 필요
Route::middleware(['auth.token', 'user.get'])->group(function () {
    // 유저 로그아웃, 삭제
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/delete', [AuthController::class, 'delete']);

    // 유저 업데이트, 탈퇴
    Route::put('/users', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'destroy']);

    // 토픽 추가
    Route::post('/topics', [TopicController::class, 'store']);

    // 의견 추가, 삭제, 수정
    Route::post('/opinions', [OpinionController::class, 'store']);
    Route::delete('/opinions/{opinion}', [OpinionController::class, 'delete']);
    Route::put('/opinions/{opinion}', [OpinionController::class, 'update']);

    // 유저가 특정 토픽에 적은 모든 의견들 조회
    Route::get('/users/{user}/topics/{topic}/opinions', UserTopicOpinionController::class);
});

// 유저가 참여한 토픽들 반환
Route::get('/users/{user}/topics', UserTopicController::class);

// 유저 가입, 로그인, 중복확인, 아이디 찾기, 패스워드 찾기
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/duplicated', [AuthController::class, 'duplicated']);
Route::get('/auth/email', [AuthController::class, 'findEmail']);
Route::get('/auth/password/token', [AuthController::class, 'sendTokenForChangingPassword']);
Route::post('/auth/password/token', [AuthController::class, 'verifyTokenForChangingPassword']);
Route::post('/auth/password', [AuthController::class, 'changePassword']);
Route::post('/auth/email', [AuthController::class, 'resendAuthEmail']);

// 토픽을 보여줌
Route::apiResource('topics', TopicController::class)->only('index', 'show');

// 특정 토픽에 속한 의견들을 보여줌
Route::get('topics/{topic}/opinions', [TopicOpinionController::class, 'index']);
Route::get('topics/{id}/graph', [TopicOpinionController::class, 'graph']);

// 의견 가져오기
Route::get('opinions/{opinion}', [OpinionController::class, 'show']);

// fallback은 라우터 가장 하단에 있어야 한다.
Route::fallback(function () {
    return response()->json("등록되지 않은 URL입니다.", Response::HTTP_NOT_FOUND);
});
