<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
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
    return view('welcome');
});

Route::get('/user/login', [WebController::class, 'login_page'])->name('login_page');
Route::get('/user/register', [WebController::class, 'register'])->name('register');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/main', [WebController::class, 'dashboard'])->name('dashboard');
    Route::get('/user/manager/user', [WebController::class, 'manager_user'])->name('manager_user');
});
Route::get('/chat', function () {
    return view('chat');
})->name('chat');
