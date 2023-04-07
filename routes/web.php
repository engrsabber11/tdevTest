<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::redirect('/', '/login');

Auth::routes();

Route::group(['middleware'=>'auth'], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('user-info-by-id',[UserController::class, 'show'])->name('getUserById')->middleware('can:edit-user');
    Route::post('user-info-update',[UserController::class, 'update'])->name('users.update')->middleware('can:edit-user');
    Route::get('users',[UserController::class,'index'])->name('users.index')->middleware('can:manage-user');
});


