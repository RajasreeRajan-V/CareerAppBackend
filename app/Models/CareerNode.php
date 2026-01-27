<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerNode extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'subjects',
        'career_options',
        'description',
        'video',
        'thumbnail'
    ];

    protected $casts = [
        'subjects' => 'array',
        'career_options' => 'array',
    ];
}
