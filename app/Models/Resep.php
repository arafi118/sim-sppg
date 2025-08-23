<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;
    protected $table = 'reseps';
    protected $fillable = [];

    public function bahanPangan()
    {
        return $this->belongsTo(BahanPangan::class, 'bahan_pangan_id');
    }
      public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
