<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPemanfaat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pemanfaat()
    {
        return $this->hasMany(DataPemanfaat::class, 'kelompok_pemanfaat_id');
    }
}
