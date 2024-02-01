<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateAdditional extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'resume_path',
        'desired_salary',
        'cover_letter',
        'linkedin_url',
        'referredby',
        'reference',
        // 'date_available',
        'website_blog_portfolio',

    ];


    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

}
