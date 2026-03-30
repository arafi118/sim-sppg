<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add reason column first
        Schema::table('po', function (Blueprint $table) {
            $table->text('alasan_batal')->after('status_bayar')->nullable();
        });

        // 2. Change column name and type using DB::statement for MySQL
        DB::statement("ALTER TABLE po CHANGE status_bayar status VARCHAR(50) DEFAULT 'DIBUAT'");

        // 3. Map old values to new values
        DB::table('po')->where('status', 'UNPAID')->update(['status' => 'DIBUAT']);
        DB::table('po')->whereIn('status', ['PAID', 'PARTIAL'])->update(['status' => 'DITERIMA']);

        // 4. Set final enum type
        DB::statement("ALTER TABLE po MODIFY status ENUM('DIBUAT', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN') DEFAULT 'DIBUAT'");

        // 5. Remove status_bayar from po_details
        Schema::table('po_details', function (Blueprint $table) {
            $table->dropColumn('status_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_details', function (Blueprint $table) {
            $table->enum('status_bayar', ['UNPAID', 'PAID', 'PARTIAL'])->default('UNPAID');
        });

        DB::statement("ALTER TABLE po CHANGE status status_bayar ENUM('UNPAID', 'PAID', 'PARTIAL') DEFAULT 'UNPAID'");

        Schema::table('po', function (Blueprint $table) {
            $table->dropColumn('alasan_batal');
        });
    }

};
