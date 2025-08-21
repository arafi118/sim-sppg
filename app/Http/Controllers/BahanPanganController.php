<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use Illuminate\Http\Request;

class BahanPanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanPangan = BahanPangan::all();

        $title = 'Bahan Pangan';
        return view('app.bahan-pangan.index', compact('bahanPangan', 'title'));
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
    public function show(BahanPangan $bahanPangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BahanPangan $bahanPangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BahanPangan $bahanPangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanPangan $bahanPangan)
    {
        //
    }
}
