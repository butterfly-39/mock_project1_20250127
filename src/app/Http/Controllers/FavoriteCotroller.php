<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function item_favorite_create($item_id)
    {
        return view('profiles.favorite', ['item_id' => $item_id]);
    }

    public function item_favorite_delete($item_id)
    {
        return view('profiles.favorite', ['item_id' => $item_id]);
    }
}

