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
