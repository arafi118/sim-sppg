<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Models\Presensi;
use App\Models\Profil;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('level')->latest();
            return DataTables::of($data)->addColumn('foto', function ($row) {
                $src = $row->foto && file_exists(storage_path('app/public/' . $row->foto)) ? asset('storage/' . $row->foto) : asset('/assets/img/landing-page/default.png');
                return '<img src="' . $src . '" class="rounded-circle me-2" width="35" height="35"/>';
            })->addColumn('foto_raw', function ($row) {
                return $row->foto && file_exists(storage_path('app/public/' . $row->foto)) ? asset('storage/' . $row->foto) : asset('/assets/img/landing-page/default.png');
            })->rawColumns(['foto'])->make(true);
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
        $rules = [
            'level_id'      => 'required',
            'nama'          => 'required',
            'nik'           => 'required',
            'tanggal_lahir' => 'required',
            'tanggal_masuk' => 'required',
            'gaji'          => 'required',
            'alamat'        => 'required',
            'telpon'        => 'nullable',
            'jenis_kelamin' => 'required',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'username'      => 'required',
            'password'      => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $gaji = (int) str_replace(['.', ','], '', $request->gaji);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto', 'public');
        }

        $karyawan = User::create([
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
            'foto'          => $data['foto'] ?? null,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Karyawan berhasil ditambahkan!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $karyawan)
    {
        $profil = Profil::first();
        $karyawan = $karyawan->load('level');

        $writer = new PngWriter();
        $qrCode = new QrCode(
            data: $karyawan->nik,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 1920,
            margin: 16,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $logo = new Logo(
            path: public_path('assets/img/Logo.png'),
            resizeToWidth: 480,
            punchoutBackground: true
        );

        $result = $writer->write($qrCode, $logo);
        $dataUri = $result->getDataUri();

        $title = "Detail Karyawan " . $karyawan->nama;
        return view('app.karyawan.detail', compact('title', 'karyawan', 'dataUri', 'profil'));
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
        $rules = [
            'level_id'      => 'required',
            'nama'          => 'required',
            'nik'           => 'required',
            'tanggal_lahir' => 'required',
            'tanggal_masuk' => 'required',
            'gaji'          => 'required',
            'alamat'        => 'required',
            'telpon'        => 'nullable',
            'jenis_kelamin' => 'required',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'username'      => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $gaji = (int) str_replace(['.', ','], '', $request->gaji);

        $updateData = [
            'level_id'      => $request->level_id,
            'nik'           => $request->nik,
            'nama'          => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_masuk' => $request->tanggal_masuk,
            'gaji'          => $gaji,
            'alamat'        => $request->alamat,
            'telpon'        => $request->telpon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_sidik_jari' => $karyawan->id_sidik_jari ?? 0,
            'status'        => 'aktif',
            'username'      => $request->username,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $updateData['foto'] = $request->file('foto')->store('foto', 'public');
        }

        $karyawan->update($updateData);

        return response()->json([
            'success' => true,
            'msg'     => 'Karyawan berhasil diupdate!',
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
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Karyawan berhasil dihapus.'
        ], Response::HTTP_OK);
    }
}
