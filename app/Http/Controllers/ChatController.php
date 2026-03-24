<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'user_name' => $request->user_name,
            'content' => $request->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
