<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Inventaris;
use App\Models\JenisTransaksi;
use App\Models\Rekening;
use App\Utils\Inventaris as UtilsInventaris;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Transaksi';
        $jenisTransaksi = JenisTransaksi::all();
        $rekening = Rekening::orderBy('kode_akun', 'asc')->get();

        return view('app.transaksi.index', compact('title', 'jenisTransaksi', 'rekening'));
    }

    public function daftarInventaris()
    {
        $tanggal = request()->get('tanggal');
        $jenis = request()->get('jenis');
        $kategori = request()->get('kategori');

        $inventaris = Inventaris::where([
            ['jenis', $jenis],
            ['kategori', intval($kategori)],
            ['tanggal_beli', '<=', $tanggal],
        ])->where(function ($query) {
            $query->where('status', 'baik')->orwhere('status', 'busak');
        })->get();

        $inventarisArray = $inventaris->toArray();
        foreach ($inventaris as $index => $inv) {
            $nilaiBuku = UtilsInventaris::nilaiBuku($tanggal, $inv);

            $inventarisArray[$index]['nilai_buku'] = $nilaiBuku;
        }

        return response()->json($inventarisArray);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'transaksi',
            "tanggal",
            "sumber_dana",
            "disimpan_ke",
            "jurnal_umum",
            "beli_inventaris",
            "hapus_inventaris"
        ]);

        $request->validate([
            'transaksi' => 'required',
            'tanggal' => 'required',
            'sumber_dana' => 'required',
            'disimpan_ke' => 'required',
            'jurnal_umum' => 'required|array',
            'beli_inventaris' => 'required|array',
            'hapus_inventaris' => 'required|array',
        ]);

        $message = "Transaksi berhasil disimpan.";
        $form = $data[$data['transaksi']];
        if ($data['transaksi'] == 'jurnal_umum') {
            Transaksi::create([
                'user_id' => auth()->user()->id,
                'tanggal_transaksi' => $data['tanggal'],
                'rekening_debit' => $data['disimpan_ke'],
                'rekening_kredit' => $data['sumber_dana'],
                'keterangan' => $form['keterangan'],
                'jumlah' => floatval(str_replace(',', '', $form['nominal'])),
            ]);
        }

        if ($data['transaksi'] == 'beli_inventaris') {
            $jenis_inventaris = $form['jenis_inventaris'];
            $kategori_inventaris = $form['kategori_inventaris'];
            $nama_barang = $form['nama_barang'];
            $harga_satuan = floatval(str_replace(',', '', $form['harga_satuan']));
            $umur_ekonomis = $form['umur_ekonomis'];
            $jumlah_unit = $form['jumlah_unit'];
            $harga_perolehan = $harga_satuan * $jumlah_unit;

            Inventaris::create([
                'nama' => $nama_barang,
                'tanggal_beli' => $data['tanggal'],
                'tanggal_validasi' => $data['tanggal'],
                'jumlah' => $jumlah_unit,
                'harga_satuan' => $harga_satuan,
                'umur_ekonomis' => $umur_ekonomis,
                'jenis' => $jenis_inventaris,
                'kategori' => $kategori_inventaris,
                'status' => 'baik',
            ]);

            $keterangan = "Beli " . $jumlah_unit . " unit " . $nama_barang;
            Transaksi::create([
                'user_id' => auth()->user()->id,
                'tanggal_transaksi' => $data['tanggal'],
                'rekening_debit' => $data['sumber_dana'],
                'rekening_kredit' => $data['disimpan_ke'],
                'keterangan' => $keterangan,
                'jumlah' => $harga_perolehan,
            ]);
        }

        if ($data['transaksi'] == 'hapus_inventaris') {
            $nama_barang = explode('#', $form['daftar_barang']);
            $id_inv = $nama_barang[0];
            $jumlah_barang = $nama_barang[1];
            $status = $form['alasan'];
            $jumlah_unit = $form['jumlah_unit_inventaris'];
            $nilai_buku = floatval(str_replace(',', '', $form['nilai_buku']));
            $harga_jual = floatval(str_replace(',', '', $form['harga_jual']));

            $inv = Inventaris::where('id', $id_inv)->first();

            $tanggal_beli = $inv->tanggal_beli;
            $harga_satuan = $inv->harga_satuan;
            $umur_ekonomis = $inv->umur_ekonomis;
            $sisa_unit = $jumlah_barang - $jumlah_unit;
            $barang = $inv->nama;
            $jenis = $inv->jenis;
            $kategori = $inv->kategori;

            $trx_penghapusan = [
                'user_id' => auth()->user()->id,
                'mitra_id' => '0',
                'po_id' => '0',
                'tanggal_transaksi' => $data['tanggal'],
                'rekening_debit' => $data['disimpan_ke'],
                'rekening_kredit' => $data['sumber_dana'],
                'keterangan' => 'Penghapusan ' . $jumlah_unit . ' unit ' . $barang . ' (' . $id_inv . ')' . ' karena ' . $status,
                'jumlah' => $nilai_buku,
                'urutan' => '0',
            ];

            $update_inventaris = [
                'jumlah' => $sisa_unit,
                'tanggal_validasi' => $data['tanggal']
            ];

            $update_status_inventaris = [
                'status' => $status,
                'tanggal_validasi' => $data['tanggal']
            ];

            $insert_inventaris = [
                'nama' => $barang,
                'tanggal_beli' => $tanggal_beli,
                'jumlah' => $jumlah_unit,
                'harga_satuan' => $harga_satuan,
                'umur_ekonomis' => $umur_ekonomis,
                'jenis' => $jenis,
                'kategori' => $kategori,
                'status' => $status,
                'tanggal_validasi' => $data['tanggal'],
            ];

            $trx_penjualan = [
                'user_id' => auth()->user()->id,
                'mitra_id' => '0',
                'po_id' => '0',
                'tgl_transaksi' => $data['tanggal'],
                'rekening_debit' => '1',
                'rekening_kredit' => '55',
                'keterangan_transaksi' => 'Penjualan ' . $jumlah_unit . ' unit ' . $barang . ' (' . $id_inv . ')',
                'jumlah' => $harga_jual,
                'urutan' => '0',
            ];

            if ($status != 'rusak') {
                $transaksi = Transaksi::create($trx_penghapusan);
            }

            if ($jumlah_unit < $jumlah_barang) {
                Inventaris::where('id', $id_inv)->update($update_inventaris);
                if ($status != 'revaluasi') {
                    Inventaris::create($insert_inventaris);
                }
            } else {
                Inventaris::where('id', $id_inv)->update($update_status_inventaris);
            }

            if ($status == 'revaluasi') {
                $harga_jual = floatval(str_replace(',', '', str_replace('.00', '', $request->harga_jual)));

                $insert_inventaris_baru = [
                    'nama' => $barang,
                    'tanggal_beli' => $data['tanggal'],
                    'tanggal_validasi' => $data['tanggal'],
                    'jumlah' => $jumlah_unit,
                    'harga_satuan' => $harga_jual / $jumlah_unit,
                    'umur_ekonomis' => $umur_ekonomis,
                    'jenis' => $jenis,
                    'kategori' => $kategori,
                    'status' => 'baik',
                ];

                if ($harga_jual != $nilai_buku) {
                    $jumlah = $harga_jual - $nilai_buku;
                    $trx_revaluasi = [
                        'user_id' => auth()->user()->id,
                        'mitra_id' => '0',
                        'po_id' => '0',
                        'tgl_transaksi' => $data['tanggal'],
                        'rekening_debit' => '1',
                        'rekening_kredit' => '57',
                        'keterangan_transaksi' => 'Revaluasi ' . $jumlah_unit . ' unit ' . $barang . ' (' . $id_inv . ')',
                        'jumlah' => $jumlah,
                        'urutan' => '0',
                    ];

                    Transaksi::create($trx_revaluasi);
                }

                Inventaris::create($insert_inventaris_baru);
            }

            $message = 'Penghapusan ' . $jumlah_unit . ' unit ' . $barang . ' karena ' . $status;
            if ($status == 'dijual') {
                $transaksi = Transaksi::create($trx_penjualan);
                $message = 'Penjualan ' . $jumlah_unit . ' unit ' . $barang;
            }
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
