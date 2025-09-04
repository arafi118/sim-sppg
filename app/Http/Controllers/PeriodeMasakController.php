<?php

namespace App\Http\Controllers;

use App\Models\PeriodeMasak;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class PeriodeMasakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PeriodeMasak::get();

            return DataTables::of($data)->make(true);
        }

        return view('app.periode-masak.index', ['title' => 'Periode Masak']);
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
        $data = $request->only([
            'tanggal_awal',
            'tanggal_akhir',
        ]);
        $rules = [
            'tanggal_awal'  => 'required',
            'tanggal_akhir' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $periode_ke = PeriodeMasak::max('periode_ke') + 1;

        dd($periode_ke, $request->tanggal_akhir, $request->tanggal_awal);
        $periodeMasak = PeriodeMasak::create([
            'periode_ke'    => $periode_ke,
            'tanggal_awal'  => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil disimpan!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodeMasak $periodeMasak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeMasak $periodeMasak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodeMasak $periodeMasak)
    {
        $data = $request->only([
            'tanggal_awal',
            'tanggal_akhir',
        ]);
        $rules = [
            'tanggal_awal'  => 'required',
            'tanggal_akhir' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $periodeMasak->update([
            'tanggal_awal'  => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeMasak $periodeMasak)
    {
        $periodeMasak->delete();

        return response()->json([
            'success'   => true,
            'message'   => 'Data berhasil dihapus!'
        ]);
    }
}
