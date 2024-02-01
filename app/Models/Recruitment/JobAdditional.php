<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class JobAdditional extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    public function jobJobAdditional(){
        return $this->hasMany(JobJobAdditional::class, 'job_additional_id');
    }
}
