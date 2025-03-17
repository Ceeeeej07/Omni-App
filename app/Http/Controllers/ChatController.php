<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chatapp()
    {   
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('chat-app', compact('users'));
    }

    public function chat($id)
    {
        return view('chat', compact('id'));
    }
}
