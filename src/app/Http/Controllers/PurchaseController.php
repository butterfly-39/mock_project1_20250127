<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Order;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase_view($item_id)
    {
        try {
            $item = Item::find($item_id);
            $is_item_sold = Order::where('item_id', $item_id)->exists();
            if ($is_item_sold) {
                return redirect('/')->with('error', 'この商品は既に売り切れです。');
            }
            $profile = Profile::find($item->user_id);
            return view('items.purchase', compact('item', 'profile'));

        } catch (\Exception $e) {
            // エラーの場合は商品一覧ページにリダイレクト
            return redirect('/')->with('error', '商品が見つかりませんでした。');
        }
    }

    public function purchase_update(PurchaseRequest $request, $item_id)
    {
        $item = Item::find($item_id);
        $profile = Profile::find($item->user_id);

        // プロフィール情報の存在チェックを追加
        if (!$profile || !$profile->postal_code || !$profile->address) {
            return redirect()->back()->with('error', '配送先情報が不完全です。');
        }

        // トランザクション開始
        DB::transaction(function () use ($item, $request) {
            // 注文を作成
            $order = Order::create([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'payment_method' => $request->payment_method
            ]);

            // 商品のステータスを「sold」に更新
            $item->update(['status' => 'sold']);
        });

        // 商品一覧ページにリダイレクト
        return redirect('/');
    }

    public function address_view($item_id)
    {
        $item = Item::find($item_id);
        return view('profiles.address', ['item' => $item]);
    }

    public function address_update(AddressRequest $request, $item_id)
    {
        $item = Item::find($item_id);
        $profile = Profile::find($item->user_id);
        $profile->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);
        return view('items.purchase', [
            'item' => $item,
            'profile' => $profile
        ]);
    }
}

