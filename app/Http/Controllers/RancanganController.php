<?php

namespace App\Http\Controllers;

use App\Models\Rancangan;
use Illuminate\Http\Request;

class RancanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Rancangan Menu';
        return view('app.rancang-menu.index', compact('title'));
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
    public function show(Rancangan $rancangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rancangan $rancangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rancangan $rancangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rancangan $rancangan)
    {
        //
    }
}
