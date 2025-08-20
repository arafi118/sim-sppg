<?php

namespace App\Http\Controllers;

use App\Models\KelompokPangan;
use App\Models\Menu;
use App\Models\Resep;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $menu = Menu::with('resep.bahanPangan')->orderBy('created_at', 'desc')->get();
            return datatables()->of($menu)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/app/menu/' . $row->id . '/edit" class="btn btn-sm btn-primary">Edit</a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus ms-2" data-id="' . $row->id . '">Hapus</button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Master Menu';
        return view('app.menu.index', compact('title'));
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

        $title = 'Tambah Menu';
        return view('app.menu.create', compact('title', 'kelompokPangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'bahan' => 'required|array'
        ]);

        $menu = Menu::create([
            'nama' => $request->nama_menu,
        ]);

        $bahan = [];
        foreach ($request->bahan as $item) {
            $bahan[] = [
                'menu_id' => $menu->id,
                'bahan_pangan_id' => json_decode($item['nama_bahan'], true)['id'],
                'gramasi' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (count($bahan) > 0) {
            Resep::insert($bahan);
        }

        return redirect('/app/menu')->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menu = $menu->load('resep.bahanPangan');
        $kelompokPangan = KelompokPangan::with([
            'bahanPangan' => function ($query) {
                $query->orderBy('nama', 'asc');
            }
        ])->orderBy('nama', 'asc')->get();

        $title = 'Edit Menu ' . $menu->nama;
        return view('app.menu.edit', compact('title', 'menu', 'kelompokPangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required',
            'bahan' => 'required|array'
        ]);

        Menu::where('id', $menu->id)->update([
            'nama' => $request->nama_menu,
        ]);

        $bahan = [];
        foreach ($request->bahan as $item) {
            $bahan[] = [
                'menu_id' => $menu->id,
                'bahan_pangan_id' => json_decode($item['nama_bahan'], true)['id'],
                'gramasi' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        if (count($bahan) > 0) {
            Resep::where('menu_id', $menu->id)->delete();
            Resep::insert($bahan);
        }

        return redirect('/app/menu')->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        Resep::where('menu_id', $menu->id)->delete();
        Menu::destroy($menu->id);

        return response()->json([
            'status' => true,
            'message' => 'Menu berhasil dihapus'
        ]);
    }
}
