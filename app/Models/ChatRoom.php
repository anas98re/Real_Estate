<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    // Get the messages in the chat room
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_users')
                    ->withPivot('is_have_permission')
                    ->withTimestamps();
    }


    //Get the messages in the chat room
    public function messages()
    {
        return $this->hasMany(messagees_chat::class);
    }
}
