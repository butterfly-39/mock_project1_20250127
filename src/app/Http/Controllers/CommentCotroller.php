<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function item_comment_create($item_id)
    {
        return view('profiles.comment', ['item_id' => $item_id]);
    }
}

