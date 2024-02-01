<?php

namespace App\Models\livechat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberChat extends Model
{
    use HasFactory;

    protected $fillable = ['chat_room_id', 'user_id'];

    public function groupChat()
    {
        return $this->belongsTo(ChatRoom::class,'chat_room_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
