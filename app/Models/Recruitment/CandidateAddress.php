<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateAddress extends Model
{
    use HasFactory;
    protected $fillable = [

        'candidate_id',
        'flat_no',
        'street_1',
        'street_2',
        'city',
        'province',
        'postal_code',
        'country',
        'country_id',

    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

}
