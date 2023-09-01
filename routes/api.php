<?php

use App\Http\Controllers\Test\TestController;
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

Route::get('/', [TestController::class, 'test']);

// fallback은 라우터 가장 하단에 있어야 한다.
Route::fallback(function () {
    return response()->json("등록되지 않은 URL입니다.");
});
