<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegeView extends Model
{
    protected $fillable = ['user_id', 'college_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
