<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rancangan;
use App\Models\RancangMenu;
use App\Models\Menu;
use App\Models\Resep;
use App\Models\BahanPangan;

class RabController extends Controller
{
    public function index()
    {
        $title = 'Rab';
        return view('app.rab.index', compact('title'));

    }

    public function generate(Request $request)
    {
        $jenis = $request->jenis; // Harian / Periode
        $tanggal = $request->tanggal;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        // Query rancangan sesuai filter
        $query = Rancangan::query();

        if ($jenis == 'Harian' && $tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($jenis == 'Periode' && $tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
        }

        $rancangan = $query->with(['rancangMenu.menu.resep.bahanPangan'])->get();

        // kirim hasil ke view
        return view('rab.index', compact('rancangan', 'jenis', 'tanggal', 'tanggal_awal', 'tanggal_akhir'));
    }
}
