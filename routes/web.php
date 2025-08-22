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
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\RancanganController;

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
    return view('welcome');
});

Route::get('/auth', [AuthController::class, 'index']);
Route::post('/auth', [AuthController::class, 'auth']);

Route::group(['middleware' => ['auth'], 'prefix' => 'app'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('/menu', MenuController::class);

    Route::get('/rancang-menu/get-periode/{tanggal}', [RancanganController::class, 'getPeriode']);
    Route::resource('/rancang-menu', RancanganController::class);

    //admin
    Route::resource('/profile', ProfilController::class);

    Route::resource('/karyawan', UserController::class);

    Route::get('/bahan-pangan/list', [BahanPanganController::class, 'list']);
    Route::resource('/bahan-pangan', BahanPanganController::class);

    Route::resource('/kelompok-pangan', KelompokPanganController::class);

    Route::resource('/kelompok-pemanfaat', KelompokPemanfaatController::class);

    Route::get('/data-pemanfaat/list', [DataPemanfaatController::class, 'list']);
    Route::resource('/data-pemanfaat', DataPemanfaatController::class);

    Route::get('/nama-pemanfaat/list', [NamaPemanfaatController::class, 'list']);
    Route::resource('/nama-pemanfaat', NamaPemanfaatController::class);

    Route::resource('/periode-masak', PeriodeMasakController::class);

    //Pelaporan
    Route::get('/laporan', [PelaporanController::class, 'index']);
    Route::get('/pelaporan/preview', [PelaporanController::class, 'preview']);
    Route::get('/pelaporan/sub-laporan/{file}', [PelaporanController::class, 'subLaporan']);
    Route::get('/pelaporan/simpan-saldo/{tahun}/{bulan?}', [PelaporanController::class, 'simpanSaldo']);
});
