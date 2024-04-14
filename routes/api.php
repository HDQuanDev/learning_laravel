<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserGetController;
use App\Http\Controllers\User\ManagerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\User\NoteController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/get/user', [UserGetController::class, 'get_list']);
    Route::post('/user/manager/update_user', [ManagerController::class, 'edit_user']);
    Route::post('/user/get/user_by_id', [UserGetController::class, 'get_info_by_id']);
    Route::post('/user/manager/delete_user', [ManagerController::class, 'delete_user']);
    Route::post('/user/note/create', [NoteController::class, 'create'])->name('create_note');
    Route::get('/user/note/get', [NoteController::class, 'get_note'])->name('get_note');
    Route::post('/user/note/get_by_id', [NoteController::class, 'get_note_by_id'])->name('get_note_by_id');
});
Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/chat/send', [ChatController::class, 'send_chat']);
Route::get('/chat/get', [ChatController::class, 'get_chat']);
