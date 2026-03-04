<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerRecordVideo extends Model
{
    /** @use HasFactory<\Database\Factories\CareerRecordVideoFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'about',
        'url',
        'duration',
        'creator',
    ];
}
