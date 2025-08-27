<?php

namespace Database\Seeders;

use App\Models\AkunLevel1;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunLevel1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AkunLevel1::insert([
            ["id" => "1", "lev1" => "1", "lev2" => "0", "lev3" => "0", "lev4" => "0", "kode_akun" => "1.0.00.00", "nama_akun" => "Aset", "jenis_mutasi" => "Debet", "created_at" => null, "updated_at" => null],
            ["id" => "2", "lev1" => "2", "lev2" => "0", "lev3" => "0", "lev4" => "0", "kode_akun" => "2.0.00.00", "nama_akun" => "Utang", "jenis_mutasi" => "Kredit", "created_at" => null, "updated_at" => null],
            ["id" => "3", "lev1" => "3", "lev2" => "0", "lev3" => "0", "lev4" => "0", "kode_akun" => "3.0.00.00", "nama_akun" => "Modal", "jenis_mutasi" => "Kredit", "created_at" => null, "updated_at" => null],
            ["id" => "4", "lev1" => "4", "lev2" => "0", "lev3" => "0", "lev4" => "0", "kode_akun" => "4.0.00.00", "nama_akun" => "Pendapatan", "jenis_mutasi" => "Kredit", "created_at" => null, "updated_at" => null],
            ["id" => "5", "lev1" => "5", "lev2" => "0", "lev3" => "0", "lev4" => "0", "kode_akun" => "5.0.00.00", "nama_akun" => "Beban", "jenis_mutasi" => "Debet", "created_at" => null, "updated_at" => null],
        ]);
    }
}
