<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollegeImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'college_id',
        'image_url',
    ];
    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
