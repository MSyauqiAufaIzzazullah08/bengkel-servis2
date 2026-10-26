<?php

namespace App\Http\Controllers;

use App\Models\KomisiTeknisi;
use App\Models\KonfigurasiKomisi;
use Illuminate\Http\Request;

class KomisiController extends Controller
{
    // ================================================================
    // KONFIGURASI KOMISI — hanya Super Admin (role:1)
    // ================================================================

    /**
     * Daftar semua konfigurasi komisi, terbaru dulu.
     */
    public function indexKonfigurasi()
    {
        $konfigurasi = KonfigurasiKomisi::orderByDesc('berlaku_mulai')
            ->orderByDesc('id')
            ->get();

        return view('komisi.konfigurasi.index', compact('konfigurasi'));
    }

    /**
     * Form tambah konfigurasi baru.
     */
    public function createKonfigurasi()
    {
        return view('komisi.konfigurasi.create');
    }

    /**
     * Simpan konfigurasi baru.
     */
    public function storeKonfigurasi(Request $request)
    {
        $data = $request->validate([
            'target_bulanan'      => 'required|integer|min:1',
            'komisi_per_servis'   => 'required|numeric|min:0',
            'bonus_target_bulanan' => 'required|numeric|min:0',
            'berlaku_mulai'       => 'required|date',
        ]);

        $data['created_by'] = (int) session('user_id');

        KonfigurasiKomisi::create($data);

        return redirect()
            ->route('komisi.konfigurasi.index')
            ->with('success', 'Konfigurasi komisi berhasil disimpan.');
    }

    // ================================================================
    // DATA KOMISI — Super Admin (all), Admin Cabang (cabangnya saja)
    // ================================================================

    /**
     * Daftar semua komisi teknisi dengan filter.
     */
    public function indexKomisi(Request $request)
    {
        $roleId   = (int) session('role_id');
        $userId   = (int) session('user_id');

        $query = KomisiTeknisi::with([
            'teknisi',
            'penugasanTeknisi.bookingServis.cabang',
        ]);

        // Admin Cabang hanya melihat komisi di cabangnya
        if ($roleId === 2) {
            $user     = \App\Models\User::findOrFail($userId);
            $cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;

            abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');

            $query->whereHas('penugasanTeknisi.bookingServis', function ($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            });
        }

        // Filter: cabang (Super Admin)
        if ($request->filled('cabang_id') && $roleId === 1) {
            $query->whereHas('penugasanTeknisi.bookingServis', function ($q) use ($request) {
                $q->where('id_cabang', $request->cabang_id);
            });
        }

        // Filter: periode (YYYY-MM)
        if ($request->filled('periode')) {
            $periodeDate = $request->periode . '-01';
            $query->whereDate('periode_bulan', $periodeDate);
        }

        // Filter: status pembayaran
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $komisi = $query->orderByDesc('periode_bulan')
            ->orderByDesc('id_komisi')
            ->paginate(20)
            ->withQueryString();

        $cabangList = \App\Models\Cabang::orderBy('nama_cabang')->get();

        return view('komisi.index', compact('komisi', 'cabangList'));
    }

    /**
     * Tandai komisi sebagai sudah dibayar.
     */
    public function markDibayar($id)
    {
        $komisi = KomisiTeknisi::findOrFail($id);

        if ($komisi->status_pembayaran === 'sudah_dibayar') {
            return back()->with('error', 'Komisi ini sudah berstatus dibayar.');
        }

        $komisi->update([
            'status_pembayaran' => 'sudah_dibayar',
            'tanggal_dibayar'   => now(),
        ]);

        return back()->with('success', 'Komisi berhasil ditandai sebagai sudah dibayar.');
    }
}
