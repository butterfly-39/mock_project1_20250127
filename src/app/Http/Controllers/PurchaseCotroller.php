<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function purchase_view($item_id)
    {
        return view('profiles.purchase', ['item_id' => $item_id]);
    }

    public function purchase_update($item_id)
    {
        return view('profiles.purchase', ['item_id' => $item_id]);
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

