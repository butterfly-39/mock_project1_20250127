<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function items_view()
    {
        return view('items.index');
    }

    public function item_show($item_id)
    {
        return view('items.show', ['item_id' => $item_id]);
    }
}

