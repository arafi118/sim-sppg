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
        ]);

        $rules = [
            'nama' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $MKPangan = KelompokPangan::create([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Kelompok pangan berhasil ditambahkan!',
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
        ]);

        $rules = [
            'nama' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $kelompokPangan->update([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Kelompok pangan berhasil di Update!',
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
