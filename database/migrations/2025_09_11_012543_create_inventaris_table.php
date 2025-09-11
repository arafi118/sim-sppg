<?php

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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->date('tanggal_beli');
            $table->date('tanggal_validasi');
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('umur_ekonomis');
            $table->enum('jenis', ['ati', 'atb']);
            $table->integer('kategori');
            $table->enum('status', ['baik', 'rusak', 'hilang', 'jual']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
