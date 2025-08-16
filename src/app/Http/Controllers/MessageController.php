<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    public function store(MessageRequest $request)
    {
        $message = new Message();
        $message->item_id = $request->item_id;
        $message->user_id = auth()->id();
        $message->message = $request->message;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('messages', 'public');
            $message->image = $path;
        }
        
        $message->save();
        
        return redirect()->back()->with('success', 'メッセージを送信しました。');
    }
}
