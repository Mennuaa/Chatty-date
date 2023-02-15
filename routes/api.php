<?php

use App\Events\ChatMessageEvent;
use App\Events\PlaygroundEvent;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LikesController;
use App\Http\Controllers\API\NewPasswordController;
use App\Http\Controllers\GroupChatController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRecomendations;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
Route::controller(NewPasswordController::class)->group(function () {
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

Route::post('/like', [LikesController::class, 'addToLiked'])->middleware('auth:sanctum');

//upload image 
Route::post('/uploadImage', [UploadImageController::class, 'upload']);


//group
Route::put('/group_chat/send_message/{id}', [GroupChatController::class, 'sendMessages'])->middleware('auth:sanctum');
Route::post('/group_chat', [GroupChatController::class, 'makeChat'])->middleware('auth:sanctum');
Route::put('/group_chat/{id}', [GroupChatController::class, 'enter'])->middleware('auth:sanctum');
Route::post('/group-chat-message', [GroupChatController::class, 'chatMessage'])->middleware('auth:sanctum');
Route::get('/group_chats', [GroupChatController::class, 'chats'])->middleware('auth:sanctum');

//chats
Route::post('/messages', [MessagesController::class, 'makeChat'])->middleware('auth:sanctum');
Route::post('/chat-message', [MessagesController::class, 'chatMessage'])->middleware('auth:sanctum');
Route::put('/messages/{id}', [MessagesController::class, 'sendMessages'])->middleware('auth:sanctum');
Route::get('/chats', [MessagesController::class, 'chats'])->middleware('auth:sanctum');

Route::get('/playground', function(){

    event(new ChatMessageEvent());
    return null;
});
Route::get('/user/{id}', [UserController::class, 'getUser'])->middleware('auth:sanctum');
Route::put('/user/{id}', [UserController::class, 'change'])->middleware('auth:sanctum');
Route::put('/cancel_user', [UserController::class, 'cancelUser'])->middleware('auth:sanctum');

//recomendations
Route::get('/recomendations/{id}', [UserRecomendations::class, 'recomendations'])->middleware('auth:sanctum');


//notifications
Route::get('/notifications/all', [NotificationController::class, 'getNotifications'])->middleware('auth:sanctum');