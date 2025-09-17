<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Upload Presensi";
        return view('app.presensi.create', compact('title'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            "nik",
            "waktu",
            "absensi",
        ]);

        $tanggal = date('Y-m-d');
        $user = User::where('nik', $data['nik'])->first();

        if (!$user) {
            throw new \Exception("Karyawan tidak ditemukan");
        }

        if ($data['absensi'] == 'masuk') {
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'waktu' => $data['waktu'],
                'jam_masuk' => $data['waktu'],
                'jam_pulang' => null,
                'status' => 'masuk',
            ]);
        } else {
            $presensi = Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->first();

            if (!$presensi) {
                throw new \Exception("Belum absens masuk");
            }

            $presensi->jam_pulang = $data['waktu'];
            $presensi->save();
        }

        return response()->json([
            'success'  => true,
            'msg' => $user->nama . " berhasil absen " . $data['absensi'] . " pada " . $data['waktu'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Presensi $presensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presensi $presensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presensi $presensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presensi $presensi)
    {
        //
    }
}
