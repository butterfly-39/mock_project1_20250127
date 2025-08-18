<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;

class BuyerChatController extends Controller
{
    public function show($item_id)
    {
        $item = Item::with(['user', 'orders.user'])->findOrFail($item_id);
        if ($item->orders->first()->user_id !== auth()->id()) {
            abort(403);
        }
        $seller = $item->user;
        $messages = Message::where('item_id', $item_id)
            ->where('is_deleted', false)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // 相手からの未読メッセージを既読にする
        Message::where('item_id', $item_id)
            ->where('user_id', '!=', auth()->id())  // 相手からのメッセージ
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return view('buyers.chat', compact('item', 'messages', 'seller'));
    }
}
 