<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tamu = Tamu::latest()->paginate(10);
        return view('admin.tamu.index', compact('tamu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tamu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_tamu,email',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanda_pengenal' => 'required|date',
            'password' => 'required|string|min:6',
        ]);

        Tamu::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'tanda_pengenal' => $request->tanda_pengenal,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.tamu.index')->with('success', 'Data tamu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tamu = Tamu::with('transaksi.kamar.tipeKamar')->findOrFail($id);
        return view('admin.tamu.show', compact('tamu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tamu = Tamu::findOrFail($id);
        return view('admin.tamu.edit', compact('tamu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tamu = Tamu::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_tamu,email,' . $id . ',id_tamu',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanda_pengenal' => 'required|date',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'tanda_pengenal' => $request->tanda_pengenal,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $tamu->update($data);

        return redirect()->route('admin.tamu.index')->with('success', 'Data tamu berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->delete();

        return redirect()->route('admin.tamu.index')->with('success', 'Data tamu berhasil dihapus.');
    }
}
