<?php

namespace App\Http\Controllers;

use App\Models\DetailServis;
use App\Models\BookingServis;
use App\Models\LayananServis;
// SparePart: model masih ada di database, tidak dimuat di controller karena field tidak digunakan di UI
use Illuminate\Http\Request;

class DetailServisController extends Controller
{
    public function index()
    {
        $detail = DetailServis::with([
            'bookingServis.pelanggan',
            'bookingServis.kendaraan',
            'layananServis',
            'sparePart'
        ])->get();

        return view('detailservis.index', compact('detail'));
    }

    public function create()
    {
        $booking = BookingServis::with([
            'pelanggan',
            'kendaraan'
        ])->get();

        $layanan = LayananServis::all();

        // id_sparepart: kolom database tetap ada untuk data historis, tidak ditampilkan di form
        return view('detailservis.create', compact(
            'booking',
            'layanan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
            'layanan_id' => 'required',
            // id_sparepart: tidak divalidasi/diisi dari form, kolom database tetap ada
            'harga' => 'required|numeric',
            'catatan' => 'nullable',
        ]);

        DetailServis::create([
            'booking_id' => $request->booking_id,
            'layanan_id' => $request->layanan_id,
            // id_sparepart tidak diisi — kolom tetap ada di database untuk data historis
            'harga' => $request->harga,
            'catatan' => $request->catatan,
        ]);

        return redirect('/detailservis')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $detail = DetailServis::findOrFail($id);

        $booking = BookingServis::with([
            'pelanggan',
            'kendaraan'
        ])->get();

        $layanan = LayananServis::all();

        // id_sparepart: kolom database tetap ada untuk data historis, tidak ditampilkan di form
        return view('detailservis.edit', compact(
            'detail',
            'booking',
            'layanan'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'booking_id' => 'required',
            'layanan_id' => 'required',
            // id_sparepart: tidak divalidasi/diisi dari form, kolom database tetap ada
            'harga' => 'required|numeric',
            'catatan' => 'nullable',
        ]);

        $detail = DetailServis::findOrFail($id);

        $detail->update([
            'booking_id' => $request->booking_id,
            'layanan_id' => $request->layanan_id,
            // id_sparepart tidak disentuh — tetap menyimpan nilai historis
            'harga' => $request->harga,
            'catatan' => $request->catatan,
        ]);

        return redirect('/detailservis')->with('success', 'Data berhasil diubah.');
    }

    public function destroy($id)
    {
        $detail = DetailServis::findOrFail($id);

        $detail->delete();

        return redirect('/detailservis')->with('success', 'Data berhasil dihapus.');
    }
}