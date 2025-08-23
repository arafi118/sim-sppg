<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RancanganMenu extends Model
{
    use HasFactory;
    protected $table = 'rancangan_menus';
    protected $guarded = [];
    
    public function rancangan()
    {
        return $this->belongsTo(Rancangan::class, 'rancangan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
