<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateVisaDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'visa_type',
        'visa_number',
        'passport_number',
        'nationalid_number',
        'visa_path',
        'issue_date',
        'expiration_date',
        'country_of_origin',
        'is_valid',

    ];
    public function candidate()
    {
        return $this->belongsTo(Candidate::class,'job_id');
    }
}
