<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = [
        'college_id',
        'title',
        'message',
    ];

   public function college()
{
    return $this->belongsTo(College::class);
}
}
