<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;

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

    public function edit_update(AddressRequest $request)
    {
        $user = auth()->user();
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('image_url', 'name', 'postal_code', 'address', 'building')
        );
        return redirect('/');
    }

    public function buy_view()
    {
        return view('profiles.mypage');
    }

    public function sell_view()
    {
        return view('profiles.mypage');
    }
}
