<?php

use App\Models\Mitra;
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
        Schema::create('profils', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Mitra::class);
            $table->string('id_yayasan');
            $table->string('nama');
            $table->string('nama_mitra');
            $table->text('alamat');
            $table->string('telpon');
            $table->string('penanggung_jawab');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
