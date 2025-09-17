<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                    return redirect('/app/dashboard')->with('success', 'Selamat datang ' . Auth::user()->nama);
                }
            }
        }

        return redirect('/auth')->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        Auth::logout();

        session()->flush();
        session()->regenerate();

        return redirect('/auth')->with('success', 'Anda telah berhasil keluar');
    }
}
