<?php

namespace App\Models\livechat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    
    protected $fillable = ['name'];

    public function members()
    {
        return $this->hasMany(MemberChat::class,'chat_room_id');
    }
    public function groupmembers()
    {
        return $this->hasMany(GroupMessage::class,'chat_room_id');
    }
}
