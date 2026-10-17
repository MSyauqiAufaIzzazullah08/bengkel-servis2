<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Membatasi kendaraan berdasarkan booking di cabang admin.
     */
    private function queryKendaraan()
    {
        $query = Kendaraan::query();

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

    public function index()
    {
        $kendaraan = $this->queryKendaraan()
            ->with('pelanggan')
            ->get();

        return view('admin.kendaraan.index', compact('kendaraan'));
    }

    public function create()
    {
        $pelanggan = $this->pelangganUntukCabang()
            ->orderBy('nama')
            ->get();

        return view('admin.kendaraan.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:Pelanggan,id_pelanggan',
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'tahun' => 'required|integer|min:1900|max:2100',
            'nopol' => 'required|string|max:20|unique:Kendaraan,nopol',
        ]);

        $this->pelangganUntukCabang()
            ->findOrFail($request->id_pelanggan);

        Kendaraan::create($request->only([
            'id_pelanggan',
            'merk',
            'model',
            'tahun',
            'nopol',
        ]));

        return redirect('/kendaraan')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kendaraan = $this->queryKendaraan()->findOrFail($id);

        $pelanggan = $this->pelangganUntukCabang()
            ->orderBy('nama')
            ->get();

        return view('admin.kendaraan.edit', compact(
            'kendaraan',
            'pelanggan'
        ));
    }

    public function update(Request $request, $id)
    {
        $kendaraan = $this->queryKendaraan()->findOrFail($id);

        $request->validate([
            'id_pelanggan' => 'required|exists:Pelanggan,id_pelanggan',
            'merk' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'tahun' => 'required|integer|min:1900|max:2100',
            'nopol' => 'required|string|max:20|unique:Kendaraan,nopol,'
                . $id . ',id_kendaraan',
        ]);

        $this->pelangganUntukCabang()
            ->findOrFail($request->id_pelanggan);

        $kendaraan->update($request->only([
            'id_pelanggan',
            'merk',
            'model',
            'tahun',
            'nopol',
        ]));

        return redirect('/kendaraan')
            ->with('success', 'Data kendaraan berhasil diubah.');
    }

    public function destroy($id)
    {
        $kendaraan = $this->queryKendaraan()->findOrFail($id);

        if (
            $kendaraan->bookingServis()->exists() ||
            $kendaraan->homeServis()->exists()
        ) {
            return redirect('/kendaraan')
                ->with(
                    'error',
                    'Kendaraan tidak dapat dihapus karena masih memiliki riwayat servis.'
                );
        }

        $kendaraan->delete();

        return redirect('/kendaraan')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }

    /**
     * Daftar pelanggan yang boleh dipilih oleh admin cabang.
     */
    private function pelangganUntukCabang()
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
}
