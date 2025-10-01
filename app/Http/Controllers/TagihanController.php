<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Rancangan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $invoice = Invoice::with('tagihan')->get();

            return datatables()->of($invoice)
                ->addIndexColumn()
                ->addColumn('jumlah', function ($row) {
                    $jumlah = 0;
                    foreach ($row->tagihan as $tagihan) {
                        $jumlah += $tagihan->total;
                    }

                    return "Rp. " . number_format($jumlah);
                })
                ->make(true);
        }

        $title = 'Daftar Tagihan';
        return view('app.tagihan.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Buat Tagihan';
        return view('app.tagihan.create', compact('title'));
    }

    public function tanggal($tanggal)
    {
        $rancangan = Rancangan::with(['rancanganMenu.menu.resep.bahanPangan'])
            ->where('tanggal', $tanggal)
            ->where('approved', 1)
            ->orderBy('tanggal', 'ASC')
            ->get();

        if ($rancangan->isEmpty()) {
            return response()->json(['error' => 'Data tidak ditemukan.']);
        }

        $dataBahanPangan = [];
        foreach ($rancangan as $r) {
            $jumlah = $r->jumlah;
            foreach ($r->rancanganMenu as $rm) {
                $menu = $rm->menu;
                if ($menu) {
                    foreach ($menu->resep as $resep) {
                        $bahanPangan = $resep->bahanPangan;
                        if ($bahanPangan) {
                            if (isset($dataBahanPangan[$bahanPangan->id])) {
                                $dataBahanPangan[$bahanPangan->id]['jumlah'] += ($resep->gramasi * $jumlah);
                            } else {
                                $dataBahanPangan[$bahanPangan->id] = [
                                    'id'     => $bahanPangan->id,
                                    'nama'   => $bahanPangan->nama,
                                    'satuan' => $bahanPangan->satuan,
                                    'harga'  => $bahanPangan->harga_jual,
                                    'jumlah' => ($resep->gramasi * $jumlah),
                                ];
                            }
                        }
                    }
                }
            }
        }
        uasort($dataBahanPangan, fn($a, $b) => strcmp($a['nama'], $b['nama']));

        $view = view('app.tagihan.daftar-tagihan', compact('dataBahanPangan'))->render();
        return response()->json([
            'success' => true,
            'view'    => $view
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'tanggal',
            'bahan_pangan',
            'grand_total',
        ]);

        $validate = Validator::make($data, [
            'tanggal'      => 'required',
            'bahan_pangan' => 'required|array',
            'grand_total'  => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->first()]);
        }

        $cekInvoice = Invoice::where('tanggal', $data['tanggal'])->first();
        if ($cekInvoice) {
            return response()->json(['msg' => 'Tagihan untuk tanggal ' . $data['tanggal'] . ' sudah ada.']);
        }

        $jumlahInvoice = Invoice::where('tanggal', 'LIKE', date('Y-m', strtotime($data['tanggal'])) . '%')->count();
        $invoice = Invoice::create([
            'no_invoice' => 'INV-' . date('ym') . '-' . str_pad($jumlahInvoice + 1, 3, '0', STR_PAD_LEFT),
            'tanggal' => $data['tanggal'],
            'status' => 'UNPAID'
        ]);

        $tagihan = [];
        foreach ($data['bahan_pangan'] as $bahan_pangan) {
            $detailBahan = json_decode($bahan_pangan, true);

            $tagihan[] = [
                'bahan_pangan_id' => $detailBahan['id'],
                'invoice_id' => $invoice->id,
                'harga' => $detailBahan['harga'],
                'kebutuhan' => $detailBahan['jumlah'],
                'total' => $detailBahan['harga'] * $detailBahan['jumlah'],
            ];
        }

        Tagihan::insert($tagihan);
        return response()->json([
            'success' => true,
            'msg' => 'Tagihan berhasil disimpan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tagihan $tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        //
    }
}
