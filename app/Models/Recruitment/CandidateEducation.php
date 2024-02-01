<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateEducation extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'college_name',
        'highest_education',
        'branch',
        'passed',
        'grade',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidate::class,'job_id');
    }
}
