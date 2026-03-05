<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerGuidanceBanner extends Model
{
    /** @use HasFactory<\Database\Factories\CareerGuidanceBannerFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'instructor_name',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'google_meet_link',
        'image',
    ];
}
