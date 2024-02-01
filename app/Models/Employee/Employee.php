<?php

namespace App\Models\Employee;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Spatie\MediaLibrary\InteractsWithMedia;

class Employee extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'company_id',
        'date_of_birth',
        'gender_id',
        'marital_status_id',
        'social_security_number',
        'timezone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function getProfilePictureUrlAttribute()
    {
        $profileImage = $this->getMedia('employee-images')->first();

        if (!$profileImage) {
            return 'https://via.placeholder.com/150';
        }

        return $profileImage->getUrl();
    }

}
