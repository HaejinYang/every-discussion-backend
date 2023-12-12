<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\EmailAuthController;
use App\Mail\AuthMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    Mail::to('extension.master.91@gmail.com')->send(new AuthMail());

    return view('welcome');
});

Route::get('/verify', EmailAuthController::class);

Route::prefix('/admin')->group(function () {
    Route::match(['get', 'post'], 'login', [AdminController::class, 'login']);

    Route::group(['middleware' => ['admin']], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::match(['get', 'post'], 'logout', [AdminController::class, 'logout']);

        // 유저
        Route::get('users', [AdminController::class, 'users']);
        Route::put('users', [AdminController::class, 'userUpdate']);
        Route::delete('users', [AdminController::class, 'userDelete']);

        // 토론 주제
        Route::get('topics', [AdminController::class, 'topics']);
        Route::put('topics', [AdminController::class, 'topicUpdate']);
        Route::delete('topics', [AdminController::class, 'topicDelete']);

        // 의견
        Route::get('opinions', [AdminController::class, 'opinions']);
        Route::put('opinions', [AdminController::class, 'opinionUpdate']);
        Route::delete('opinions', [AdminController::class, 'opinionDelete']);
    });
});



//
//Route::get('/verify', function (Request $request) {
//    $user = $request->input('user');
//    $token = $request->input('token');
//
//    return view('mail.verify', ['user' => $user, 'token' => $token]);
//});
