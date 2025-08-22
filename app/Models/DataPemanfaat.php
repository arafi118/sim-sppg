<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPemanfaat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kelompokPemanfaat()
    {
        return $this->belongsTo(KelompokPemanfaat::class, 'kelompok_pemanfaat_id');
    }

    public function namaPemanfaat()
    {
        return $this->hasMany(NamaPemanfaat::class, 'data_pemanfaat_id');
    }
}
