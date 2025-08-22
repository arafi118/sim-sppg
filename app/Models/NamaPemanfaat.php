<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaPemanfaat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dataPemanfaat()
    {
        return $this->belongsTo(DataPemanfaat::class, 'data_pemanfaat_id');
    }
}
