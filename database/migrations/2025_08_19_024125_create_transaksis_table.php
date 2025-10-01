<?php

use App\Models\Invoice;
use App\Models\Mitra;
use App\Models\Po;
use App\Models\User;
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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Mitra::class)->nullable();
            $table->foreignIdFor(Po::class)->nullable();
            $table->foreignIdFor(Invoice::class)->nullable();
            $table->date('tanggal');
            $table->string('rekening_debit');
            $table->string('rekening_kredit');
            $table->string('keterangan');
            $table->integer('jumlah');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
