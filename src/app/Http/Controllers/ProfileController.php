<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order; // Added this import for Order model
use App\Models\Message; // Added this import for Message model

class ProfileController extends Controller
{
    public function mypage_view(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'sell');

        // 未読メッセージの件数を取得
        $unreadMessageCount = Message::where('user_id', '!=', $user->id)
            ->whereHas('item', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('is_read', false)
            ->count();

        if ($tab === 'buy') {
            // 購入した商品（売却済みの商品）
            $items = Item::where('status', 'sold')
                ->whereHas('orders', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();
            $tradingItems = collect();
            $tradingCount = 0;
        } elseif ($tab === 'trading') {
            // 取引中の商品（自分が出品した商品 + 自分が購入した商品）
            $items = collect();

            // 自分が出品した商品で取引中のもの
            $sellerTradingItems = Item::where('user_id', $user->id)
                ->where('status', 'trading')
                ->get();

            // 自分が購入した商品で取引中のもの
            $buyerTradingItems = Item::where('status', 'trading')
                ->whereHas('orders', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->get();

            // 両方を結合
            $tradingItems = $sellerTradingItems->merge($buyerTradingItems);
            $tradingCount = $tradingItems->count();
        } else {
            // sellタブ（デフォルト）：出品中の商品
            $items = Item::where('user_id', $user->id)
                ->where('status', 'available')
                ->get();
            $tradingItems = collect();
            $tradingCount = 0;
        }

        return view('profiles.mypage', compact('user', 'items', 'tradingItems', 'tradingCount', 'unreadMessageCount'));
    }

    public function edit_view()
    {
        $user = Auth::user();
        return view('profiles.profile', compact('user'));
    }

    public function edit_update(ProfileRequest $request)
    {
        $user = Auth::user();
        // usersテーブルの更新
        $user->update([
            'name' => $request->name
        ]);

        // profilesテーブル用のデータ
        $profile = $request->only(['postal_code', 'address', 'building']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('profile_images', 'public');
            $profile['image'] = $path;
        }

        // プロフィール情報を更新または作成
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profile
        );

        return redirect('/');
    }

}

