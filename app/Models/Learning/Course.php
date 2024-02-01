<?php

namespace App\Models\Learning;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'duration',
        'image',
        'instructor',
        'quiz_time'
    ];



    public function videoProgress()
    {
        return $this->hasMany(VideoProgress::class, 'course_id');
    }
    public function video()
    {
        return $this->hasMany(Video::class, 'course_id');
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }
    public function learningEmployee()
    {
        return $this->hasOne(LearningEmployee::class, 'course_id');
    }

    public function enrollmentCourse()
    {
        return $this->hasOne(EnrollmentCourse::class, 'course_id');
    }
     /**
     * Get the user who created the course.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the course.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (Auth::check()) {
                $course->created_by = Auth::id();
            }
        });

        static::updating(function ($course) {
            if (Auth::check()) {
                $course->updated_by = Auth::id();
            }
        });
    }
}
