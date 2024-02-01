<?php

namespace App\Models\Timesheet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProjectDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file',
        'link_to_file',
        'project_id',
        'original_file_name'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($projrctDoc) {
            if (Auth::check()) {
                $projrctDoc->created_by = Auth::id();
            }
        });
    }
}
