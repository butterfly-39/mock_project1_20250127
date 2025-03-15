<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $profile = auth()->user()->profile;  // ユーザーのプロフィール情報を取得
        
        return view('items.purchase', [
            'item' => $item,
            'profile' => $profile
        ]);
    }
} 