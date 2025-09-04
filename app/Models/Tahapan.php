<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahapan extends Model
{
    use HasFactory;

    protected $table = 'tahapans';
    protected $guarded = ['id'];

    public function penyiapan()
    {
        return $this->belongsTo(Penyiapan::class, 'penyiapan_id');
    }

    public function pelaksana()
    {
        return $this->hasMany(Pelaksana::class);
    }
}
