<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\TipeKamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kamar = Kamar::with('tipeKamar')->paginate(10);
        return view('admin.kamar.index', compact('kamar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipeKamar = TipeKamar::all();
        return view('admin.kamar.create', compact('tipeKamar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tipe_kamar' => 'required|exists:tb_tipe_kamar,id_tipe',
            'nomer_kamar' => 'required|unique:tb_kamar,nomer_kamar',
            'jumlah_bed' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'fasilitas' => 'nullable|string',
            'thumbnail_kamar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:tersedia,terisi,maintenance'
        ]);

        $data = $request->all();

        if ($request->hasFile('thumbnail_kamar')) {
            $file = $request->file('thumbnail_kamar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kamar'), $filename);
            $data['thumbnail_kamar'] = $filename;
        }

        Kamar::create($data);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kamar = Kamar::with('tipeKamar')->findOrFail($id);
        return view('admin.kamar.show', compact('kamar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kamar = Kamar::findOrFail($id);
        $tipeKamar = TipeKamar::all();
        return view('admin.kamar.edit', compact('kamar', 'tipeKamar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kamar = Kamar::findOrFail($id);

        $request->validate([
            'id_tipe_kamar' => 'required|exists:tb_tipe_kamar,id_tipe',
            'nomer_kamar' => 'required|unique:tb_kamar,nomer_kamar,' . $id . ',id_kamar',
            'jumlah_bed' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'fasilitas' => 'nullable|string',
            'thumbnail_kamar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:tersedia,terisi,maintenance'
        ]);

        $data = $request->all();

        if ($request->hasFile('thumbnail_kamar')) {
            // Delete old image
            if ($kamar->thumbnail_kamar && file_exists(public_path('uploads/kamar/' . $kamar->thumbnail_kamar))) {
                unlink(public_path('uploads/kamar/' . $kamar->thumbnail_kamar));
            }

            $file = $request->file('thumbnail_kamar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kamar'), $filename);
            $data['thumbnail_kamar'] = $filename;
        }

        $kamar->update($data);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kamar = Kamar::findOrFail($id);

        // Delete image if exists
        if ($kamar->thumbnail_kamar && file_exists(public_path('uploads/kamar/' . $kamar->thumbnail_kamar))) {
            unlink(public_path('uploads/kamar/' . $kamar->thumbnail_kamar));
        }

        $kamar->delete();

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
