<?php

use App\Models\BahanPangan;
use App\Models\Invoice;
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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(BahanPangan::class);
            $table->foreignIdFor(Invoice::class);
            $table->integer('harga');
            $table->string('kebutuhan');
            $table->float('total', 50, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
