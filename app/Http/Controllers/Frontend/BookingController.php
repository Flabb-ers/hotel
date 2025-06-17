<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Tamu;
use App\Models\Kamar;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function show($id)
    {
        $kamar = Kamar::with('tipeKamar')->findOrFail($id);

        if ($kamar->status !== 'tersedia') {
            return redirect()->route('home')->with('error', 'Kamar tidak tersedia');
        }

        return view('frontend.booking', compact('kamar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:tb_kamar,id_kamar',
            'nama_tamu' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
        ]);

        try {
            DB::beginTransaction();

            // Create or find tamu
            $tamu = Tamu::firstOrCreate(
                ['email' => $request->email],
                [
                    'nama_lengkap' => $request->nama_tamu,
                    'no_telp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'password' => bcrypt('password123'), // Default password
                    'tanda_pengenal' => now()->toDateString(),
                ]
            );

            // Calculate total days and amount
            $checkin = \Carbon\Carbon::parse($request->checkin_date);
            $checkout = \Carbon\Carbon::parse($request->checkout_date);
            $totalHari = $checkin->diffInDays($checkout);

            $kamar = Kamar::with('tipeKamar')->findOrFail($request->kamar_id);
            $totalBayar = $totalHari * $kamar->harga;

            // Create transaksi
            $transaksi = Transaksi::create([
                'id_tamu' => $tamu->id_tamu,
                'id_kamar' => $kamar->id_kamar,
                'sub_total' => $totalBayar,
                'tgl_transaksi' => now()->toDateString(),
                'tgl_checkin' => $request->checkin_date,
                'tgl_checkout' => $request->checkout_date,
                'is_paid' => 'pending',
            ]);

            // Auto login tamu setelah booking
            Auth::guard('tamu')->login($tamu);

            DB::commit();

            // Redirect to payment
            return redirect()->route('payment.create', $transaksi->id_transaksi);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
