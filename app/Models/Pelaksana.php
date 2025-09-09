<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaksana extends Model
{
    use HasFactory;

    protected $table = 'pelaksanas';
    protected $guarded = ['id'];

    public function tahapan()
    {
        return $this->belongsTo(Tahapan::class,  'tahapan_id', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
