<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('chat', [ChatController::class, 'index']);
Route::get('chat/message', [ChatController::class, 'getMessages']);
Route::post('chat/message', [ChatController::class, 'sendMessage']);
Route::post('chat/clear', [ChatController::class, 'clearMessages']);
