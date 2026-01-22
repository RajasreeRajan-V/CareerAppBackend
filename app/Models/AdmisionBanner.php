<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmisionBanner extends Model
{
    /** @use HasFactory<\Database\Factories\AdmisionBannerFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'description',
        'link',
    ];
}
