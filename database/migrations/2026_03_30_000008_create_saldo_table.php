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
        Schema::create('saldo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rekening_id')->nullable();
            $table->string('bulan', 5)->nullable();
            $table->string('tahun', 10)->nullable();
            $table->string('debit', 50)->nullable();
            $table->string('kredit', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo');
    }
};
