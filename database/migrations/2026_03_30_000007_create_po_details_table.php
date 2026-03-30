<?php

use App\Models\BahanPangan;
use App\Models\Po;
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
        Schema::create('po_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Po::class);
            $table->foreignIdFor(BahanPangan::class);
            $table->string('harga_satuan', 255);
            $table->string('jumlah', 255);
            $table->string('jumlah_input', 255)->nullable();
            $table->string('jumlah_bayar', 255)->nullable();
            $table->string('total_harga', 255);
            $table->enum('status_bayar', ['UNPAID', 'PAID', 'PARTIAL']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_details');
    }
};
