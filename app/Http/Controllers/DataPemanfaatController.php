<?php

namespace App\Http\Controllers;

use App\Models\DataPemanfaat;
use App\Models\KelompokPemanfaat;
use App\Models\NamaPemanfaat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class DataPemanfaatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DataPemanfaat::with('kelompokPemanfaat')->get();

            return DataTables::of($data)
                ->addColumn('kelompok_pemanfaat', function ($row) {
                    return $row->kelompokPemanfaat->nama ?? '-';
                })
                ->make(true);
        }

        return view('app.data-pemanfaat.index', ['title' => 'Data Pemanfaat']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Pemanfaat';
        $kelompokPemanfaat = KelompokPemanfaat::all();

        return view('app.data-pemanfaat.create', compact('title', 'kelompokPemanfaat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'kelompok_pemanfaat_id',
            'nama_lembaga',
            'nis',
            'nama_pj',
            'alamat',
            'jabatan_pj',
            'telpon_pj',
            'email_pj',
            'jarak_tempuh',
            'waktu_tempuh_roda_2',
            'waktu_tempuh_roda_4',
        ]);
        $rules = [
            'kelompok_pemanfaat_id'     => 'required',
            'nama_lembaga'              => 'required',
            'nis'                       => 'required',
            'nama_pj'                   => 'required',
            'alamat'                    => 'required',
            'jabatan_pj'                => 'required',
            'telpon_pj'                 => 'required',
            'email_pj'                  => 'required',
            'jarak_tempuh'              => 'required',
            'waktu_tempuh_roda_2'       => 'required',
            'waktu_tempuh_roda_4'       => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dataPemanfaat = DataPemanfaat::create([
            'kelompok_pemanfaat_id' => $request->kelompok_pemanfaat_id,
            'nama_lembaga'          => $request->nama_lembaga,
            'nama_pj'               => $request->nama_pj,
            'nis'                   => $request->nis,
            'alamat'                => $request->alamat,
            'jabatan_pj'            => $request->jabatan_pj,
            'telpon_pj'             => $request->telpon_pj,
            'email_pj'              => $request->email_pj,
            'jarak_tempuh'          => $request->jarak_tempuh,
            'jumlah_pemanfaat'      => 0,
            'waktu_tempuh_roda_2'   => $request->waktu_tempuh_roda_2,
            'waktu_tempuh_roda_4'   => $request->waktu_tempuh_roda_4,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil disimpan',
            'data'      => $dataPemanfaat,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(DataPemanfaat $dataPemanfaat)
    {
        $Pemanfaat = DataPemanfaat::with(
            'kelompokPemanfaat',
            'namaPemanfaat'
        )->findOrFail($dataPemanfaat->id);

        $title = 'Detail Data Pemanfaat';

        return view('app.data-pemanfaat.detail', compact('title', 'Pemanfaat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataPemanfaat $dataPemanfaat)
    {
        $title = 'Edit Data Pemanfaat';
        $kelompokPemanfaat = KelompokPemanfaat::all();
        $dataPemanfaat = DataPemanfaat::findOrFail($dataPemanfaat->id);

        return view('app.data-pemanfaat.edit', compact('title', 'kelompokPemanfaat', 'dataPemanfaat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataPemanfaat $dataPemanfaat)
    {
        $data = $request->only([
            'kelompok_pemanfaat_id',
            'nama_lembaga',
            'nis',
            'nama_pj',
            'alamat',
            'jabatan_pj',
            'telpon_pj',
            'email_pj',
            'jarak_tempuh',
            'waktu_tempuh_roda_2',
            'waktu_tempuh_roda_4',
        ]);
        $rules = [
            'kelompok_pemanfaat_id'     => 'required',
            'nama_lembaga'              => 'required',
            'nis'                       => 'required',
            'nama_pj'                   => 'required',
            'alamat'                    => 'required',
            'jabatan_pj'                => 'required',
            'telpon_pj'                 => 'required',
            'email_pj'                  => 'required',
            'jarak_tempuh'              => 'required',
            'waktu_tempuh_roda_2'       => 'required',
            'waktu_tempuh_roda_4'       => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dataPemanfaat->update([
            'kelompok_pemanfaat_id' => $request->kelompok_pemanfaat_id,
            'nama_lembaga'          => $request->nama_lembaga,
            'nis'                   => $request->nis,
            'nama_pj'               => $request->nama_pj,
            'alamat'                => $request->alamat,
            'jabatan_pj'            => $request->jabatan_pj,
            'telpon_pj'             => $request->telpon_pj,
            'email_pj'              => $request->email_pj,
            'jarak_tempuh'          => $request->jarak_tempuh,
            'waktu_tempuh_roda_2'   => $request->waktu_tempuh_roda_2,
            'waktu_tempuh_roda_4'   => $request->waktu_tempuh_roda_4,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui!',
            'data'      => $dataPemanfaat,
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataPemanfaat $dataPemanfaat)
    {
        if (NamaPemanfaat::where('data_pemanfaat_id', $dataPemanfaat->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak bisa dihapus karena sudah dipakai di Nama Pemanfaat.'
            ], 400);
        }

        $dataPemanfaat->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data Pemanfaat berhasil dihapus.'
        ]);
    }
}
