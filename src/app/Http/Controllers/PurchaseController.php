<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function purchase_view($item_id)
    {
        $item = Item::find($item_id);
        return view('items.purchase', ['item' => $item]);
    }

    public function purchase_update($item_id)
    {
        return view('items.purchase', ['item_id' => $item_id]);
    }

    public function address_view($item_id)
    {
        return view('profiles.address', ['item_id' => $item_id]);
    }

    public function address_update($item_id)
    {
        return view('profiles.address', ['item_id' => $item_id]);
    }
}

