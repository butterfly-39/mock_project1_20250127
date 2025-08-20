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

        if ($item->user_id !== auth()->id()) {
            abort(403, 'この商品のチャット画面にアクセスする権限がありません');
        }

        $buyer = $item->orders->first()->user ?? null;

        if (!$buyer) {
            abort(404, '購入者情報が見つかりません');
        }

        $order = $item->orders->first();

        $otherTradingItems = Item::where('user_id', auth()->id())
            ->where('status', 'trading')
            ->where('id', '!=', $item_id)
            ->get();

        $messages = Message::where('item_id', $item_id)
            ->where('is_deleted', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $hasRated = false;
        if ($order) {
            $hasRated = Rating::where('item_id', $item_id)
                ->where('order_id', $order->id)
                ->exists();
        }

        $sellerHasRated = false;
        if ($order) {
            $sellerHasRated = Rating::where('item_id', $item_id)
                ->where('order_id', $order->id)
                ->where('rater_id', auth()->id())
                ->where('rated_id', $buyer->id)
                ->exists();
        }

        \Log::info('Seller chat debug:', [
            'item_id' => $item_id,
            'item_status' => $item->status,
            'has_order' => $order ? true : false,
            'order_id' => $order ? $order->id : null,
            'buyer_has_rating' => $hasRated,
            'seller_has_rating' => $sellerHasRated,
            'ratings_count' => Rating::where('item_id', $item_id)->count()
        ]);

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
