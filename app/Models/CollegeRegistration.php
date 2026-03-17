<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CollegeRegistration extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CollegeRegistrationFactory> */
    use HasFactory;
    protected $fillable = [
        'college_name',
        'principal_name',
        'email',
        'contact_no',
        'website',
        'address',
        'city',
        'state',
        'pincode',
        'password',
    ];
}
