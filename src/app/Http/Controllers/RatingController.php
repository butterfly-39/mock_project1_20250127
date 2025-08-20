<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\TransactionCompletedMail;
use Illuminate\Support\Facades\Mail;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'rating' => 'required|integer|between:1,5'
        ]);


        $currentUser = Auth::user();
        $item = Item::find($request->item_id);

        $order = null;
        $isBuyer = false;
        $buyer = null;

        $buyerOrder = Order::where('item_id', $request->item_id)
            ->where('user_id', $currentUser->id)
            ->first();

        if ($buyerOrder) {
            $order = $buyerOrder;
            $isBuyer = true;
            $buyer = $currentUser;
        } else {
            if ($item->user_id === $currentUser->id) {
                $sellerOrder = Order::where('item_id', $request->item_id)->first();
                if ($sellerOrder) {
                    $order = $sellerOrder;
                    $isBuyer = false;
                    $buyer = User::find($sellerOrder->user_id);
                }
            }
        }

        if (!$order) {
            return redirect()->back()->with('error', '取引が見つかりません');
        }

        $existingRating = Rating::where([
            'item_id' => $request->item_id,
            'order_id' => $order->id,
            'rater_id' => $currentUser->id
        ])->first();

        if ($existingRating) {
            return redirect()->back()->with('error', '既に評価済みです');
        }

        try {
            $rating = Rating::create([
                'item_id' => $request->item_id,
                'order_id' => $order->id,
                'rater_id' => $currentUser->id,
                'rated_id' => $isBuyer ? $item->user_id : $buyer->id,
                'rating' => $request->rating
            ]);

            if (!$isBuyer) {
                $item->update(['status' => 'sold']);
            }

            if ($isBuyer) {
                try {
                    Mail::to($item->user->email)->send(new TransactionCompletedMail($item, $item->user, $buyer));
                } catch (\Exception $e) {
                    \Log::error('取引完了メール送信失敗: ' . $e->getMessage());
                }
            }


            return redirect()->route('items.index')->with('success', '評価を送信しました');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '評価の保存に失敗しました');
        }
    }
}