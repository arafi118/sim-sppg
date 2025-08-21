<?php

namespace App\Http\Controllers;

use App\Models\KelompokPangan;
use Illuminate\Http\Request;

class KelompokPanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompokPangan = KelompokPangan::all();

        $title = 'Kelompok Pangan';
        return view('app.kelompok-pangan.index', compact('kelompokPangan', 'title'));
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
    public function show(KelompokPangan $kelompokPangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KelompokPangan $kelompokPangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KelompokPangan $kelompokPangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelompokPangan $kelompokPangan)
    {
        //
    }
}
