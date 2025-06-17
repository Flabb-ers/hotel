<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Tamu;
use App\Models\Kamar;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::with(['tamu', 'kamar.tipeKamar'])
            ->latest()
            ->paginate(10);
        return view('admin.transaksi.index', compact('transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tamu = Tamu::all();
        $kamar = Kamar::with('tipeKamar')->where('status', 'tersedia')->get();
        return view('admin.transaksi.create', compact('tamu', 'kamar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tamu' => 'required|exists:tb_tamu,id_tamu',
            'id_kamar' => 'required|exists:tb_kamar,id_kamar',
            'tgl_checkin' => 'required|date|after_or_equal:today',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
        ]);

        $kamar = Kamar::findOrFail($request->id_kamar);
        $checkin = \Carbon\Carbon::parse($request->tgl_checkin);
        $checkout = \Carbon\Carbon::parse($request->tgl_checkout);
        $totalHari = $checkin->diffInDays($checkout);
        $subTotal = $totalHari * $kamar->harga;

        Transaksi::create([
            'id_tamu' => $request->id_tamu,
            'id_kamar' => $request->id_kamar,
            'sub_total' => $subTotal,
            'tgl_transaksi' => now()->toDateString(),
            'tgl_checkin' => $request->tgl_checkin,
            'tgl_checkout' => $request->tgl_checkout,
            'is_paid' => 'pending',
        ]);

        // Update status kamar
        $kamar->update(['status' => 'terisi']);

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['tamu', 'kamar.tipeKamar'])->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaksi = Transaksi::with(['tamu', 'kamar'])->findOrFail($id);
        $tamu = Tamu::all();
        $kamar = Kamar::with('tipeKamar')->get();
        return view('admin.transaksi.edit', compact('transaksi', 'tamu', 'kamar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'is_paid' => 'required|in:pending,paid,failed,cancelled',
        ]);

        $oldStatus = $transaksi->is_paid;
        $transaksi->update([
            'is_paid' => $request->is_paid,
        ]);

        // Update status kamar berdasarkan status pembayaran
        if ($request->is_paid === 'paid' && $oldStatus !== 'paid') {
            $transaksi->kamar->update(['status' => 'terisi']);
        } elseif ($request->is_paid !== 'paid' && $oldStatus === 'paid') {
            $transaksi->kamar->update(['status' => 'tersedia']);
        }

        return redirect()->route('admin.transaksi.index')->with('success', 'Status transaksi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Kembalikan status kamar ke tersedia
        $transaksi->kamar->update(['status' => 'tersedia']);

        $transaksi->delete();

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
