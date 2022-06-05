<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/line-test', function () {
    return 'ok';
});

// LINE メッセージ受信
Route::post('/line/webhook', [App\Http\Controllers\LineMessengerController::class, 'webhook'])->name('line.webhook');
// LINE メッセージ送信用
Route::post('/line/message', [App\Http\Controllers\LineMessengerController::class, 'message']);

Route::get('/line/push', [App\Http\Controllers\LineMessengerController::class, 'push_frend_all']);

Route::get('/line/test',function() {
    return view('welcome');
});

