<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function mailbox()
    {
        return view('mailbox');
    }
    public function compose()
    {
        return view('compose');
    }
    
}
