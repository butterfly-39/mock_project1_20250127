<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;

class SellerChatController extends Controller
{
    public function show($item_id)
    {
        $item = Item::with(['user', 'orders.user'])->findOrFail($item_id);
        
        // 出品者かどうかチェック
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }
        
        // 購入者を取得
        $buyer = $item->orders->first()->user ?? null;
        
        // メッセージを取得
        $messages = Message::where('item_id', $item_id)
            ->where('is_deleted', false) // ← 削除済みメッセージを除外
            ->orderBy('created_at', 'asc')
            ->get();
        
        // 他の取引中の商品を取得（現在の商品以外）
        $otherTradingItems = Item::where('user_id', auth()->id())
            ->where('status', 'trading')
            ->where('id', '!=', $item_id)
            ->get();
        
        // 相手からの未読メッセージを既読にする
        Message::where('item_id', $item_id)
            ->where('user_id', '!=', auth()->id())  // 相手からのメッセージ
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return view('sellers.chat', compact('item', 'messages', 'buyer', 'otherTradingItems'));
    }
}
 