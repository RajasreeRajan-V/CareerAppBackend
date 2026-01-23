<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
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
}
