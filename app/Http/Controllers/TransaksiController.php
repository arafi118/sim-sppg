<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\BahanPangan;
use App\Models\JenisTransaksi;
use App\Models\Po;
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

    public function store(Request $request)
    {
        $data = $request->only([
            "tanggal",
            "sumber_dana",
            "disimpan_ke",
            "keterangan",
            "nominal",
        ]);

        $request->validate([
            'tanggal' => 'required',
            'sumber_dana' => 'required',
            'disimpan_ke' => 'required',
            'keterangan' => 'required',
            'nominal' => 'required',
        ]);

        Transaksi::create([
            'user_id' => auth()->user()->id,
            'tanggal_transaksi' => $data['tanggal'],
            'rekening_debit' => $data['disimpan_ke'],
            'rekening_kredit' => $data['sumber_dana'],
            'keterangan' => $data['keterangan'],
            'jumlah' => floatval(str_replace(',', '', $data['nominal'])),
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan.']);
    }
}
