<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BuyerChatController;
use App\Http\Controllers\SellerChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RatingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'mypage_view']);
    Route::get('/sell', [ItemController::class, 'sell_view']);
    Route::post('/sell', [ItemController::class, 'sell_update']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit_view']);
    Route::post('/mypage/profile', [ProfileController::class, 'edit_update']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchase_view']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase_update']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address_view']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'address_update']);
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'item_favorite_create']);
    Route::delete('/item/{item_id}/favorite', [FavoriteController::class, 'item_favorite_delete']);
    Route::post('/item/{item_id}/comment', [CommentController::class, 'item_comment_create']);
    Route::get('/buyers/chat/{item_id}', [BuyerChatController::class, 'show'])->name('buyers.chat');
    Route::get('/sellers/chat/{item_id}', [SellerChatController::class, 'show'])->name('sellers.chat');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
});

Route::get('/', [ItemController::class, 'items_view']);
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'item_show']);

