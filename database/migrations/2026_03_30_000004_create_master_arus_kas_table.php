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
        Schema::create('master_arus_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akun', 255)->nullable();
            $table->string('debit', 50)->nullable();
            $table->string('kredit', 50)->nullable();
            $table->integer('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_arus_kas');
    }
};
