<?php

namespace App\Http\Controllers;

use App\Models\LaporanServis;
use App\Models\RiwayatVerifikasiLaporan;
use App\Models\User;
use App\Services\KomisiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiLaporanController extends Controller
{
    /**
     * Resolve cabang_id Admin Cabang yang sedang login.
     */
    private function resolveCabangId(): int
    {
        $user = User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;

        abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');

        return (int) $cabangId;
    }

    /**
     * Daftar laporan berstatus menunggu_verifikasi di cabang Admin Cabang.
     */
    public function index()
    {
        $cabangId = $this->resolveCabangId();

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.teknisi',
        ])
            ->where('status', 'menunggu_verifikasi')
            ->whereHas('penugasanTeknisi.bookingServis', function ($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        return view('verifikasi-laporan.index', compact('laporan'));
    }

    /**
     * Detail laporan + riwayat verifikasi sebelumnya.
     */
    public function show($id)
    {
        $cabangId = $this->resolveCabangId();

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.bookingServis.layananServis',
            'penugasanTeknisi.teknisi',
            'riwayatVerifikasi.admin',
        ])->findOrFail($id);

        // Pastikan laporan ini berasal dari cabang admin yang login.
        $idCabangLaporan = optional(
            optional($laporan->penugasanTeknisi)->bookingServis
        )->id_cabang;

        abort_if(
            (int) $idCabangLaporan !== $cabangId,
            403,
            'Laporan ini bukan milik cabang Anda.'
        );

        return view('verifikasi-laporan.show', compact('laporan'));
    }

    /**
     * Setujui laporan: status → disetujui, isi tanggal_selesai penugasan.
     */
    public function setujui(Request $request, $id)
    {
        $cabangId  = $this->resolveCabangId();
        $adminId   = (int) session('user_id');

        $data = $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $laporan = LaporanServis::with('penugasanTeknisi')->findOrFail($id);

        $idCabangLaporan = optional(
            optional($laporan->penugasanTeknisi)->bookingServis()->first()
        )->id_cabang;

        // Fallback: query langsung jika relasi belum di-load.
        if (!$idCabangLaporan && $laporan->booking_id) {
            $idCabangLaporan = DB::table('bookingservis')
                ->where('id_booking', $laporan->booking_id)
                ->value('id_cabang');
        }

        abort_if((int) $idCabangLaporan !== $cabangId, 403, 'Laporan ini bukan milik cabang Anda.');

        abort_if(
            $laporan->status !== 'menunggu_verifikasi',
            422,
            'Hanya laporan berstatus menunggu_verifikasi yang dapat disetujui.'
        );

        DB::transaction(function () use ($laporan, $adminId, $data) {
            // 1. Update status laporan.
            $laporan->update(['status' => 'disetujui']);

            // 2. Catat riwayat verifikasi.
            RiwayatVerifikasiLaporan::create([
                'laporan_id' => $laporan->id,
                'admin_id'   => $adminId,
                'aksi'       => 'disetujui',
                'catatan'    => $data['catatan'] ?? null,
            ]);

            // 3. Isi tanggal_selesai penugasan.
            if ($laporan->penugasan_id) {
                DB::table('PenugasanTeknisi')
                    ->where('id_penugasan', $laporan->penugasan_id)
                    ->update(['tanggal_selesai' => now()]);
            }

            // 4. Hitung komisi untuk penugasan ini.
            if ($laporan->penugasan_id) {
                $penugasan = $laporan->penugasanTeknisi;
                if ($penugasan) {
                    app(KomisiService::class)->hitungKomisiPenugasan($penugasan);
                }
            }
        });

        return redirect()
            ->route('verifikasi-laporan.index')
            ->with('success', 'Laporan berhasil disetujui.');
    }

    /**
     * Minta revisi laporan: status → perlu_revisi.
     */
    public function minta_revisi(Request $request, $id)
    {
        $cabangId = $this->resolveCabangId();
        $adminId  = (int) session('user_id');

        $data = $request->validate([
            'catatan' => 'required|string|max:1000',
        ]);

        $laporan = LaporanServis::findOrFail($id);

        $idCabangLaporan = null;
        if ($laporan->booking_id) {
            $idCabangLaporan = DB::table('bookingservis')
                ->where('id_booking', $laporan->booking_id)
                ->value('id_cabang');
        } elseif ($laporan->penugasan_id) {
            // Laporan dari home service atau yang tidak punya booking_id langsung —
            // cari cabang via penugasanteknisi → bookingservis
            $idCabangLaporan = DB::table('PenugasanTeknisi as pt')
                ->join('bookingservis as bs', 'pt.id_booking', '=', 'bs.id_booking')
                ->where('pt.id_penugasan', $laporan->penugasan_id)
                ->value('bs.id_cabang');
        }

        abort_if((int) $idCabangLaporan !== $cabangId, 403, 'Laporan ini bukan milik cabang Anda.');

        abort_if(
            $laporan->status !== 'menunggu_verifikasi',
            422,
            'Hanya laporan berstatus menunggu_verifikasi yang dapat diminta revisi.'
        );

        DB::transaction(function () use ($laporan, $adminId, $data) {
            $laporan->update(['status' => 'perlu_revisi']);

            RiwayatVerifikasiLaporan::create([
                'laporan_id' => $laporan->id,
                'admin_id'   => $adminId,
                'aksi'       => 'perlu_revisi',
                'catatan'    => $data['catatan'],
            ]);
        });

        return redirect()
            ->route('verifikasi-laporan.index')
            ->with('success', 'Laporan dikembalikan untuk direvisi.');
    }
}
