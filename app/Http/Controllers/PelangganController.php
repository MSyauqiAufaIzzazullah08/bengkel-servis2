<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Query pelanggan sesuai cabang yang sedang login.
     */
    private function queryPelanggan()
    {
        $query = Pelanggan::query();

        if ((int) session('role_id') === 2) {
            $user = User::findOrFail(session('user_id'));

            $cabangId = $user->cabang_id
                ?? $user->adminCabang?->cabang_id;

            abort_if(
                !$cabangId,
                403,
                'Akun Admin Cabang belum terhubung dengan cabang.'
            );

            $query->whereHas('bookingServis', function ($booking) use ($cabangId) {
                $booking->where('id_cabang', $cabangId);
            });
        }

        return $query;
    }

    // Menampilkan pelanggan sesuai hak akses.
    public function index()
    {
        $pelanggan = $this->queryPelanggan()
            ->with([
                'kendaraan',
                'bookingServis',
                'orderSparePart',
                'homeServis',
                'rating',
            ])
            ->get();

        return view('pelanggan.index', compact('pelanggan'));
    }

    // Menampilkan form tambah pelanggan.
    public function create()
    {
        return view('pelanggan.create');
    }

    // Menyimpan pelanggan baru.
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'tanggal_daftar' => 'required|date',
        ]);

        Pelanggan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_daftar' => $request->tanggal_daftar,
        ]);

        return redirect('/pelanggan')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // Menampilkan form edit pelanggan.
    public function edit($id)
    {
        $pelanggan = $this->queryPelanggan()->findOrFail($id);

        return view('pelanggan.edit', compact('pelanggan'));
    }

    // Mengupdate pelanggan.
    public function update(Request $request, $id)
    {
        $pelanggan = $this->queryPelanggan()->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'tanggal_daftar' => 'required|date',
        ]);

        $pelanggan->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_daftar' => $request->tanggal_daftar,
        ]);

        return redirect('/pelanggan')
            ->with('success', 'Data pelanggan berhasil diubah.');
    }

    // Menghapus pelanggan.
    public function destroy($id)
    {
        $pelanggan = $this->queryPelanggan()->findOrFail($id);

        $pelanggan->delete();

        return redirect('/pelanggan')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
