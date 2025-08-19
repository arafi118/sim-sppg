<?php

use App\Models\Menu;
use App\Models\Rancangan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rancangan_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Menu::class);
            $table->foreignIdFor(Rancangan::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rancangan_menus');
    }
};
