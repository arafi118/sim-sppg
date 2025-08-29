<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Po extends Model
{
    use HasFactory;
    protected $table = 'po';
    protected $guarded = [];


    public function poDetail()
    {
        return $this->hasMany(PoDetail::class, 'po_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
