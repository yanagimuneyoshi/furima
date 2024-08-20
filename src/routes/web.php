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
use App\Http\Controllers\MypageController;
// ホームページ
Route::get('/', [ItemController::class, 'index'])->name('home');

// ユーザー登録と認証
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 認証が必要なルート
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

// 商品表示と検索
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');
Route::post('/favorites/toggle/{item}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::get('/search', [SearchController::class, 'search'])->name('item.search');

// コメント管理
Route::get('/comments/{item_id}', [CommentController::class, 'show'])->name('comments.show');
Route::post('/comments/{item_id}', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// 購入と決済
Route::post('/buy/{id}', [PurchaseController::class, 'buy'])->name('buy');
Route::post('/purchase/payment/{item_id}', [PurchaseController::class, 'processPayment'])->name('stripe.payment');
Route::post('/purchase/charge', [PurchaseController::class, 'charge']);
Route::post('/purchase/save', [PurchaseController::class, 'savePurchaseData']);
Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
