<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabang = Cabang::with([
            'bookingServis',
            'adminCabang.user',
            'operasionalCabang',
            'users'
        ])->get();

        return view('cabang.index', compact('cabang'));
    }

    public function create()
    {
        return view('cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'jam_operasional' => 'required|string|max:100',
            'kontak' => 'required|string|max:20',
        ]);

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_operasional' => $request->jam_operasional,
            'kontak' => $request->kontak,
        ]);

        return redirect('/cabang')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $cabang = Cabang::findOrFail($id);

        return view('cabang.edit', compact('cabang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'jam_operasional' => 'required|string|max:100',
            'kontak' => 'required|string|max:20',
        ]);

        $cabang = Cabang::findOrFail($id);

        $cabang->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_operasional' => $request->jam_operasional,
            'kontak' => $request->kontak,
        ]);

        return redirect('/cabang')
            ->with('success', 'Data cabang berhasil diubah.');
    }

    public function destroy($id)
    {
        $cabang = Cabang::findOrFail($id);

        $cabang->delete();

        return redirect('/cabang')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}