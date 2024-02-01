<?php

namespace App\Models\Performance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_score',
        'final_comments',
        'status',
    ];
    public function appraisalMessage()
    {
        return $this->hasMany(AppraisalMessage::class);
    }
}
