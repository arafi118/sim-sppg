<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;
    protected $table = 'rekenings';
    protected $guarded = [];

    public function transaksiDebit()
    {
        return $this->hasMany(Transaksi::class, 'rekening_debit', 'id');
    }

    public function transaksiKredit()
    {
        return $this->hasMany(Transaksi::class, 'rekening_kredit', 'id');
    }
    
     public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'rekening_debit', 'id')
            ->orWhere('rekening_kredit', $this->id);
    }

}
