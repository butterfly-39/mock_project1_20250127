<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Added missing import for User model

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'rating' => 'required|integer|between:1,5'
        ]);

        // 現在のユーザーと商品情報を取得
        $currentUser = Auth::user();
        $item = Item::find($request->item_id);
        
        // 購入者か出品者かを判定してorder_idを取得
        $order = null;
        $isBuyer = false;
        $buyer = null;
        
        // 購入者の場合
        $buyerOrder = Order::where('item_id', $request->item_id)
            ->where('user_id', $currentUser->id)
            ->first();
            
        if ($buyerOrder) {
            $order = $buyerOrder;
            $isBuyer = true;
            $buyer = $currentUser;
        } else {
            // 出品者の場合
            if ($item->user_id === $currentUser->id) {
                $sellerOrder = Order::where('item_id', $request->item_id)->first();
                if ($sellerOrder) {
                    $order = $sellerOrder;
                    $isBuyer = false;
                    // 購入者情報を取得
                    $buyer = User::find($sellerOrder->user_id);
                }
            }
        }

        if (!$order) {
            return redirect()->back()->with('error', '取引が見つかりません');
        }

        // 既に評価済みかチェック（現在のユーザーがこの商品を評価済みか）
        $existingRating = Rating::where([
            'item_id' => $request->item_id,
            'order_id' => $order->id,
            'rater_id' => $currentUser->id
        ])->first();

        if ($existingRating) {
            return redirect()->back()->with('error', '既に評価済みです');
        }

        try {
            // 評価を保存
            $rating = Rating::create([
                'item_id' => $request->item_id,
                'order_id' => $order->id,
                'rater_id' => $currentUser->id,  // 評価する人（現在のユーザー）
                'rated_id' => $isBuyer ? $item->user_id : $buyer->id,  // 評価される人
                'rating' => $request->rating
            ]);
            
            // 購入者・出品者ともに商品一覧画面に遷移
            return redirect()->route('items.index')->with('success', '評価を送信しました');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '評価の保存に失敗しました');
        }
    }
} 