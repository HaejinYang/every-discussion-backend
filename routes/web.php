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
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::get('login', [AdminController::class, 'login']);
});



//
//Route::get('/verify', function (Request $request) {
//    $user = $request->input('user');
//    $token = $request->input('token');
//
//    return view('mail.verify', ['user' => $user, 'token' => $token]);
//});
