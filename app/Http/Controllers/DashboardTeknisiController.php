<?php

namespace App\Http\Controllers;

use App\Models\KomisiTeknisi;
use App\Models\KonfigurasiKomisi;
use App\Models\LaporanServis;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardTeknisiController extends Controller
{
    public function index()
    {
        // ----------------------------------------------------------------
        // Data teknisi yang sedang login — DILARANG tampilkan data lain
        // ----------------------------------------------------------------
        $teknisi = Teknisi::where('user_id', session('user_id'))->firstOrFail();
        $idTeknisi = $teknisi->id_teknisi;

        // ----------------------------------------------------------------
        // Statistik penugasan
        // ----------------------------------------------------------------
        $query = $teknisi->penugasanTeknisi();

        $totalPenugasan  = (clone $query)->count();
        $penugasanBaru   = (clone $query)->where('status', 'Assigned')->count();
        $penugasanProses = (clone $query)->where('status', 'In Progress')->count();
        $penugasanSelesai= (clone $query)->where('status', 'Completed')->count();

        $penugasan = $teknisi->penugasanTeknisi()
            ->with([
                'bookingServis.pelanggan',
                'bookingServis.kendaraan',
                'bookingServis.layananServis',
            ])
            ->orderByDesc('tanggal')
            ->get();

        // ----------------------------------------------------------------
        // Target & Komisi bulan berjalan
        // ----------------------------------------------------------------
        $periodeAwal  = now()->startOfMonth()->toDateString(); // Y-m-01
        $periodeAkhir = now()->endOfMonth()->toDateString();

        // Jumlah servis dengan laporan disetujui bulan ini (milik teknisi ini)
        $servisSelesaiBulanIni = DB::table('penugasanteknisi as p')
            ->join('laporan_servis as l', 'l.penugasan_id', '=', 'p.id_penugasan')
            ->where('p.id_teknisi', $idTeknisi)
            ->where('l.status', 'disetujui')
            ->whereRaw("DATE_FORMAT(p.tanggal, '%Y-%m') = ?", [now()->format('Y-m')])
            ->count();

        $config = KonfigurasiKomisi::aktif()->first();
        $targetBulanan = $config ? (int) $config->target_bulanan : 20;

        $persentasePencapaian = $targetBulanan > 0
            ? min(100, round(($servisSelesaiBulanIni / $targetBulanan) * 100, 1))
            : 0;

        // Estimasi bonus: tampilkan nominal jika >= 100% dan belum ada bonus bulan ini
        $bonusBulanIni = KomisiTeknisi::where('id_teknisi', $idTeknisi)
            ->where('is_bonus_target', 1)
            ->whereDate('periode_bulan', $periodeAwal)
            ->exists();

        $estimasiBonus = null;
        if ($persentasePencapaian >= 100 && !$bonusBulanIni && $config) {
            $estimasiBonus = $config->bonus_target_bulanan;
        }

        // Rekap komisi bulan ini per status pembayaran
        $komisiStats = KomisiTeknisi::where('id_teknisi', $idTeknisi)
            ->whereDate('periode_bulan', $periodeAwal)
            ->selectRaw('status_pembayaran, SUM(nominal_komisi) as total, COUNT(*) as jumlah')
            ->groupBy('status_pembayaran')
            ->get()
            ->keyBy('status_pembayaran');

        $komisiTotalBelumDibayar = (float) ($komisiStats['belum_dibayar']->total ?? 0);
        $komisiTotalSudahDibayar = (float) ($komisiStats['sudah_dibayar']->total ?? 0);

        // Riwayat komisi bulan ini
        $riwayatKomisiBulanIni = KomisiTeknisi::where('id_teknisi', $idTeknisi)
            ->whereDate('periode_bulan', $periodeAwal)
            ->orderByDesc('id_komisi')
            ->get();

        return view('teknisi.dashboard', compact(
            'teknisi',
            'totalPenugasan',
            'penugasanBaru',
            'penugasanProses',
            'penugasanSelesai',
            'penugasan',
            // Komisi & target
            'servisSelesaiBulanIni',
            'targetBulanan',
            'persentasePencapaian',
            'estimasiBonus',
            'komisiTotalBelumDibayar',
            'komisiTotalSudahDibayar',
            'riwayatKomisiBulanIni'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['In Progress', 'Completed']),
            ],
        ]);

        $userId = session('user_id');

        $penugasan = DB::table('penugasanteknisi as p')
            ->join('Teknisi as t', 'p.id_teknisi', '=', 't.id_teknisi')
            ->where('p.id_penugasan', $id)
            ->where('t.user_id', $userId)
            ->select('p.*')
            ->first();

        if (!$penugasan) {
            abort(404, 'Penugasan tidak ditemukan atau bukan milik Anda.');
        }

        if ($penugasan->status === 'Completed') {
            return back()->with(
                'error',
                'Pekerjaan ini sudah selesai dan tidak dapat diubah lagi.'
            );
        }

        if (
            $data['status'] === 'Completed'
            && $penugasan->status !== 'In Progress'
        ) {
            return back()->with(
                'error',
                'Mulai pengerjaan terlebih dahulu sebelum menyelesaikan servis.'
            );
        }

        if (
            $data['status'] === 'In Progress'
            && $penugasan->status !== 'Assigned'
        ) {
            return back()->with(
                'error',
                'Status pekerjaan tidak dapat diubah ke tahap tersebut.'
            );
        }

        DB::transaction(function () use ($penugasan, $data) {
            DB::table('PenugasanTeknisi')
                ->where('id_penugasan', $penugasan->id_penugasan)
                ->update([
                    'status' => $data['status'],
                ]);

            $booking = DB::table('bookingservis')
                ->where('id_booking', $penugasan->id_booking)
                ->lockForUpdate()
                ->first();

            if ($booking && $booking->status !== 'Paid') {
                DB::table('bookingservis')
                    ->where('id_booking', $penugasan->id_booking)
                    ->update([
                        'status' => $data['status'],
                    ]);
            }
        });

        $pesan = $data['status'] === 'In Progress'
            ? 'Pekerjaan berhasil dimulai.'
            : 'Servis berhasil diselesaikan. Petugas Service dapat melanjutkan proses pembayaran.';

        return redirect()
            ->route('dashboard.teknisi')
            ->with('success', $pesan);
    }
}
