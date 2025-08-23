<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanPangan extends Model
{
    use HasFactory;

    protected $table = 'bahan_pangans'; 
    protected $fillable = [];

    public function kelompokPangan()
    {
        return $this->belongsTo(KelompokPangan::class, 'kelompok_pangan_id');
    }
     public function resep()
    {
        return $this->hasMany(Resep::class);
    }
}
