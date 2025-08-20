<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;
use App\Models\Rating;

class BuyerChatController extends Controller
{
	public function show($item_id)
	{
		$item = Item::with(['user', 'orders.user'])->findOrFail($item_id);
		if ($item->orders->first()->user_id !== auth()->id()) {
			abort(403);
		}

		$order = $item->orders->first();
		if ($order && Rating::where('item_id', $item_id)
			->where('order_id', $order->id)
			->where('rater_id', auth()->id())
			->exists()) {
			return redirect()->back()->with('info', 'この取引は評価済みです');
		}

		$seller = $item->user;
		$messages = Message::where('item_id', $item_id)
			->where('is_deleted', false)
			->orderBy('created_at', 'asc')
			->get();


		$otherTradingItems = Item::whereHas('orders', function($query) {
			$query->where('user_id', auth()->id());
		})
		->where('id', '!=', $item_id)
		->where('status', 'trading')
		->get();

		Message::where('item_id', $item_id)
		->where('user_id', '!=', auth()->id())
		->where('is_read', false)
		->update([
				'is_read' => true,
				'read_at' => now()
		]);

		return view('buyers.chat', compact('item', 'messages', 'seller', 'otherTradingItems'));
	}
}
