<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $tamu = Auth::guard('tamu')->user();

        $transaksi = Transaksi::with(['kamar.tipeKamar'])
            ->where('id_tamu', $tamu->id_tamu)
            ->latest()
            ->paginate(10);

        $stats = [
            'total_transaksi' => Transaksi::where('id_tamu', $tamu->id_tamu)->count(),
            'transaksi_lunas' => Transaksi::where('id_tamu', $tamu->id_tamu)->where('is_paid', 'paid')->count(),
            'transaksi_pending' => Transaksi::where('id_tamu', $tamu->id_tamu)->where('is_paid', 'pending')->count(),
            'total_spent' => Transaksi::where('id_tamu', $tamu->id_tamu)->where('is_paid', 'paid')->sum('sub_total'),
        ];

        return view('frontend.dashboard.index', compact('tamu', 'transaksi', 'stats'));
    }

    public function showTransaksi($id)
    {
        $tamu = Auth::guard('tamu')->user();

        $transaksi = Transaksi::with(['kamar.tipeKamar', 'tamu'])
            ->where('id_tamu', $tamu->id_tamu)
            ->where('id_transaksi', $id)
            ->firstOrFail();

        return view('frontend.dashboard.transaksi-detail', compact('transaksi'));
    }

    public function profile()
    {
        $tamu = Auth::guard('tamu')->user();
        return view('frontend.dashboard.profile', compact('tamu'));
    }

    public function updateProfile(Request $request)
    {
        $tamu = Auth::guard('tamu')->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanda_pengenal' => 'required|date',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'tanda_pengenal' => $request->tanda_pengenal,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $tamu->update($data);

        return redirect()->route('tamu.profile')->with('success', 'Profile berhasil diupdate.');
    }
}
