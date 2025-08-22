<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('level')->get();

            return DataTables::of($data)->make(true);
        }

        return view('app.karyawan.index', ['title' => 'Karyawan']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title  = 'Tambah Karyawan';
        $levels = Level::all();

        return view('app.karyawan.create', compact('title', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            "level_id",
            "nama",
            "nik",
            "tanggal_lahir",
            "tanggal_masuk",
            "gaji",
            "alamat",
            "telpon",
            "jenis_kelamin",
            "username",
            "password"
        ]);

        $rules = [
            'level_id'           => 'required',
            'nama'               => 'required',
            'nik'                => 'required',
            'tanggal_lahir'      => 'required',
            'tanggal_masuk'      => 'required',
            'gaji'               => 'required',
            'alamat'             => 'required',
            'telpon'             => 'nullable',
            'jenis_kelamin'      => 'required',
            'username'           => 'required',
            'password'           => 'required',
        ];

        $gaji = floatval(str_replace(',', '', str_replace('.00', '', $request->gaji)));

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $karyawan = User::create([
            'level_id'          => $request->level_id,
            'nik'               => $request->nik,
            'nama'              => $request->nama,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'tanggal_masuk'     => $request->tanggal_masuk,
            'gaji'              => $gaji,
            'alamat'            => $request->alamat,
            'telpon'            => $request->telpon,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'id_sidik_jari'     => 0,
            'status'            => 'aktif',
            'username'          => $request->username,
            'password'          => Hash::make($request->password),
        ]);
        return response()->json([
            'success'   => true,
            'msg'       => 'Karyawan berhasil ditambahkan!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $karyawan)
    {
        $title = 'Edit Karyawan';
        $levels = Level::all();

        return view('app.karyawan.edit', compact('title', 'karyawan', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $karyawan)
    {
        $data = $request->only([
            "level_id",
            "nama",
            "nik",
            "tanggal_lahir",
            "tanggal_masuk",
            "gaji",
            "alamat",
            "telpon",
            "jenis_kelamin",
            "username",
            "password"
        ]);

        $rules = [
            'level_id'           => 'required',
            'nama'               => 'required',
            'nik'                => 'required',
            'tanggal_lahir'      => 'required',
            'tanggal_masuk'      => 'required',
            'gaji'               => 'required',
            'alamat'             => 'required',
            'telpon'             => 'nullable',
            'jenis_kelamin'      => 'required',
            'username'           => 'required',
        ];

        $gaji = floatval(str_replace(',', '', str_replace('.00', '', $request->gaji)));

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $karyawan->update([
            'level_id'      => $request->level_id,
            'nik'           => $request->nik,
            'nama'          => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_masuk' => $request->tanggal_masuk,
            'gaji'          => $gaji,
            'alamat'        => $request->alamat,
            'telpon'        => $request->telpon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_sidik_jari' => 0,
            'status'        => 'aktif',
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
        ]);
        return response()->json([
            'success'   => true,
            'msg'       => 'Karyawan berhasil di Update!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(User $karyawan)
    {
        if (Presensi::where('user_id', $karyawan->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak bisa dihapus karena sudah dipakai di data presensi.'
            ], 400);
        }

        $karyawan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Karyawan berhasil dihapus.'
        ]);
    }
}
