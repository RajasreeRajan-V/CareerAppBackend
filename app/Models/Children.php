<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    protected $table = 'childrens';
    protected $fillable = [
        'user_id',
        'name',
        'education_level',
        'subject_group',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
