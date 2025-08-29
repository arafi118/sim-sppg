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

    public function kelompokPemanfaat()
    {
        return $this->belongsTo(KelompokPemanfaat::class, 'kelompok_pemanfaat_id');
    }

    public function rancanganMenu()
    {
        return $this->hasMany(RancanganMenu::class, 'rancangan_id');
    }
}
