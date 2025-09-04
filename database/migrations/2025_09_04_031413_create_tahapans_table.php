<?php

use App\Models\Penyiapan;
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
        Schema::create('tahapans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Penyiapan::class);
            $table->string('tahapan');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahapans');
    }
};
