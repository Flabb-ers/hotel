<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $tamu = Tamu::where('email', $request->email)->first();

        if ($tamu && Hash::check($request->password, $tamu->password)) {
            Auth::guard('tamu')->login($tamu);
            return redirect()->intended(route('tamu.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function showRegister()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_tamu,email',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanda_pengenal' => 'required|date',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $tamu = Tamu::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'tanda_pengenal' => $request->tanda_pengenal,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('tamu')->login($tamu);

        return redirect()->route('tamu.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function logout()
    {
        Auth::guard('tamu')->logout();
        return redirect()->route('home');
    }
}
