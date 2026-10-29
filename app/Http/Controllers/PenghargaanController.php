<?php

namespace App\Http\Controllers;

use App\Models\PenghargaanTeknisi;
use App\Models\User;
use App\Services\PenghargaanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenghargaanController extends Controller
{
    /**
     * Leaderboard penghargaan teknisi.
     */
    public function index(Request $request)
    {
        $roleId = (int) session('role_id');
        $userId = (int) session('user_id');

        // Filter inputs
        $periodeTipe  = $request->input('periode_tipe', 'bulanan');
        $periodeMulai = $request->input('periode_mulai', now()->startOfMonth()->toDateString());
        $filterCabang = $request->input('cabang_id');

        // Admin Cabang hanya melihat cabangnya
        if ($roleId === 2) {
            $user     = User::findOrFail($userId);
            $cabangId = $user->cabang_id ?? optional($user->adminCabang)->cabang_id;
        } else {
            $cabangId = $filterCabang ?: null;
        }

        $query = PenghargaanTeknisi::with(['teknisi', 'cabang'])
            ->where('periode_tipe', $periodeTipe)
            ->where('periode_mulai', $periodeMulai)
            ->orderBy('peringkat');

        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }

        $penghargaan = $query->get();

        $semuaCabang = DB::table('cabang')->orderBy('nama_cabang')->get();

        return view('penghargaan.index', compact(
            'penghargaan',
            'periodeTipe',
            'periodeMulai',
            'cabangId',
            'semuaCabang'
        ));
    }

    /**
     * Generate / recalculate penghargaan.
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'periode_tipe'    => 'required|in:mingguan,bulanan,tahunan',
            'periode_mulai'   => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'cabang_id'       => 'nullable|integer',
        ]);

        $cabangId = $data['cabang_id'] ? (int) $data['cabang_id'] : null;

        // Admin Cabang diunci ke cabangnya sendiri
        if ((int) session('role_id') === 2) {
            $user     = User::findOrFail(session('user_id'));
            $cabangId = (int) ($user->cabang_id ?? optional($user->adminCabang)->cabang_id);
        }

        app(PenghargaanService::class)->hitungDanSimpan(
            $data['periode_tipe'],
            $data['periode_mulai'],
            $data['periode_selesai'],
            $cabangId
        );

        return redirect()
            ->route('penghargaan.index', [
                'periode_tipe'  => $data['periode_tipe'],
                'periode_mulai' => $data['periode_mulai'],
                'cabang_id'     => $cabangId,
            ])
            ->with('success', 'Penghargaan berhasil dihitung.');
    }
}
