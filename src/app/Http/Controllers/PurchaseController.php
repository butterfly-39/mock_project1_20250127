<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Order;

class PurchaseController extends Controller
{
    public function purchase_view($item_id)
    {
        $item = Item::find($item_id);
        $profile = Profile::find($item->user_id);  // ログインユーザーのプロフィール情報を取得
        return view('items.purchase', compact('item', 'profile'));
    }

    public function purchase_update(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        // 注文を作成
        $order = Order::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method
        ]);

        // 商品のステータスを「sold」に更新
        $item->update(['status' => 'sold']);

        // 商品一覧ページにリダイレクト
        return redirect('/');
    }

    public function address_view($item_id)
    {
        $item = Item::find($item_id);
        return view('profiles.address', ['item' => $item]);
    }

    public function address_update($item_id)
    {
        $item = Item::find($item_id);
        return view('profiles.address', ['item' => $item]);
    }
}

