<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chat_room_id',
        'is_have_permission'
    ];

    // Get the user associated with the chat room user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Get the chat room associated with the chat room user.
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
