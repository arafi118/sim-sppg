<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDetail extends Model
{
    use HasFactory;
    protected $table = 'po_details';
    protected $guarded = [];
    
    public function po()
    {
        return $this->belongsTo(Po::class, 'po_id');
    }

    public function bahanPangan()
    {
        return $this->belongsTo(BahanPangan::class, 'bahan_pangan_id');
    }
     public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'po_detail_id');
    }
}
