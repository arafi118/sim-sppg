<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeMasak extends Model
{
    use HasFactory;

    public function rancangan()
    {
        return $this->hasMany(Rancangan::class, 'periode_masak_id');
    }
}
