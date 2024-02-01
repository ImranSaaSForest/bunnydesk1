<?php

namespace App\Models\Recruitment;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Candidate extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'job_id',
        'status',
        'first_name',
        'last_name',
        'email',
        'rating',
        'pnone_number',
        'photo',
        'job_accept_code',
        'offer_sent_by',
        'offer_sent_on',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }
    public function candidate_additional()
    {
        return $this->hasOne(CandidateAdditional::class);
    }
    public function candidate_address()
    {
        return $this->hasOne(CandidateAddress::class);
    }
    public function candidate_notes()
    {
        return $this->hasMany(CandidateNote::class);
    }
    public function candidate_visa_details()
    {
        return $this->hasOne(CandidateVisaDetail::class);
    }
    public function candidate_educations()
    {
        return $this->hasOne(CandidateEducation::class);
    }

}
