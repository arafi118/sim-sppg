<?php

use App\Models\KelompokPemanfaat;
use App\Models\PeriodeMasak;
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
        Schema::create('rancangans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(PeriodeMasak::class);
            $table->foreignIdFor(KelompokPemanfaat::class);
            $table->date('tanggal');
            $table->string('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rancangans');
    }
};
