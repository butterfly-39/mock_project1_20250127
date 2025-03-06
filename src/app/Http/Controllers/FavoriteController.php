<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function item_favorite_create($item_id)
    {
        $item = Item::find($item_id);
        $item->favorites()->create(['user_id' => Auth::user()->id]);
        return redirect()->back();
    }

    public function item_favorite_delete($item_id)
    {
        $item = Item::find($item_id);
        $item->favorites()->where('user_id', Auth::user()->id)->delete();
        return redirect()->back();
    }
}

