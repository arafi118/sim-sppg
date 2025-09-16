<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiKegiatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class DokumentasiKegiatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DokumentasiKegiatan::latest();
            return DataTables::of($data)
                ->addColumn('gambar', function ($row) {
                    return $row->gambar
                        ? '<img src="' . asset('storage/' . $row->gambar) . '" style="width:50px; height:50px; object-fit:cover; border-radius:8px;" class="img-thumbnail"/>'
                        : '-';
                })
                ->addColumn('gambar_raw', function ($row) {
                    return $row->gambar ? asset('storage/' . $row->gambar) : '';
                })
                ->rawColumns(['gambar'])
                ->make(true);
        }
        $title = 'Dokumentasi Kegiatan SPPG';

        return view('app.dokumentasi-kegiatan.index', compact('title'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'judul',
            'deskripsi',
            'gambar',
        ]);

        $rules = [
            'judul'     => 'required',
            'deskripsi' => 'required',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('dokumentasi', 'public');
        }

        DokumentasiKegiatan::create([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar'    => $data['gambar'],
        ]);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil ditambahkan'
        ]);
    }

    public function show(DokumentasiKegiatan $dokumentasi_kegiatan)
    {
        //
    }

    public function edit(DokumentasiKegiatan $dokumentasi_kegiatan)
    {
        //
    }

    public function update(Request $request, DokumentasiKegiatan $dokumentasi_kegiatan)
    {
        $data = $request->only([
            'judul',
            'deskripsi',
        ]);

        $rules = [
            'judul'     => 'required',
            'deskripsi' => 'required',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('gambar')) {
            if ($dokumentasi_kegiatan->gambar && Storage::disk('public')->exists($dokumentasi_kegiatan->gambar)) {
                Storage::disk('public')->delete($dokumentasi_kegiatan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('dokumentasi', 'public');
        }



        $dokumentasi_kegiatan->update($data);

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil diperbarui'
        ]);
    }

    public function destroy(DokumentasiKegiatan $dokumentasi_kegiatan)
    {
        if ($dokumentasi_kegiatan->gambar && Storage::disk('public')->exists($dokumentasi_kegiatan->gambar)) {
            Storage::disk('public')->delete($dokumentasi_kegiatan->gambar);
        }

        $dokumentasi_kegiatan->delete();

        return response()->json([
            'success'   => true,
            'msg'       => 'Data berhasil dihapus'
        ]);
    }
}
