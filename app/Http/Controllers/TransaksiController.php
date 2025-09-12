<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Inventaris;
use App\Models\JenisTransaksi;
use App\Models\Rekening;

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
        $tanggal_transaksi = request()->get('tanggal_transaksi');
        $jenis = request()->get('jenis');
        $kategori = request()->get('kategori');

        $inventaris = Inventaris::where([
            ['jenis', $jenis],
            ['kategori', $kategori],
            ['tgl_beli', '<=', $tanggal_transaksi],
        ])->where(function ($query) {
            $query->where('status', 'Baik')->orwhere('status', 'Rusak');
        })->get();
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'transaksi',
            "tanggal",
            "sumber_dana",
            "disimpan_ke",
            "jurnal_umum",
            "beli_inventaris"
        ]);

        $request->validate([
            'transaksi' => 'required',
            'tanggal' => 'required',
            'sumber_dana' => 'required',
            'disimpan_ke' => 'required',
            'jurnal_umum' => 'required|array',
            'beli_inventaris' => 'required|array',
        ]);

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

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan.']);
    }
}
