<?php

namespace App\Http\Controllers;

use App\Models\Po;
use Illuminate\Http\Request;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $daftarPO = Po::latest()->get();
            return datatables()->of($daftarPO)
                ->addIndexColumn()
                ->editColumn('total_harga', function ($row) {
                    return "Rp. " . number_format($row->total_harga);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" id="' . $row->id . '" class="btn btn-sm btn-primary btn-detail">Detail</button>';

                    return $btn;
                })
                ->make(true);
        }

        $title = 'Daftar Po';
        return view('app.po.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Po $po)
    {
        $po = $po->load('poDetail.bahanPangan');

        $view = view('app.po.detail', compact('po'))->render();
        return response()->json(['view' => $view, 'tanggal' => $po->tanggal]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Po $po)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Po $po)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Po $po)
    {
        //
    }
}
