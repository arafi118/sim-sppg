<?php

use App\Models\KelompokPangan;
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
        Schema::create('bahan_pangans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(KelompokPangan::class);
            $table->string('nama');
            $table->string('satuan');
            $table->integer('harga_jual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_pangans');
    }
};
