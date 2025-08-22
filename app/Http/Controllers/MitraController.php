<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use App\Models\KelompokPangan;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $mitra = Mitra::with('bahanPangan')->get();

            return datatables()->of($mitra)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/app/mitra/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus ms-2" data-id="' . $row->id . '">Hapus</button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Daftar Mitra';
        return view('app.mitra.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelompokPangan = KelompokPangan::with([
            'bahanPangan' => function ($query) {
                $query->orderBy('nama', 'asc');
            }
        ])->orderBy('nama', 'asc')->get();

        $title = 'Tambah Mitra';
        return view('app.mitra.create', compact('title', 'kelompokPangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            "nama_bahan",
            "harga_beli",
            "nama_mitra",
            "telpon",
            "alamat",
        ]);

        $validate = Validator::make($data, [
            'nama_bahan' => 'required',
            'harga_beli' => 'required',
            'nama_mitra' => 'required',
            'telpon' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        $mitra = Mitra::create([
            'bahan_pangan_id' => $data['nama_bahan'],
            'nama' => $data['nama_mitra'],
            'alamat' => $data['alamat'],
            'telpon' => $data['telpon'],
            'harga_beli' => str_replace(',', '', $data['harga_beli']),
        ]);

        $hargaBahanTertinggi = Mitra::where('bahan_pangan_id', $data['nama_bahan'])->orderBy('harga_beli', 'desc')->first();
        BahanPangan::where('id', $data['nama_bahan'])->update([
            'harga_jual' => $hargaBahanTertinggi->harga_beli,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil disimpan!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mitra $mitra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mitra $mitra)
    {
        $kelompokPangan = KelompokPangan::with([
            'bahanPangan' => function ($query) {
                $query->orderBy('nama', 'asc');
            }
        ])->orderBy('nama', 'asc')->get();

        $title = 'Edit Mitra';
        return view('app.mitra.edit', compact('title', 'mitra', 'kelompokPangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mitra $mitra)
    {
        $data = $request->only([
            "nama_bahan",
            "harga_beli",
            "nama_mitra",
            "telpon",
            "alamat",
        ]);

        $validate = Validator::make($data, [
            'nama_bahan' => 'required',
            'harga_beli' => 'required',
            'nama_mitra' => 'required',
            'telpon' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()]);
        }

        Mitra::where('id', $mitra->id)->update([
            'bahan_pangan_id' => $data['nama_bahan'],
            'nama' => $data['nama_mitra'],
            'alamat' => $data['alamat'],
            'telpon' => $data['telpon'],
            'harga_beli' => str_replace(',', '', $data['harga_beli']),
        ]);

        $hargaBahanTertinggi = Mitra::where('bahan_pangan_id', $data['nama_bahan'])->orderBy('harga_beli', 'desc')->first();
        BahanPangan::where('id', $data['nama_bahan'])->update([
            'harga_jual' => $hargaBahanTertinggi->harga_beli,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil diperbarui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $mitra)
    {
        Mitra::destroy($mitra->id);

        $hargaBahanTertinggi = Mitra::where('bahan_pangan_id', $mitra->bahan_pangan_id)->orderBy('harga_beli', 'desc')->first();
        BahanPangan::where('id', $mitra->bahan_pangan_id)->update([
            'harga_jual' => $hargaBahanTertinggi ? $hargaBahanTertinggi->harga_beli : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => $mitra->nama . ' berhasil dihapus!',
        ]);
    }
}
