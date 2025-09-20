<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunLevel3 extends Model
{
    use HasFactory;

    protected $table = 'akun_level3s';
    
     public function rek()
    {
        return $this->hasMany(Rekening::class, 'parent_id', 'id')->orderBy('kode_akun', 'ASC');
    }
}
