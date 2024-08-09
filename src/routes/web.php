<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;



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
  Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase');
  Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address');
  Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');
});


Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');
// Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase');
Route::post('/buy/{id}', [PurchaseController::class, 'buy'])->name('buy');
Route::post('/favorites/toggle/{item}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// コメント表示と投稿のルート
Route::get('/comments/{item_id}', [CommentController::class, 'show'])->name('comments.show');
Route::post('/comments/{item_id}', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::get('/search', [SearchController::class, 'search'])->name('item.search');