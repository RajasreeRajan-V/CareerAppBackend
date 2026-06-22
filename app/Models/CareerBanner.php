<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerBanner extends Model
{
    protected $table = 'career_banners';
    protected $fillable = ['title', 'image'];
}
