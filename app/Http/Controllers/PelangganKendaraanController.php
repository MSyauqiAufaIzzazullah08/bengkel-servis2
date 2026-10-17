<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelangganKendaraanController extends Controller
{
    private function pelangganLogin()
    {
        return DB::table('pelanggan')
            ->where('email', session('user_email'))
            ->first();
    }

    public function index()
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $kendaraans = DB::table('kendaraan')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderByDesc('id_kendaraan')
            ->get();

        return view('pelanggan.kendaraan.index', compact(
            'pelanggan',
            'kendaraans'
        ));
    }

    public function create()
    {
        if (!$this->pelangganLogin()) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        return view('pelanggan.kendaraan.create');
    }

    public function store(Request $request)
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $data = $request->validate([
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'nopol' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $nopol = strtoupper(trim($data['nopol']));

        $sudahAda = DB::table('kendaraan')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('nopol', $nopol)
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'nopol' => 'Nomor polisi ini sudah terdaftar pada akunmu.',
                ]);
        }

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('kendaraan', 'public');
        }

        DB::table('kendaraan')->insert([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'merk' => $data['merk'],
            'model' => $data['model'],
            'tahun' => $data['tahun'],
            'nopol' => $nopol,
            'foto' => $foto,
        ]);

        return redirect('/kendaraan-pelanggan')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $kendaraan = DB::table('kendaraan')
            ->where('id_kendaraan', $id)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->first();

        if (!$kendaraan) {
            abort(404, 'Kendaraan tidak ditemukan.');
        }

        $sedangDipesan = DB::table('bookingservis')
            ->where('id_kendaraan', $id)
            ->exists();

        if ($sedangDipesan) {
            return back()->with(
                'error',
                'Kendaraan tidak bisa dihapus karena sudah memiliki riwayat booking.'
            );
        }

        DB::table('kendaraan')
            ->where('id_kendaraan', $id)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->delete();

        if ($kendaraan->foto) {
            Storage::disk('public')->delete($kendaraan->foto);
        }

        return redirect('/kendaraan-pelanggan')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
