<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $kamarTersedia = Kamar::with(['tipeKamar'])
            ->where('status', 'tersedia')
            ->get()
            ->groupBy('id_tipe_kamar');

        $tipeKamar = TipeKamar::all();

        return view('frontend.home', compact('kamarTersedia', 'tipeKamar'));
    }

    public function search(Request $request)
    {
        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');
        $tipe = $request->input('tipe');

        $query = Kamar::with(['tipeKamar'])
            ->where('status', 'tersedia');

        if ($tipe) {
            $query->where('id_tipe_kamar', $tipe);
        }

        $kamarTersedia = $query->get()->groupBy('id_tipe_kamar');
        $tipeKamar = TipeKamar::all();

        return view('frontend.home', compact('kamarTersedia', 'tipeKamar', 'checkin', 'checkout'));
    }
}
