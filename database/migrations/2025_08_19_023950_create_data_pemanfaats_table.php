<?php

use App\Models\KelompokPemanfaat;
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
        Schema::create('data_pemanfaats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(KelompokPemanfaat::class);
            $table->string('nama_lembaga');
            $table->text('alamat');
            $table->string('nama_pj');
            $table->string('jabatan_pj');
            $table->string('telpon_pj');
            $table->string('email_pj');
            $table->string('jarak_tempuh');
            $table->string('waktu_tempuh_roda_2');
            $table->string('waktu_tempuh_roda_4');
            $table->string('jumlah_pemanfaat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pemanfaats');
    }
};
