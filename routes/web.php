<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BahanPanganController;
use App\Http\Controllers\KelompokPanganController;
use App\Http\Controllers\KelompokPemanfaatController;
use App\Http\Controllers\DataPemanfaatController;
use App\Http\Controllers\NamaPemanfaatController;
use App\Http\Controllers\PeriodeMasakController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RabController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MitraController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RancanganController;
use App\Http\Controllers\PenyiapanController;
use App\Http\Controllers\PelaksanaController;
use App\Http\Controllers\DokumentasiKegiatanController;
use App\Http\Controllers\TahapanController;
use App\Models\PeriodeMasak;
use App\Models\Menu;
use App\Models\DokumentasiKegiatan;
use App\Models\User;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    Carbon::setLocale('id');

    $today     = Carbon::today();
    $yesterday = $today->copy()->subDay();
    $tomorrow  = $today->copy()->addDay();

    $periode = PeriodeMasak::with([
        'rancangan.rancanganMenu.menu.resep.bahanPangan'
    ])->get();

    $getMenuByDate = function ($periode, $date) {
        return $periode->flatMap(function ($p) use ($date) {
            return $p->rancangan
                ->where('tanggal', $date->toDateString())
                ->flatMap->rancanganMenu
                ->pluck('menu');
        });
    };

    $menus = [
        'yesterday' => $getMenuByDate($periode, $yesterday),
        'today'     => $getMenuByDate($periode, $today),
        'tomorrow'  => $getMenuByDate($periode, $tomorrow),
    ];

    $karyawan = User::with('level')->get();
    $levelImages = [
        'Admin'      => '/assets/img/landing-page/admin.png',
        'SuperAdmin' => '/assets/img/landing-page/superadmin.png',
        'Mitra'      => '/assets/img/landing-page/mitra.png',
        'Ahli Gizi'  => '/assets/img/landing-page/ahligizi.png',
        'Akuntan'    => '/assets/img/landing-page/akuntan.png',
        'Kepala'     => '/assets/img/landing-page/kepala.png',
        'Karyawan'   => '/assets/img/landing-page/karyawan.png',
    ];

    $dokumentasi = DokumentasiKegiatan::latest()->get();
    $dokumentasiChunks = $dokumentasi->chunk(4);

    return view('welcome', compact('menus', 'levelImages', 'periode', 'dokumentasiChunks', 'karyawan', 'yesterday', 'today', 'tomorrow'));
});

Route::get('test', fn () => phpinfo());


Route::get('/auth', [AuthController::class, 'index']);
Route::post('/auth', [AuthController::class, 'auth']);
Route::get('/link', function () {
    $target = __DIR__ . '/../storage/app/public';
    $shortcut = __DIR__ . '/../public/storage';

    try {
        symlink($target, $shortcut);
        return response()->json("Symlink created successfully.");
    } catch (\Exception $e) {
        return response()->json("Failed to create symlink: " . $e->getMessage());
    }
});

Route::group(['middleware' => ['auth'], 'prefix' => 'app'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('/menu', MenuController::class);

    Route::get('/rancang-menu/get-periode/{tanggal}', [RancanganController::class, 'getPeriode']);
    Route::get('/rancang-menu/approve', [RancanganController::class, 'approve']);
    Route::get('/rancang-menu/approve-list', [RancanganController::class, 'approveList']);
    Route::post('/rancang-menu/approve', [RancanganController::class, 'approved']);
    Route::resource('/rancang-menu', RancanganController::class);

    Route::resource('/mitra', MitraController::class);

    //admin
    Route::resource('/profile', ProfilController::class);

    Route::resource('/karyawan', UserController::class);

    Route::get('/bahan-pangan/list', [BahanPanganController::class, 'list']);
    Route::resource('/bahan-pangan', BahanPanganController::class);

    Route::get('/kelompok-pangan/next-code', [KelompokPanganController::class, 'getNextCode']);
    Route::resource('/kelompok-pangan', KelompokPanganController::class);

    Route::get('/kelompok-pemanfaat/next-code', [KelompokPemanfaatController::class, 'NextCode']);
    Route::resource('/kelompok-pemanfaat', KelompokPemanfaatController::class);

    Route::get('/data-pemanfaat/list', [DataPemanfaatController::class, 'list']);
    Route::resource('/data-pemanfaat', DataPemanfaatController::class);

    Route::get('/nama-pemanfaat/list', [NamaPemanfaatController::class, 'list']);
    Route::resource('/nama-pemanfaat', NamaPemanfaatController::class);

    Route::resource('/periode-masak', PeriodeMasakController::class);

    Route::resource('dokumentasi-kegiatan', DokumentasiKegiatanController::class);

    //Presensi
    Route::get('/presensi', [PresensiController::class, 'index']);
    Route::get('/scan-qr', [PresensiController::class, 'create']);
    Route::post('/scan-qr', [PresensiController::class, 'store']);
    Route::get('/hitung-pengajian', [PresensiController::class, 'hitung']);

    //Pelaporan
    Route::get('/laporan', [PelaporanController::class, 'index']);
    Route::get('/pelaporan/preview', [PelaporanController::class, 'preview']);
    Route::get('/pelaporan/sub_laporan/{file}', [PelaporanController::class, 'subLaporan']);
    Route::get('/pelaporan/simpan-saldo/{tahun}/{bulan?}', [PelaporanController::class, 'simpanSaldo']);

    //Rab
    Route::get('/app/rab', [RABController::class, 'index'])->name('rab.index');
    Route::get('/rab/approve', [RabController::class, 'approve']);
    Route::get('/rab/approve-list', [RabController::class, 'approveList']);
    Route::post('/rab/approve', [RabController::class, 'approved']);
    Route::get('/rab', [RabController::class, 'index']);
    Route::get('/rab/generate', [RabController::class, 'generate']);
    Route::get('/rab/po', [RabController::class, 'PO']);
    Route::post('/rab/simpanPO', [RabController::class, 'simpanPO']);
    Route::get('/rab/detailPO/{id}', [RabController::class, 'detailPO']);
    Route::get('/rab/daftar_po', [RabController::class, 'daftar_po']);
    Route::get('/rab/po/cetak/{id}', [RabController::class, 'cetakPO']);
    Route::put('/rab/update', [RabController::class, 'updatePO'])->name('rab.update');
    Route::get('/rab/po/cetak_detail/{id}', [RabController::class, 'cetak_detail']);
    Route::post('/rab/bayar', [RabController::class, 'bayar'])->name('rab.bayar');
    Route::post('/rab/bayar_po', [RabController::class, 'bayarPO']);

    //Mekanisme Pelaksanaan
    Route::get('/create-mekanisme/{id}', [PenyiapanController::class, 'CreateMekanisme']);
    Route::get('/edit-mekanisme/{id}', [PenyiapanController::class, 'Editmekanisme']);
    Route::post('/store-mekanisme', [PenyiapanController::class, 'Storemekanisme']);
    Route::put('/update-mekanisme/{id}', [PenyiapanController::class, 'Updatemekanisme']);
    Route::delete('/destroy-Mekanisme/{id}', [PenyiapanController::class, 'DestroyMekanisme'])->name('mekanisme.destroy');
    Route::resource('/penyiapan-mbg', PenyiapanController::class);

    //Jurnal Transaksi
    Route::get('/transaksi/daftar-inventaris', [TransaksiController::class, 'daftarInventaris']);
    Route::resource('/transaksi', TransaksiController::class);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
