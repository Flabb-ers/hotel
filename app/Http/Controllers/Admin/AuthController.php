<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $petugas = Petugas::where('username', $request->username)->first();

        if ($petugas && Hash::check($request->password, $petugas->password)) {
            Auth::guard('petugas')->login($petugas);

            // Debug: Log successful login
            Log::info('Admin login successful', [
                'username' => $request->username,
                'user_id' => $petugas->id_petugas,
                'guard_check' => Auth::guard('petugas')->check()
            ]);

            return redirect()->route('admin.dashboard');
        }

        // Debug: Log failed login
        Log::warning('Admin login failed', [
            'username' => $request->username,
            'user_found' => $petugas ? 'yes' : 'no',
            'password_check' => $petugas ? Hash::check($request->password, $petugas->password) : 'no_user'
        ]);

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::guard('petugas')->logout();
        return redirect()->route('admin.login');
    }
}
