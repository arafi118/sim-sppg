<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Profil;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title  = 'Profil';
        $profil = Profil::with('mitra')->first();
        $mitra  = Mitra::all();
        $user   = User::with('level')->find(auth()->user()->id);

        return view('app.profil.index', compact('title', 'user', 'mitra', 'profil'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profil $profil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profil $profil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $formType = $request->input('form_type');

        if ($formType === 'user') {
            $request->validate([
                'username' => 'required|string|max:50',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $user = User::findOrFail($id);

            $user->username = $request->username;
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'msg' => 'User berhasil diperbarui!'
            ]);
        }

        if ($formType === 'profil') {
            $request->validate([
                'mitra_id'          => 'required',
                'id_yayasan'        => 'required',
                'nama'              => 'required',
                'nama_mitra'        => 'required',
                'alamat'            => 'required',
                'telpon'            => 'required',
                'penanggung_jawab'  => 'required',
            ]);

            $profil = Profil::findOrFail($id);
            $profil->mitra_id           = $request->mitra_id;
            $profil->id_yayasan         = $request->id_yayasan;
            $profil->nama               = $request->nama;
            $profil->nama_mitra         = $request->nama_mitra;
            $profil->alamat             = $request->alamat;
            $profil->telpon             = $request->telpon;
            $profil->penanggung_jawab   = $request->penanggung_jawab;
            $profil->save();

            return response()->json([
                'success' => true,
                'msg' => 'Profil berhasil diperbarui!'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Form tidak dikenali'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profil $profil)
    {
        //
    }
}
