<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Rancangan extends Model
{
    use HasFactory;
    protected $table = 'rancangans';
    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(PeriodeMasak::class, 'periode_masak_id');
    }

    public function dataPemanfaat()
    {
        return $this->belongsTo(DataPemanfaat::class, 'data_pemanfaat_id');
    }

    public function rancanganMenu()
    {
        return $this->hasMany(RancanganMenu::class, 'rancangan_id');
    }
}
