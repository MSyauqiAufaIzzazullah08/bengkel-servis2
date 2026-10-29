<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cabang;

class LaporanSuperAdminController extends Controller
{
    /**
     * Resolve rentang tanggal berdasarkan parameter request.
     */
    private function resolveDateRange(Request $request): array
    {
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            return [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay(),
            ];
        }

        $periode = $request->get('periode', 'bulanan');

        return match ($periode) {
            'harian'   => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            'mingguan' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'tahunan'  => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            default    => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }

    public function index(Request $request)
    {
        if ((int) session('role_id') !== 1) {
            abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
        }

        [$dateStart, $dateEnd] = $this->resolveDateRange($request);

        $cabangs = Cabang::all();

        $dataCabang = [];
        $grandTotalBooking    = 0;
        $grandTotalPendapatan = 0;

        foreach ($cabangs as $cabang) {
            $id = $cabang->id_cabang;

            $totalBooking = DB::table('bookingservis')
                ->where('id_cabang', $id)
                ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
                ->count();

            $servisSelesai = DB::table('bookingservis')
                ->where('id_cabang', $id)
                ->where('status', 'Completed')
                ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
                ->count();

            $pendapatanBengkel = DB::table('pembayaran')
                ->join('bookingservis', 'pembayaran.id_booking', '=', 'bookingservis.id_booking')
                ->where('bookingservis.id_cabang', $id)
                ->where('pembayaran.status', 'lunas')
                ->whereBetween('bookingservis.tanggal_booking', [$dateStart, $dateEnd])
                ->sum('pembayaran.jumlah');

            $pendapatanHomeService = DB::table('pembayaran')
                ->join('homeservice', 'pembayaran.id_home_service', '=', 'homeservice.id_home_service')
                ->where('homeservice.id_cabang', $id)
                ->where('pembayaran.status', 'lunas')
                ->whereBetween('homeservice.tanggal', [$dateStart, $dateEnd])
                ->sum('pembayaran.jumlah');

            $totalPendapatan = (float) $pendapatanBengkel + (float) $pendapatanHomeService;

            $jumlahPelanggan = DB::table('bookingservis')
                ->where('id_cabang', $id)
                ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
                ->distinct('id_pelanggan')
                ->count('id_pelanggan');

            $hsJumlah = DB::table('homeservice')
                ->where('id_cabang', $id)
                ->whereBetween('tanggal', [$dateStart, $dateEnd])
                ->count();

            $avgRating = DB::table('Rating as r')
                ->join('bookingservis as bs', 'r.id_booking', '=', 'bs.id_booking')
                ->where('bs.id_cabang', $id)
                ->whereBetween('bs.tanggal_booking', [$dateStart, $dateEnd])
                ->avg('r.nilai');

            $dataCabang[] = [
                'nama_cabang'           => $cabang->nama_cabang,
                'total_booking'         => $totalBooking,
                'servis_selesai'        => $servisSelesai,
                'pendapatan_bengkel'    => $pendapatanBengkel,
                'pendapatan_home_service' => $pendapatanHomeService,
                'total_pendapatan'      => $totalPendapatan,
                'jumlah_pelanggan'      => $jumlahPelanggan,
                'hs_jumlah'             => $hsJumlah,
                'avg_rating'            => $avgRating ? round($avgRating, 1) : null,
            ];

            $grandTotalBooking    += $totalBooking;
            $grandTotalPendapatan += $totalPendapatan;
        }

        // --- Tren bulanan 12 bulan terakhir ---
        $trenBulanan = DB::table('bookingservis')
            ->where('tanggal_booking', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(tanggal_booking, '%Y-%m') as bulan"),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw("DATE_FORMAT(tanggal_booking, '%Y-%m')"))
            ->orderBy('bulan')
            ->get();

        // Isi bulan kosong agar chart selalu 12 titik
        $trenLabels = [];
        $trenValues = [];
        $bulanMap   = $trenBulanan->pluck('jumlah', 'bulan')->toArray();

        for ($i = 11; $i >= 0; $i--) {
            $label = Carbon::now()->subMonths($i)->format('Y-m');
            $trenLabels[] = Carbon::now()->subMonths($i)->translatedFormat('M Y');
            $trenValues[] = $bulanMap[$label] ?? 0;
        }

        // --- Top 10 Teknisi Nasional ---
        $topTeknisi = DB::table('PenugasanTeknisi as pt')
            ->join('Teknisi as t', 'pt.id_teknisi', '=', 't.id_teknisi')
            ->join('bookingservis as bs', 'pt.id_booking', '=', 'bs.id_booking')
            ->leftJoin('Rating as r', 'r.id_booking', '=', 'bs.id_booking')
            ->where('pt.status', 'Completed')
            ->select(
                't.id_teknisi',
                't.nama as nama_teknisi',
                DB::raw('COUNT(DISTINCT pt.id_penugasan) as jumlah_servis'),
                DB::raw('AVG(r.nilai) as avg_rating')
            )
            ->groupBy('t.id_teknisi', 't.nama')
            ->orderByDesc('jumlah_servis')
            ->orderByDesc(DB::raw('AVG(r.nilai)'))
            ->limit(10)
            ->get();

        return view('super-admin.laporan.index', compact(
            'dataCabang',
            'grandTotalBooking',
            'grandTotalPendapatan',
            'trenLabels',
            'trenValues',
            'topTeknisi',
            'dateStart',
            'dateEnd'
        ));
    }

    public function exportPdf(Request $request)
    {
        if ((int) session('role_id') !== 1) {
            abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
        }

        [$dateStart, $dateEnd] = $this->resolveDateRange($request);

        $cabangs = Cabang::all();
        $dataCabang = [];
        $grandTotalBooking    = 0;
        $grandTotalPendapatan = 0;

        foreach ($cabangs as $cabang) {
            $id = $cabang->id_cabang;

            $totalBooking = DB::table('bookingservis')
                ->where('id_cabang', $id)
                ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
                ->count();

            $pendapatanBengkel = DB::table('pembayaran')
                ->join('bookingservis', 'pembayaran.id_booking', '=', 'bookingservis.id_booking')
                ->where('bookingservis.id_cabang', $id)
                ->where('pembayaran.status', 'lunas')
                ->whereBetween('bookingservis.tanggal_booking', [$dateStart, $dateEnd])
                ->sum('pembayaran.jumlah');

            $pendapatanHomeService = DB::table('pembayaran')
                ->join('homeservice', 'pembayaran.id_home_service', '=', 'homeservice.id_home_service')
                ->where('homeservice.id_cabang', $id)
                ->where('pembayaran.status', 'lunas')
                ->whereBetween('homeservice.tanggal', [$dateStart, $dateEnd])
                ->sum('pembayaran.jumlah');

            $totalPendapatan = (float) $pendapatanBengkel + (float) $pendapatanHomeService;

            $hsJumlah = DB::table('homeservice')
                ->where('id_cabang', $id)
                ->whereBetween('tanggal', [$dateStart, $dateEnd])
                ->count();

            $servisSelesai = DB::table('bookingservis')
                ->where('id_cabang', $id)
                ->where('status', 'Completed')
                ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
                ->count();

            $avgRating = DB::table('Rating as r')
                ->join('bookingservis as bs', 'r.id_booking', '=', 'bs.id_booking')
                ->where('bs.id_cabang', $id)
                ->whereBetween('bs.tanggal_booking', [$dateStart, $dateEnd])
                ->avg('r.nilai');

            $dataCabang[] = [
                'nama_cabang'             => $cabang->nama_cabang,
                'total_booking'           => $totalBooking,
                'servis_selesai'          => $servisSelesai,
                'pendapatan_bengkel'      => $pendapatanBengkel,
                'pendapatan_home_service' => $pendapatanHomeService,
                'total_pendapatan'        => $totalPendapatan,
                'hs_jumlah'               => $hsJumlah,
                'avg_rating'              => $avgRating ? round($avgRating, 1) : null,
            ];

            $grandTotalBooking    += $totalBooking;
            $grandTotalPendapatan += $totalPendapatan;
        }

        $topTeknisi = DB::table('PenugasanTeknisi as pt')
            ->join('Teknisi as t', 'pt.id_teknisi', '=', 't.id_teknisi')
            ->join('bookingservis as bs', 'pt.id_booking', '=', 'bs.id_booking')
            ->leftJoin('Rating as r', 'r.id_booking', '=', 'bs.id_booking')
            ->where('pt.status', 'Completed')
            ->select(
                't.nama as nama_teknisi',
                DB::raw('COUNT(DISTINCT pt.id_penugasan) as jumlah_servis'),
                DB::raw('AVG(r.nilai) as avg_rating')
            )
            ->groupBy('t.id_teknisi', 't.nama')
            ->orderByDesc('jumlah_servis')
            ->limit(10)
            ->get();

        $pdf = Pdf::loadView('super-admin.laporan.pdf', compact(
            'dataCabang',
            'grandTotalBooking',
            'grandTotalPendapatan',
            'topTeknisi',
            'dateStart',
            'dateEnd'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-eksekutif-' . $dateStart->format('Y-m-d') . '.pdf');
    }
}
