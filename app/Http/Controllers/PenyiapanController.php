<?php

namespace App\Http\Controllers;

use App\Models\Penyiapan;
use App\Models\Tahapan;
use App\models\Pelaksana;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class PenyiapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Penyiapan::with(
                'tahapan',
                'tahapan.pelaksana.karyawan'
            );
            return DataTables::of($data)->make(true);
        }

        return view('app.penyiapan-mbg.index', ['title' => 'Mekanisme Penyiapan MBG']);
    }

    public function detail($id)
    {
        $tahapan = Tahapan::with(
            'penyiapan',
            'pelaksana.karyawan'
        )->findOrFail($id);
        return view('app.penyiapan-mbg.detail', [
            'title'   => 'Detail Tahapan Penyiapan',
            'tahapan' => $tahapan
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
            'tanggal',
        ]);
        $rules = [
            'tanggal' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $penyiapan = Penyiapan::create([
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil disimpan!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyiapan $penyiapan_mbg)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyiapan $penyiapan_mbg)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyiapan $penyiapan_mbg)
    {
        $data = $request->only([
            'tanggal',
        ]);

        $rules = [
            'tanggal' => 'required',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $penyiapan_mbg->update([
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui!',
        ]);
    }

    //Mekanisme Pelaksanaan
    public function CreateMekanisme($id)
    {
        $tahapan = Penyiapan::where('id', $id)->first();

        $karyawan = User::where('level_id', 6)->get();

        $title = 'Create Tahapan Mekanisme Penyiapan MBG';
        return view('app.penyiapan-mbg.mekanisme', compact('tahapan', 'title', 'karyawan'));
    }

    public function Storemekanisme(Request $request)
    {
        $data = $request->only([
            'penyiapan_id',
            'tahapan',
            'waktu_mulai',
            'waktu_selesai',
        ]);

        $rules = [
            'penyiapan_id'  => 'required|integer',
            'tahapan'       => 'required|string',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validate->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // simpan tahapan
        $tahapan = Tahapan::create($data);
        if ($request->has('pelaksana')) {
            foreach ($request->pelaksana as $p) {
                if (!empty($p['user_id'])) {
                    Pelaksana::create([
                        'tahapan_id' => $tahapan->id,
                        'user_id'    => $p['user_id'],
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil disimpan!',
        ]);
    }

    public function EditMekanisme($id)
    {
        $tahapan = Tahapan::with(
            'penyiapan',
            'pelaksana',
            'pelaksana.karyawan'
        )->findOrFail($id);

        $karyawan = User::where('level_id', 6)->get();

        $title = 'Edit Tahapan Mekanisme Penyiapan MBG';
        return view('app.penyiapan-mbg.Edit', compact('tahapan', 'title', 'karyawan'));
    }

    public function Updatemekanisme(Request $request, $id)
    {
        $data = $request->only([
            'penyiapan_id',
            'tahapan',
            'waktu_mulai',
            'waktu_selesai',
            'pelaksana',
        ]);

        $rules = [
            'penyiapan_id'  => 'required|integer',
            'tahapan'       => 'required|string',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
            'pelaksana'     => 'nullable|array',
            'pelaksana.*.user_id' => 'nullable|integer|exists:users,id',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validate->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $tahapan = Tahapan::findOrFail($id);

        $tahapan->update([
            'penyiapan_id'  => $request->penyiapan_id,
            'tahapan'       => $request->tahapan,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        if ($request->has('pelaksana') && count($request->pelaksana) > 0) {
            $newUserIds = [];
            foreach ($request->pelaksana as $p) {
                if (!empty($p['user_id'])) {
                    $newUserIds[] = $p['user_id'];
                    Pelaksana::updateOrCreate(
                        [
                            'tahapan_id' => $tahapan->id,
                            'user_id'    => $p['user_id'],
                        ],
                        [
                            'tahapan_id' => $tahapan->id,
                            'user_id'    => $p['user_id'],
                        ]
                    );
                }
            }

            Pelaksana::where('tahapan_id', $tahapan->id)
                ->whereNotIn('user_id', $newUserIds)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil diperbarui!',
            'data'    => $tahapan->id,
        ]);
    }


    public function DestroyMekanisme($id)
    {
        $tahapan = Tahapan::findOrFail($id);

        Pelaksana::where('tahapan_id', $tahapan->id)->delete();

        $tahapan->delete();

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil dihapus!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyiapan $penyiapan_mbg)
    {
        if (Tahapan::where('penyiapan_id', $penyiapan_mbg->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak bisa dihapus karena sudah dipakai untuk Tanggal Pelaksanaan.'
            ], 400);
        }

        $penyiapan_mbg->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Penyiapan berhasil dihapus.'
        ]);
    }
}
