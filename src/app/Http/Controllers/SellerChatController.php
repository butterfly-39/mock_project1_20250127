<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use App\Models\Rating;
use App\Models\Order;
use Illuminate\Http\Request;

class SellerChatController extends Controller
{
    public function show($item_id)
    {
        $item = Item::with(['user', 'orders.user'])->findOrFail($item_id);
        
        // 出品者かどうかチェック
        if ($item->user_id !== auth()->id()) {
            abort(403, 'この商品のチャット画面にアクセスする権限がありません');
        }
        
        // 購入者を取得（既存のリレーションを使用）
        $buyer = $item->orders->first()->user ?? null;
        
        if (!$buyer) {
            abort(404, '購入者情報が見つかりません');
        }
        
        // 注文情報を取得
        $order = $item->orders->first();
        
        // 他の取引中の商品を取得
        $otherTradingItems = Item::where('user_id', auth()->id())
            ->where('status', 'trading')
            ->where('id', '!=', $item_id)
            ->get();
        
        // メッセージを取得
        $messages = Message::where('item_id', $item_id)
            ->where('is_deleted', false)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // 購入者が評価済みかどうかをチェック
        $hasRated = false;
        if ($order) {
            // この商品に対する評価が存在するかチェック
            $hasRated = Rating::where('item_id', $item_id)
                ->where('order_id', $order->id)
                ->exists();
        }
        
        // 出品者が購入者を評価済みかどうかをチェック
        $sellerHasRated = false;
        if ($order) {
            $sellerHasRated = Rating::where('item_id', $item_id)
                ->where('order_id', $order->id)
                ->where('rater_id', auth()->id())  // 出品者（現在のユーザー）
                ->where('rated_id', $buyer->id)    // 購入者
                ->exists();
        }
        
        // デバッグ用のログ出力
        \Log::info('Seller chat debug:', [
            'item_id' => $item_id,
            'item_status' => $item->status,
            'has_order' => $order ? true : false,
            'order_id' => $order ? $order->id : null,
            'buyer_has_rating' => $hasRated,
            'seller_has_rating' => $sellerHasRated,
            'ratings_count' => Rating::where('item_id', $item_id)->count()
        ]);
        
        // 相手からの未読メッセージを既読にする
        Message::where('item_id', $item_id)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true
            ]);
        
        return view('sellers.chat', compact(
            'item', 
            'buyer', 
            'otherTradingItems', 
            'messages',
            'hasRated',
            'sellerHasRated'
        ));
    }
}
 