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
        $item = Item::findOrFail($item_id);

        $is_item_sold = Order::where('item_id', $item_id)->exists();
        if ($is_item_sold) {
            return redirect('/');
        }

        $profile = Profile::where('user_id', auth()->id())->first();

        return view('items.purchase', compact('item', 'profile'));
    }

    public function purchase_update(PurchaseRequest $request, $item_id)
    {
        $item = Item::find($item_id);
        $profile = Profile::where('user_id', auth()->id())->first();


        if (!$profile || !$profile->postal_code || !$profile->address) {
            return redirect()->back()->with('error', '配送先情報が不完全です。');
        }

        // トランザクション開始
        DB::transaction(function () use ($item, $request, $profile) {
    
            $order = Order::create([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'order_postal_code' => $profile->postal_code,
                'order_address' => $profile->address,
                'order_building' => $profile->building,
                'status' => 'pending'
            ]);

    
            $item->update(['status' => 'trading']); // ✅ 修正
        });

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
        $profile = Profile::where('user_id', auth()->id())->first();
        
        $profile->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect("/purchase/{$item_id}");
    }
}

