<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CollegeFacility extends Model
{
    use HasFactory;
    protected $fillable = [
        'college_id',
        'facility',
    ];
    public function college()
    {
        return $this->belongsTo(College::class); 
    }
}
