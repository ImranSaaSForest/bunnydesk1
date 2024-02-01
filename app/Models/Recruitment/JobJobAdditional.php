<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobJobAdditional extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'job_additional_id',
        'required',
    ];

    public function job()
    {
        return $this->belongsTo(job::class, 'job_id');
    }
    public function jobAdditional()
    {
        return $this->belongsTo(JobAdditional::class, 'job_additional_id');
    }
}
