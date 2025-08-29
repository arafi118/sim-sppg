<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\PoDetail;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Transaksi';
        $bahanpangan = BahanPangan::all();
        return view('app.transaksi.index', compact('title','bahanpangan'));
    }


}
