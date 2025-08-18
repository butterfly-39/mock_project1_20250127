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
            ->where('is_deleted', false) // ← 削除済みメッセージを除外
            ->orderBy('created_at', 'asc')
            ->get();
        return view('buyers.chat', compact('item', 'messages', 'seller'));
    }
}
