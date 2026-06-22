<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
   protected $fillable = [
        'fcm_token',
        'platform',
        'app_version',
        'model',
        'model_name',
        'model_version',
        'failed_at',      // add this
        'fail_reason',
    ];
}
