<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Tamu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\GuestsExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_kamar' => Kamar::count(),
            'kamar_kosong' => Kamar::where('status', 'tersedia')->count(),
            'kamar_terisi' => Kamar::where('status', 'terisi')->count(),
            'total_tamu' => Tamu::count(),
        ];

        // Memulai query untuk model Tamu
        $query = Tamu::query();

        // Filter berdasarkan pencarian (nama, email, atau no telp)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('no_telp', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan tanggal (check-in)
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $recentGuests = $query->latest()->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            return view('admin._dashboard_guest_table', compact('recentGuests'))->render();
        }

        return view('admin.dashboard', compact('stats', 'recentGuests'));
    }

    public function export(Request $request)
    {
        $search = $request->query('search');
        $tanggal = $request->query('tanggal');
        $fileName = 'daftar-tamu-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new GuestsExport($search, $tanggal), $fileName);
    }
}
