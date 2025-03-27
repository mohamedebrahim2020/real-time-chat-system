<?php

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Route;
	use App\Http\Controllers\ChatController;
	use App\Http\Controllers\AuthController;

	Route::get('/user', function (Request $request) {
		return $request->user();
	})->middleware('auth:sanctum');

	Route::middleware('throttle:api')->group(function () {
		Route::post('/login', AuthController::class . '@login')->name('sanctum.login');
		Route::middleware('auth:sanctum')->prefix('chat')->name('chat.')->group(function () {
			Route::post('/send', [ChatController::class, 'store'])->name('send');
			Route::get('/messages/{user_id}', [ChatController::class, 'index'])->name('index');
			Route::patch('/read/{message_id}', [ChatController::class, 'read'])->name('read');
		});
	});
