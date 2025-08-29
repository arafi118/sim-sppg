<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\PoDetail;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Transaksi';
        $poDetails = PoDetail::with('bahanPangan')->get();
        return view('app.transaksi.index', compact('title','poDetails'));
    }

public function store(Request $request)
{
    $request->validate([
        'tgl_transaksi'   => 'required|date',
        'po_detail_id'    => 'required|exists:po_details,id',
        'jumlah'          => 'required|numeric|min:1',
    ]);

    // Ambil detail PO untuk dapatkan harga_satuan
$poDetail = \App\Models\PoDetail::with('bahanPangan')
    ->latest()
    ->get()
    ->unique('bahan_pangan_id')
    ->values();

    $hargaSatuan = $poDetail->harga_satuan;
    $nominal     = $request->jumlah * $hargaSatuan;

    Transaksi::create([
        'tgl_transaksi'   => $request->tgl_transaksi,
        'po_detail_id'    => $request->po_detail_id,
        'jumlah'          => $request->jumlah,
        'harga_satuan'    => $hargaSatuan,
        'nominal'         => $nominal,
    ]);

    return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
}


}
