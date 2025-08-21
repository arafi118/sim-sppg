<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPangan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function bahanPangan()
    {
        return $this->hasMany(BahanPangan::class, 'kelompok_pangan_id');
    }
}
