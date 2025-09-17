<?php

namespace App\Http\Controllers;

use App\Models\DataPemanfaat;
use App\Models\NamaPemanfaat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class NamaPemanfaatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = NamaPemanfaat::with('dataPemanfaat')->get();

            return DataTables::of($data)
                ->addColumn('Nama_Pemanfaat', function ($row) {
                    return $row->dataPemanfaat->nama_lembaga ?? '-';
                })
                ->make(true);
        }

        return view('app.nama-pemanfaat.index', ['title' => 'Nama Pemanfaat']);
    }

    public function list(Request $request)
    {
        $search = $request->get('q');

        $query = DataPemanfaat::select('id', 'nama_lembaga');
        if ($search) {
            $query->where('nama_lembaga', 'like', "%{$search}%");
        }

        return response()->json(
            $query->get()->map(fn ($item) => [
                'id'            => $item->id,
                'nama_lembaga'  => $item->nama_lembaga,
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
            'data_pemanfaat_id',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'status'
        ]);

        $rules = [
            'data_pemanfaat_id' => 'required',
            'nama'              => 'required',
            'tempat_lahir'      => 'required',
            'tanggal_lahir'     => 'required',
            'status'            => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $namaPemanfaat = NamaPemanfaat::create([
            'data_pemanfaat_id' => $request->data_pemanfaat_id,
            'nama'              => $request->nama,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'status'            => $request->status,
        ]);

        $Pemanfaat = DataPemanfaat::find($request->data_pemanfaat_id);
        $data1 = $Pemanfaat->jumlah_pemanfaat + 1;

        $Pemanfaat->update([
            'jumlah_pemanfaat' => $data1
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'data berhasil disimpan!',
            'data' => $namaPemanfaat
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(NamaPemanfaat $namaPemanfaat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NamaPemanfaat $namaPemanfaat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NamaPemanfaat $namaPemanfaat)
    {
        $data = $request->only([
            'data_pemanfaat_id',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'status'
        ]);

        $rules = [
            'data_pemanfaat_id' => 'required',
            'nama'              => 'required',
            'tempat_lahir'      => 'required',
            'tanggal_lahir'     => 'required',
            'status'            => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $namaPemanfaat->update($data);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui!',
            'data'      => $namaPemanfaat
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NamaPemanfaat $namaPemanfaat)
    {
        $Pemanfaat = DataPemanfaat::find($namaPemanfaat->data_pemanfaat_id);
        $data1 = $Pemanfaat->jumlah_pemanfaat - 1;
        $Pemanfaat->update([
            'jumlah_pemanfaat' => $data1
        ]);

        $namaPemanfaat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nama Pemanfaat berhasil dihapus.'
        ], Response::HTTP_OK);
    }
}
