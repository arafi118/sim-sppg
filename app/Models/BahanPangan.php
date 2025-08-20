<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanPangan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kelompokPangan()
    {
        return $this->belongsTo(KelompokPangan::class, 'kelompok_pangan_id');
    }
}
