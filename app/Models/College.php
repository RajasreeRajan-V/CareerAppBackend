<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class College extends Authenticatable
{
    use HasFactory;
   protected $fillable = [
    'name',
    'location',
    'rating',
    'phone',
    'email',
    'website',
    'about',
    'principal_name',
    'state_id',
    'district_id',
    'pincode',
    'is_verified',
    'password',
    'password_changed',
];
    public function images()
    {
        return $this->hasMany(CollegeImage::class);
    }
    public function facilities()
    {
        return $this->hasMany(CollegeFacility::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function collegeCourses()
    {
        return $this->hasMany(CollegeCourse::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
