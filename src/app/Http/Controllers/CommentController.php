<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function item_comment_create(CommentRequest $request, $item_id)
    {
        $item = Item::with('comments.user.profile')->findOrFail($item_id);
        $comment = new Comment();
        $comment->item_id = $item_id;
        $comment->user_id = auth()->id();
        $comment->comment = $request->comment;
        $comment->save();
        

        $item->load('comments.user.profile');
        return redirect()->back();
    }
}
