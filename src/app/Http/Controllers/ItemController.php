<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\ItemCategory;
use App\Models\Comment;


class ItemController extends Controller
{
    public function items_view(Request $request)
    {
        $tab = $request->query('tab', 'recommended');
        if ($tab === 'mylist') {
            // お気に入りに登録した商品を取得
            $items = auth()->user()->favorites()->with('item')->get()->pluck('item');
        } else {
            // 他のユーザーが出品した商品を取得
            $items = Item::where('user_id', '!=', auth()->id())->get();
        }

        return view('items.index', compact('items'));
    }

    public function item_show($item_id)
    {
        $item = Item::with(['itemCategories.category'])->findOrFail($item_id);
        $comment = Comment::find($item_id);
        return view('items.show', compact('item', 'comment'));
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

        return redirect('/');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $items = Item::where('name', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('items.index', compact('items', 'query'));
    }
}


