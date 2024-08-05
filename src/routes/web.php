<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', [ItemController::class, 'index'])->name('home');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
  Route::get('/mypage', [UserController::class, 'index'])->name('mypage');
  Route::get('/mypage/edit', [UserController::class, 'edit'])->name('profile.edit');
  Route::post('/mypage/edit', [UserController::class, 'update'])->name('profile.update');
  Route::get('/sell', [ItemController::class, 'create'])->name('sell');
  Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});


Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');
