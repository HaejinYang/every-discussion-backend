<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Opinion\OpinionController;
use App\Http\Controllers\Topic\TopicController;
use App\Http\Controllers\Topic\TopicOpinionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserTopicController;
use App\Http\Controllers\User\UserTopicOpinionController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


// need auth
Route::middleware(['auth.token', 'user.get'])->group(function () {
    // 유저 로그인, 로그아웃,
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/delete', [AuthController::class, 'delete']);
    Route::post('/topics', [TopicController::class, 'store']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
    Route::post('/opinions', [OpinionController::class, 'store']);
    Route::get('/users/{user}/topics/{topic}/opinions', UserTopicOpinionController::class);
});

// user-topic controller
Route::get('/users/{user}/topics', UserTopicController::class);

// auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/check-duplicated', [AuthController::class, 'duplicated']);

// api
Route::apiResource('topics', TopicController::class)->only('index', 'show');
Route::get('topics/{topic}/opinions', TopicOpinionController::class);

// opinion 가져오기
Route::get('opinions/{opinion}', [OpinionController::class, 'show']);

// 개발중 테스트용
// Route::post('test/test', TestController::class);

// fallback은 라우터 가장 하단에 있어야 한다.
Route::fallback(function () {
    return response()->json("등록되지 않은 URL입니다.");
});
