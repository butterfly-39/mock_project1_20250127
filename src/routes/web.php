<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
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
    Route::get('/mypage?tab=buy', [ProfileController::class, 'buy_view']);
    Route::get('/mypage?tab=sell', [ProfileController::class, 'sell_view']);
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
    Route::get('/search', [ItemController::class, 'search']);
});
Route::get('/', [ItemController::class, 'items_view']);
Route::get('/item/{item_id}', [ItemController::class, 'item_show']);

