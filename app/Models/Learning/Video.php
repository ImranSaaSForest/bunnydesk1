<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable=[
        'course_id',
        'video_path',

    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function videoProgress()
    {
        return $this->hasOne(VideoProgress::class, 'video_id');
    }
}
