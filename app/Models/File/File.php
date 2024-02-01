<?php

namespace App\Models\File;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class File extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'file_name',
        'fileable_id',
        'fileable_type',
    ];

    public function company()
    {
        return $this->morphedByMany(Company::class, 'fileable');
    }
    public function employees()
    {
        return $this->morphedByMany(User::class, 'fileable');
    }
    public function getCompanyNameAttribute()
    {
        return $this->company->first()?->name;
    }

    public function getFileUrlAttribute()
    {
        $profileImage = $this->getMedia('files')->first();

        if (!$profileImage) {
            return 'https://via.placeholder.com/150';
        }

        return $profileImage->getUrl();
    }

}
