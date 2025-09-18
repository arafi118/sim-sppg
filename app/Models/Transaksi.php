<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
    protected $guarded = [];

     public function poDetail()
    {
        return $this->belongsTo(PoDetail::class, 'po_detail_id');
    }

    public function rekeningDebit() {
        return $this->belongsTo(Rekening::class, 'rekening_debit');
    }

    public function rekeningKredit() {
        return $this->belongsTo(Rekening::class, 'rekening_kredit');
    }

      public function po()
    {
        return $this->belongsTo(Po::class, 'po_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
      public function getProgressAttribute()
    {
        $totalTransaksi = $this->transaksi()->sum('jumlah');
        return $this->total_harga > 0 ? ($totalTransaksi / $this->total_harga) * 100 : 0;
    }

    // Helper untuk cek apakah lunas
    public function isPaid()
    {
        return $this->transaksi()->sum('jumlah') >= $this->total_harga;
    }
}
