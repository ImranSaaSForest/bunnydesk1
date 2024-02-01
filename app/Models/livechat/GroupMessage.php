<?php

namespace App\Models\livechat;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['chat_room_id', 'user_id', 'content','filename','originalfilename'];

    public function groupChat()
    {
        return $this->belongsTo(ChatRoom::class,'chat_room_id');
    }

    public function chatuser()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
