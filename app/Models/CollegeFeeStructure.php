<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CollegeFeeBreakdown;

class CollegeFeeStructure extends Model
{
    protected $fillable = [
        'course_id',
        'fee_type',
        'fee_mode',
        'total_amount',
        'currency'
    ];
    
    public function breakdowns()
    {
        return $this->hasMany(CollegeFeeBreakdown::class, 'fee_structure_id')
                    ->orderBy('sequence');
    }

    public function collegeCourse()
    {
        return $this->belongsTo(CollegeCourse::class);
    }
    public function feeStructures()
    {
        return $this->hasMany(CollegeFeeStructure::class, 'course_id');
    }
}

