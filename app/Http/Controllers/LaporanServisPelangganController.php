<?php

namespace App\Http\Controllers;

use App\Models\LaporanServis;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanServisPelangganController extends Controller
{
    /**
     * Resolve pelanggan dari email session.
     */
    private function resolvePelanggan()
    {
        $pelanggan = DB::table('pelanggan')
            ->where('email', session('user_email'))
            ->first();

        abort_if(!$pelanggan, 403, 'Data pelanggan tidak ditemukan.');

        return $pelanggan;
    }

    /**
     * Riwayat semua servis pelanggan yang sudah disetujui (booking + home service).
     * Role 6 — pelanggan.
     */
    public function index()
    {
        $pelanggan = $this->resolvePelanggan();

        // Laporan dari booking bengkel.
        $laporanBengkel = DB::table('laporan_servis as ls')
            ->join('bookingservis as b', 'ls.booking_id', '=', 'b.id_booking')
            ->leftJoin('kendaraan as k', 'b.id_kendaraan', '=', 'k.id_kendaraan')
            ->leftJoin('cabang as c', 'b.id_cabang', '=', 'c.id_cabang')
            ->leftJoin('layananservis as l', 'b.layanan_id', '=', 'l.id_layanan')
            ->where('b.id_pelanggan', $pelanggan->id_pelanggan)
            ->where('ls.status', 'disetujui')
            ->select(
                'ls.id',
                'ls.tanggal_selesai_pekerjaan',
                'ls.status',
                'ls.tindakan_perbaikan',
                'b.tanggal_booking',
                'k.merk',
                'k.model',
                'k.nopol',
                'c.nama_cabang',
                'l.nama_layanan',
                DB::raw("'Servis Bengkel' as jenis")
            )
            ->get();

        // Riwayat Home Service pelanggan (alur terpisah dari booking bengkel).
        $homeServiceHistory = DB::table('homeservice')
            ->leftJoin('layananservis', 'homeservice.id_layanan_servis', '=', 'layananservis.id_layanan')
            ->leftJoin('kendaraan', 'homeservice.id_kendaraan', '=', 'kendaraan.id_kendaraan')
            ->where('homeservice.id_pelanggan', $pelanggan->id_pelanggan)
            ->select(
                'homeservice.id_home_service',
                'homeservice.tanggal',
                'homeservice.status',
                'homeservice.alamat',
                'layananservis.nama_layanan',
                'kendaraan.merk',
                'kendaraan.model',
                'kendaraan.nopol'
            )
            ->orderByDesc('homeservice.tanggal')
            ->get();

        return view('pelanggan.laporan-servis.index', compact('pelanggan', 'laporanBengkel', 'homeServiceHistory'));
    }

    /**
     * Detail satu laporan servis (hanya yang sudah disetujui).
     * Role 6 — pelanggan.
     */
    public function show($id)
    {
        $pelanggan = $this->resolvePelanggan();

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.bookingServis.layananServis',
            'penugasanTeknisi.bookingServis.cabang',
            'penugasanTeknisi.teknisi',
        ])->findOrFail($id);

        abort_if($laporan->status !== 'disetujui', 403, 'Laporan belum tersedia.');

        // Pastikan laporan milik pelanggan ini.
        $idPelangganLaporan = optional(
            optional($laporan->penugasanTeknisi)->bookingServis
        )->id_pelanggan;

        abort_if(
            (int) $idPelangganLaporan !== (int) $pelanggan->id_pelanggan,
            403,
            'Laporan ini bukan milik Anda.'
        );

        return view('pelanggan.laporan-servis.show', compact('laporan', 'pelanggan'));
    }

    /**
     * Download PDF laporan servis (pelanggan — role 6).
     */
    public function downloadPdf($id)
    {
        $pelanggan = $this->resolvePelanggan();

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.bookingServis.layananServis',
            'penugasanTeknisi.bookingServis.cabang',
            'penugasanTeknisi.teknisi',
        ])->findOrFail($id);

        abort_if($laporan->status !== 'disetujui', 403, 'Laporan belum tersedia.');

        $idPelangganLaporan = optional(
            optional($laporan->penugasanTeknisi)->bookingServis
        )->id_pelanggan;

        abort_if(
            (int) $idPelangganLaporan !== (int) $pelanggan->id_pelanggan,
            403,
            'Laporan ini bukan milik Anda.'
        );

        $pdf = Pdf::loadView('pelanggan.laporan-servis.pdf', compact('laporan', 'pelanggan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-servis-' . $laporan->id . '.pdf');
    }

    /**
     * Daftar laporan di cabang Admin Cabang (role 2) — untuk cetak offline.
     */
    public function indexAdmin()
    {
        $user = User::findOrFail(session('user_id'));
        $cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;

        abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.teknisi',
        ])
            ->where('status', 'disetujui')
            ->whereHas('penugasanTeknisi.bookingServis', function ($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.laporan-servis.index', compact('laporan'));
    }

    /**
     * Download PDF laporan servis (Admin Cabang — role 2 cetak offline).
     */
    public function downloadPdfAdmin($id)
    {
        $user = User::findOrFail(session('user_id'));
        $cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;

        abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.bookingServis.layananServis',
            'penugasanTeknisi.bookingServis.cabang',
            'penugasanTeknisi.teknisi',
        ])->findOrFail($id);

        abort_if($laporan->status !== 'disetujui', 403, 'Laporan belum tersedia.');

        $idCabangLaporan = optional(
            optional($laporan->penugasanTeknisi)->bookingServis
        )->id_cabang;

        abort_if((int) $idCabangLaporan !== (int) $cabangId, 403, 'Laporan bukan milik cabang Anda.');

        $pelanggan = optional(optional($laporan->penugasanTeknisi)->bookingServis)->pelanggan;

        $pdf = Pdf::loadView('pelanggan.laporan-servis.pdf', compact('laporan', 'pelanggan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-servis-' . $laporan->id . '.pdf');
    }
}
