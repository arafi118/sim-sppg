<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $guarded = [];

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }
      public function rancanganMenu()
    {
        return $this->hasMany(RancanganMenu::class);
    }
}
