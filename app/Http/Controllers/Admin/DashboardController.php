<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_kamar' => Kamar::count(),
            'kamar_kosong' => Kamar::where('status', 'tersedia')->count(),
            'kamar_terisi' => Kamar::where('status', 'terisi')->count(),
            'total_tamu' => Tamu::count(),
        ];

        $recentGuests = Tamu::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentGuests'));
    }
}
