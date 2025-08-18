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
        $query = $request->query('query');
        
        $items = Item::query();
        
        // 検索クエリがある場合
        if ($query) {
            $items = $items->where('name', 'LIKE', "%{$query}%");
        }
        
        // タブに応じてフィルタリング
        if ($tab === 'mylist') {
            if (auth()->check()) {
                $favoriteItemIds = auth()->user()->favorites()->pluck('item_id');
                $items = $items->whereIn('id', $favoriteItemIds);
            } else {
                $items = $items->whereRaw('1 = 0'); // 空の結果を返す
            }
        } else {
            $items = $items->where('user_id', '!=', auth()->id());
        }
        
        $items = $items->orderBy('created_at', 'desc')->get();
        
        return view('items.index', compact('items', 'query'));
    }

    public function item_show($item_id)
    {
        $item = Item::with(['itemCategories.category'])->findOrFail($item_id);
        $comment = Comment::find($item_id);
        return view('items.show', compact('item', 'comment'));
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

    public function index()
    {
        $items = Item::where('status', 'trading')->get();
        return view('items.index', compact('items'));
    }
}


