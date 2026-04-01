<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerLink extends Model
{
    protected $table = 'career_links';

    protected $fillable = [
        'parent_career_id',
        'child_career_id',
    ];

   public function parent()
    {
        return $this->belongsTo(CareerNode::class, 'parent_career_id');
    }

 public function child()
    {
        return $this->belongsTo(CareerNode::class, 'child_career_id');
    }
}
