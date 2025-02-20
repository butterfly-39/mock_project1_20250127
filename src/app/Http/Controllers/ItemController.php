<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;

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

    public function sell_update(ExhibitionRequest $request)
    {
        $item = $request->only('image', 'category', 'condition', 'name', 'brand_name', 'description', 'price');
        $item['user_id'] = auth()->id();
        Item::create($item);
        return redirect('/');
    }
}


