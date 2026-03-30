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
        Schema::create('arus_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akun', 50)->default('0')->nullable();
            $table->string('urutan', 2)->default('0')->nullable();
            $table->string('sub', 2)->default('0')->nullable();
            $table->string('super_sub', 2)->default('0')->nullable();
            $table->text('rekening')->nullable();
            $table->string('status', 2)->default('0')->nullable();
            // Engine and Charset are usually handled by Laravel defaults, 
            // but MyISAM and Latin1 were in SQL. We'll stick to Laravel defaults (InnoDB/utf8mb4) 
            // unless specifically required.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arus_kas');
    }
};
