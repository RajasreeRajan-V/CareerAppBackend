<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $hidden = [];

    protected $casts = [];

    public function children()
    {
        return $this->hasMany(Children::class);
    }

    public function savedColleges()
    {
        return $this->hasMany(SavedCollege::class);
    }
}