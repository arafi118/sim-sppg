<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\BahanPangan;
use App\Models\Po;

class TransaksiController extends Controller
{
public function index()
{
    $title = 'Transaksi';
    $bahanpangan = BahanPangan::all(); // daftar barang

    return view('app.transaksi.index', compact('title', 'bahanpangan'));
}

public function store(Request $request)
{
    $request->validate([
        'bahan_pangan_id' => 'required|exists:bahan_pangans,id',
        'jumlah' => 'required|numeric|min:1',
    ]);

    // Ambil bahan
    $bahan = BahanPangan::findOrFail($request->bahan_pangan_id);

    // Hitung total harga
    $totalHarga = $bahan->harga_jual * $request->jumlah;

    // Buat PO baru
    $po = Po::create([
        'user_id'      => auth()->id(),
        'tanggal'      => $request->tanggal_transaksi ?? now(),
        'total_harga'  => $totalHarga,
        'status_bayar' => 'UNPAID',
    ]);

    // Buat transaksi sekaligus lunas sebagian (opsional)
    Transaksi::create([
        'user_id'           => auth()->id(),
        'mitra_id'          => $bahan->mitra_id ?? null, // jika ada
        'po_id'             => $po->id,
        'tanggal_transaksi' => $request->tanggal_transaksi ?? now(),
        'jumlah'            => 0, // bisa langsung bayar atau cicilan
        'keterangan'        => 'Cicilan awal',
    ]);

    return redirect()->route('transaksi.index')->with('success', 'PO dan transaksi berhasil dibuat!');
}



}
