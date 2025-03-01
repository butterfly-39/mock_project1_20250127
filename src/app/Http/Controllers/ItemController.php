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
        $item = $request->only('condition_id', 'name', 'brand_name', 'description', 'price');
        // 画像がアップロードされた場合の処理
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('item_images', 'public');
            $item['image'] = $path;
        }
        
        $item['user_id'] = auth()->id();
        
        // itemを作成し、カテゴリーを関連付ける
        $newItem = Item::create($item);
        
        // カテゴリーの保存
        if ($request->has('category')) {
            $newItem->categories()->attach($request->input('category'));
        }

        return redirect('/mypage/sell');
    }
}


