<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyiapan extends Model
{
    use HasFactory;

    protected $table = 'penyiapans';
    protected $guarded = ['id'];

    public function tahapan()
    {
        return $this->hasMany(Tahapan::class);
    }
}
