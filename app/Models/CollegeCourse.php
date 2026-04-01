<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CollegeFeeStructure;
class CollegeCourse extends Model
{
    protected $fillable = ['college_id', 'course_id', 'duration_years'];

public function feeStructures()
{
    return $this->hasMany(CollegeFeeStructure::class, 'college_course_id');
}

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
