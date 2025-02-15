<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function mypage_view()
    {
        return view('profiles.mypage');
    }

    public function edit_view()
    {
        return view('profiles.profile');
    }

    public function edit_update(Request $request)
    {
        return view('profiles.edit');
    }

    public function buy_view()
    {
        return view('profiles.buy');
    }

    public function sell_view()
    {
        return view('profiles.sell');
    }
}
