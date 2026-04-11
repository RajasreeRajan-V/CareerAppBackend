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
        'newgen_course',
        'description',
        'video',
        'thumbnail',
        'specialization'
    ];

    protected $casts = [
        'newgen_course' => 'boolean',
    ];
    public function children()
    {
        return $this->hasMany(CareerLink::class, 'parent_career_id')
            ->with('child');
    }
    public function parents()
    {
        return $this->hasMany(CareerLink::class, 'child_career_id')
            ->with('parent');
    }
}
