<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareerGuidanceRegistration extends Model
{
     use HasFactory;
    protected $fillable = [
        'career_guidance_banner_id',
        'name',
        'email',
        'phone'
    ];

    public function banner()
{
    return $this->belongsTo(\App\Models\CareerGuidanceBanner::class, 'career_guidance_banner_id');
}

}