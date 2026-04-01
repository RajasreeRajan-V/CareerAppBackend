<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegeFeeBreakdown extends Model
{
    protected $fillable = [
        'fee_structure_id',
        'label',
        'amount',
        'sequence'
    ];

    public function structure()
    {
        return $this->belongsTo(CollegeFeeStructure::class, 'fee_structure_id');
    }
}

