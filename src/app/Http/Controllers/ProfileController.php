<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Order; // Added this import for Order model

class ProfileController extends Controller
{
    public function mypage_view(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'sell');

        if ($tab === 'buy') {
            $items = Order::where('user_id', $user->id)->with('item')->get();
            $tradingItems = collect(); // 空のコレクション
            $tradingCount = 0;
        } elseif ($tab === 'trading') {
            $items = collect(); // 空のコレクション
            $tradingItems = Item::where('user_id', $user->id)
                ->where('status', 'trading') // 取引中のステータス
                ->get();
            $tradingCount = $tradingItems->count();
        } else {
            // sellタブ（デフォルト）
            $items = Item::where('user_id', $user->id)->get();
            $tradingItems = collect(); // 空のコレクション
            $tradingCount = 0;
        }

        return view('profiles.mypage', compact('user', 'items', 'tradingItems', 'tradingCount'));
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

