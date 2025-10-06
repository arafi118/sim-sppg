<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Profil;
use App\Models\Rancangan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

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
                ->addColumn('action', function ($row) {

                    $btn  = '<div class="d-inline-flex gap-1">';
                    $btn .= '<button type="button" class="btn btn-sm btn-primary btn-invoice" data-id="' . $row->id . '">Invoice</button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '">Hapus</button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
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

    public function no_invoice($tanggal)
    {
        $bulan = date('Y-m', strtotime($tanggal));
        $invoice = Invoice::where('tanggal_invoice', 'LIKE', $bulan . '%')->orderBy('id', 'DESC')->first();
        if ($invoice) {
            $nomor = explode('/', $invoice->no_invoice);
            $no_invoice = str_pad($nomor[0] + 1, 3, '0', STR_PAD_LEFT) . '/KOP.OJM/' . date('m.Y', strtotime($tanggal));
        } else {
            $no_invoice = '001/KOP.OJM/' . date('m.Y', strtotime($tanggal));
        }

        return response()->json(['no_invoice' => $no_invoice]);
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
            "tanggal_invoice",
            "nomor_invoice",
            "tanggal",
            'bahan_pangan',
            'grand_total',
        ]);

        $validate = Validator::make($data, [
            'nomor_invoice' => 'required',
            'tanggal_invoice' => 'required',
            'tanggal'      => 'required',
            'bahan_pangan' => 'required|array',
            'grand_total'  => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()->first()]);
        }

        $cekInvoice = Invoice::where('tanggal_tagihan', $data['tanggal'])->first();
        if ($cekInvoice) {
            return response()->json(['message' => 'Tagihan untuk tanggal ' . $data['tanggal'] . ' sudah ada.']);
        }

        $invoice = Invoice::create([
            'no_invoice' => $data['nomor_invoice'],
            'tanggal_invoice' => $data['tanggal_invoice'],
            'tanggal_tagihan' => $data['tanggal'],
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
            'message' => 'Tagihan berhasil disimpan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $generate_tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $generate_tagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $generate_tagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $generate_tagihan)
    {
        Invoice::where('id', $generate_tagihan->id)->delete();

        Tagihan::where('invoice_id', $generate_tagihan->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Tagihan berhasil dihapus'
        ]);
    }

    public function invoice(Invoice $invoice)
    {
        $profil = Profil::first();
        $invoice = $invoice->load('tagihan.bahanPangan');

        $view = view('app.tagihan.invoice', compact('profil', 'invoice'))->render();

        return PDF::loadHTML($view)
            ->setOptions([
                'header-line' => true,
                'margin-top'    => 30,
                'margin-bottom' => 15,
                'margin-left'   => 25,
                'margin-right'  => 20,
                'enable-local-file-access' => true,
            ])
            ->setPaper('A4', 'portrait')
            ->setOption('title', "Invoice " . $invoice->no_invoice)
            ->inline('RAB.pdf');
    }
}
