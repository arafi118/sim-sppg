<?php

namespace App\Http\Controllers;

use App\Models\DataPemanfaat;
use App\Models\KelompokPemanfaat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class KelompokPemanfaatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KelompokPemanfaat::get();

            return DataTables::of($data)->make(true);
        }

        return view('app.kelompok-Pemanfaat.index', ['title' => 'Kelompok Pemanfaat']);
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
            'nama'
        ]);

        $rules = [
            'nama' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $pemanfaat = KelompokPemanfaat::create([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil disimpan!',
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(KelompokPemanfaat $kelompokPemanfaat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KelompokPemanfaat $kelompokPemanfaat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KelompokPemanfaat $kelompokPemanfaat)
    {
        $data = $request->only([
            'nama'
        ]);

        $rules = [
            'nama' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $kelompokPemanfaat->update([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui!',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KelompokPemanfaat $kelompokPemanfaat)
    {
        if (DataPemanfaat::where('kelompok_pemanfaat_id', $kelompokPemanfaat->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak bisa dihapus karena sudah dipakai di data pemanfaat.'
            ], 400);
        }

        $kelompokPemanfaat->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'kelompok Pangan berhasil dihapus.'
        ]);
    }
}
