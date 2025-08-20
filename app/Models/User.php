<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'user_id');
    }
}
