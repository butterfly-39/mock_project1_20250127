<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;

class SellerChatController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        
        // 出品者かどうかチェック
        if ($item->user_id !== $user->id) {
            abort(403, 'この商品のチャットにアクセスする権限がありません。');
        }
        
        // メッセージを取得
        $messages = Message::where('item_id', $item_id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // 購入者情報を取得
        $order = $item->orders()->first();
        
        return view('sellers.chat', compact('item', 'messages', 'order'));
    }
}
