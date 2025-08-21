<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use App\Models\KelompokPangan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;



class BahanPanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BahanPangan::with('kelompokPangan')->get();

            return DataTables::of($data)
                ->addColumn('kelompok_pangan_nama', function ($row) {
                    return $row->kelompokPangan->nama ?? '-';
                })
                ->make(true);
        }

        return view('app.Bahan-Pangan.index', ['title' => 'Bahan Pangan']);
    }

    public function list(Request $request)
    {
        $search = $request->get('q');

        $query = KelompokPangan::select('id', 'nama');
        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        return response()->json(
            $query->get()->map(fn ($item) => [
                'id' => $item->id,
                'nama' => $item->nama
            ])
        );
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
            'kelompok_pangan_id',
            'nama',
            'satuan',
            'harga_jual'
        ]);

        $data['harga_jual'] = floatval(str_replace(',', '', str_replace('.00', '', $data['harga_jual'])));

        $rules = [
            'kelompok_pangan_id' => 'required|exists:kelompok_pangans,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        BahanPangan::create($data);

        return response()->json([
            'success' => true,
            'msg' => 'Bahan Pangan berhasil disimpan.'
        ], Response::HTTP_CREATED);
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
        $data = $request->only([
            'kelompok_pangan_id',
            'nama',
            'satuan',
            'harga_jual'
        ]);

        $data['harga_jual'] = floatval(str_replace(',', '', str_replace('.00', '', $data['harga_jual'])));

        $rules = [
            'kelompok_pangan_id' => 'required|exists:kelompok_pangans,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bahanPangan->update($data);

        return response()->json([
            'success' => true,
            'msg' => 'Bahan Pangan berhasil diperbarui.',
            'data' => $bahanPangan
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanPangan $bahanPangan)
    {
        $bahanPangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bahan Pangan berhasil dihapus.'
        ], Response::HTTP_OK);
    }
}
