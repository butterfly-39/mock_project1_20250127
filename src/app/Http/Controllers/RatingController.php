<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5'
        ]);

        // 既に評価済みかチェック
        $existingRating = Rating::where([
            'item_id' => $request->item_id,
            'order_id' => $request->order_id
        ])->first();

        if ($existingRating) {
            return redirect()->back()->with('error', '既に評価済みです');
        }

        // 評価を保存
        Rating::create([
            'item_id' => $request->item_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating
        ]);

        // 商品のステータスを完了に変更
        $item = Item::find($request->item_id);
        $item->update(['status' => 'completed']);

        return redirect()->route('items.index')->with('success', '評価を送信しました');
    }
} 