<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;

class ProfileController extends Controller
{
    public function mypage_view(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'sell'); // デフォルトは'sell'

        if ($tab === 'buy') {
            // 購入した商品を取得（itemリレーションを事前ロード）
            $items = $user->orders()
                ->with('item')  // itemリレーションを事前ロード
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // 出品した商品を取得
            $items = $user->items()
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('profiles.mypage', compact('user', 'items'));
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

