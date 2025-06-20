<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_email', 
        'sender_email', 
        'subject',
        'body',
        'attachment',
        'type',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
