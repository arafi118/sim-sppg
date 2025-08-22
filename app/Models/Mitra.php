<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bahanPangan()
    {
        return $this->belongsTo(BahanPangan::class, 'bahan_pangan_id');
    }
}
