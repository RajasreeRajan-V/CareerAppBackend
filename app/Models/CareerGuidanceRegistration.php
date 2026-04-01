<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerGuidanceRegistration extends Model
{
    protected $fillable = [
        'career_guidance_banner_id',
        'name',
        'email',
        'phone'
    ];
}