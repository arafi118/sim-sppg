<?php

namespace Database\Seeders;

use App\Models\BahanPangan;
use App\Models\Po;
use App\Models\PoDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PoSeeder extends Seeder
{
    /**
     * Seed PO data with various workflow statuses for testing.
     */
    public function run(): void
    {
        // Get some bahan pangan to use in PO details
        $bahanPangans = BahanPangan::inRandomOrder()->limit(10)->get();

        if ($bahanPangans->isEmpty()) {
            $this->command->warn('Tidak ada data BahanPangan. Silakan seed data BahanPangan terlebih dahulu.');
            return;
        }

        $poData = [
            // 2 PO with DIBUAT status (can be sent, cancelled, or edited)
            [
                'tanggal' => Carbon::today()->subDays(1)->toDateString(),
                'status' => 'DIBUAT',
                'alasan_batal' => null,
                'item_count' => 3,
            ],
            [
                'tanggal' => Carbon::today()->toDateString(),
                'status' => 'DIBUAT',
                'alasan_batal' => null,
                'item_count' => 2,
            ],
            // 1 PO with DIKIRIM status (can be received)
            [
                'tanggal' => Carbon::today()->subDays(3)->toDateString(),
                'status' => 'DIKIRIM',
                'alasan_batal' => null,
                'item_count' => 4,
            ],
            // 1 PO with DITERIMA status (completed)
            [
                'tanggal' => Carbon::today()->subDays(7)->toDateString(),
                'status' => 'DITERIMA',
                'alasan_batal' => null,
                'item_count' => 3,
            ],
            // 1 PO with DIBATALKAN status (with cancellation reason)
            [
                'tanggal' => Carbon::today()->subDays(5)->toDateString(),
                'status' => 'DIBATALKAN',
                'alasan_batal' => 'Harga bahan tidak sesuai kesepakatan',
                'item_count' => 2,
            ],
        ];

        foreach ($poData as $data) {
            $totalHarga = 0;
            $details = [];

            // Pick random bahan pangan for this PO
            $selectedBahan = $bahanPangans->random(min($data['item_count'], $bahanPangans->count()));

            foreach ($selectedBahan as $bahan) {
                $hargaSatuan = $bahan->harga_jual ?? rand(5000, 50000);
                $jumlah = rand(1, 20);
                $totalItem = $hargaSatuan * $jumlah;
                $totalHarga += $totalItem;

                $details[] = [
                    'bahan_pangan_id' => $bahan->id,
                    'harga_satuan' => $hargaSatuan,
                    'jumlah' => $jumlah,
                    'jumlah_input' => null,
                    'jumlah_bayar' => null,
                    'total_harga' => $totalItem,
                ];
            }

            $po = Po::create([
                'tanggal' => $data['tanggal'],
                'total_harga' => $totalHarga,
                'status' => $data['status'],
                'alasan_batal' => $data['alasan_batal'],
            ]);

            foreach ($details as $detail) {
                $po->poDetail()->create($detail);
            }
        }

        $this->command->info('Berhasil seed 5 PO dengan berbagai status workflow.');
    }
}
