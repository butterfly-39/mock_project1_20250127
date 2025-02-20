<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    public function items_view()
    {
        return view('items.index');
    }

    public function item_show($item_id)
    {
        return view('items.show', compact('item_id'));
    }

    public function mylist_view()
    {
        return view('items.mylist');
    }

    public function sell_view()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('items.sell', compact('categories', 'conditions'));
    }

    public function sell_update()
    {
        return view('items.sell');
    }
}

