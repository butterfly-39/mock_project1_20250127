<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;

class BuyerChatController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        
        // 購入者かどうかチェック
        $isBuyer = $item->orders()->where('user_id', $user->id)->exists();
        
        if (!$isBuyer) {
            abort(403, 'この商品のチャットにアクセスする権限がありません。');
        }
        
        // メッセージを取得
        $messages = Message::where('item_id', $item_id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('buyers.chat', compact('item', 'messages'));
    }
}
