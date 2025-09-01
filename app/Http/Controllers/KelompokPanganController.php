<?php

namespace App\Http\Controllers;

use App\Models\KelompokPangan;
use App\Models\BahanPangan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class KelompokPanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KelompokPangan::get();

            return DataTables::of($data)->make(true);
        }

        return view('app.kelompok-Pangan.index', ['title' => 'Kelompok Pangan']);
    }

    public function getNextCode(Request $request)
    {
        $initials = strtoupper($request->get('initials', ''));

        $last = KelompokPangan::selectRaw("CAST(SUBSTRING_INDEX(kode, '-', -1) AS UNSIGNED) as num")
            ->orderByDesc('num')
            ->first();

        $nextNumber = $last ? str_pad($last->num + 1, 3, '0', STR_PAD_LEFT) : '001';

        return response()->json([
            'kode' => $initials . '-' . $nextNumber
        ]);
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
            'nama',
            'kode',
        ]);

        $rules = [
            'nama' => 'required',
            'kode' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $MKPangan = KelompokPangan::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil disimpan!',
        ]);
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
        $data = $request->only([
            'nama',
            'kode',
        ]);

        $rules = [
            'nama' => 'required',
            'kode' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $kelompokPangan->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelompokPangan $kelompokPangan)
    {
        if (BahanPangan::where('kelompok_pangan_id', $kelompokPangan->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak bisa dihapus karena sudah dipakai di bahan pangan.'
            ], 400);
        }

        $kelompokPangan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'kelompok Pangan berhasil dihapus.'
        ]);
    }
}
