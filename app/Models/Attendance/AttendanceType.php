<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description'
    ];
    public function attendanceRecord()
    {
        return $this->hasOne(AttendanceRecord::class, 'attendance_type_id');
    }
}
