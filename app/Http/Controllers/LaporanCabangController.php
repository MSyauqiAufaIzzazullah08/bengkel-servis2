<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdminCabang;

class LaporanCabangController extends Controller
{
    /**
     * Resolve cabang_id dari session atau tabel AdminCabang.
     */
    private function resolveCabangId(): ?int
    {
        $cabangId = session('cabang_id');
        if ($cabangId) {
            return (int) $cabangId;
        }

        $admin = AdminCabang::where('user_id', session('user_id'))->first();
        return $admin ? (int) $admin->cabang_id : null;
    }

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
        $cabangId = $this->resolveCabangId();

        if (!$cabangId) {
            abort(403, 'Anda tidak memiliki akses ke data cabang.');
        }

        [$dateStart, $dateEnd] = $this->resolveDateRange($request);

        // --- Pendapatan Bengkel (dari booking) ---
        $pendapatanBengkel = DB::table('pembayaran')
            ->join('bookingservis', 'pembayaran.id_booking', '=', 'bookingservis.id_booking')
            ->where('bookingservis.id_cabang', $cabangId)
            ->where('pembayaran.status', 'lunas')
            ->whereBetween('bookingservis.tanggal_booking', [$dateStart, $dateEnd])
            ->sum('pembayaran.jumlah');

        // --- Pendapatan Home Service ---
        $pendapatanHomeService = DB::table('pembayaran')
            ->join('homeservice', 'pembayaran.id_home_service', '=', 'homeservice.id_home_service')
            ->where('homeservice.id_cabang', $cabangId)
            ->where('pembayaran.status', 'lunas')
            ->whereBetween('homeservice.tanggal', [$dateStart, $dateEnd])
            ->sum('pembayaran.jumlah');

        $totalPendapatan = (float) $pendapatanBengkel + (float) $pendapatanHomeService;

        // --- Statistik Booking ---
        $baseBooking = DB::table('bookingservis')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal_booking', [$dateStart, $dateEnd]);

        $totalBooking   = (clone $baseBooking)->count();
        $servisSelesai  = (clone $baseBooking)->where('status', 'Completed')->count();
        $servisBerjalan = (clone $baseBooking)->where('status', 'In Progress')->count();

        // --- Home Service Count ---
        $homeServiceCount = DB::table('homeservice')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal', [$dateStart, $dateEnd])
            ->count();

        // --- Jumlah Pelanggan Unik ---
        $jumlahPelanggan = DB::table('bookingservis')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
            ->distinct('id_pelanggan')
            ->count('id_pelanggan');

        // --- Performa Teknisi ---
        $performaTeknisi = DB::table('PenugasanTeknisi as pt')
            ->join('Teknisi as t', 'pt.id_teknisi', '=', 't.id_teknisi')
            ->join('bookingservis as bs', 'pt.id_booking', '=', 'bs.id_booking')
            ->leftJoin('Rating as r', 'r.id_booking', '=', 'bs.id_booking')
            ->leftJoin('komisi_teknisi as kt', 'kt.id_penugasan', '=', 'pt.id_penugasan')
            ->where('bs.id_cabang', $cabangId)
            ->where('pt.status', 'Completed')
            ->whereBetween('bs.tanggal_booking', [$dateStart, $dateEnd])
            ->select(
                't.id_teknisi',
                't.nama as nama_teknisi',
                DB::raw('COUNT(DISTINCT pt.id_penugasan) as jumlah_servis'),
                DB::raw('AVG(r.nilai) as avg_rating'),
                DB::raw('SUM(kt.nominal_komisi) as total_komisi')
            )
            ->groupBy('t.id_teknisi', 't.nama')
            ->orderByDesc('jumlah_servis')
            ->get();

        // --- Chart Data: booking per hari ---
        $chartData = DB::table('bookingservis')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
            ->select(
                DB::raw('DATE(tanggal_booking) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('DATE(tanggal_booking)'))
            ->orderBy('tanggal')
            ->get();

        // --- Status Booking untuk pie chart ---
        $statusData = DB::table('bookingservis')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal_booking', [$dateStart, $dateEnd])
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        return view('admin-cabang.laporan.index', compact(
            'totalBooking',
            'servisSelesai',
            'servisBerjalan',
            'homeServiceCount',
            'jumlahPelanggan',
            'pendapatanBengkel',
            'pendapatanHomeService',
            'totalPendapatan',
            'performaTeknisi',
            'chartData',
            'statusData',
            'dateStart',
            'dateEnd'
        ));
    }

    public function exportPdf(Request $request)
    {
        $cabangId = $this->resolveCabangId();

        if (!$cabangId) {
            abort(403, 'Anda tidak memiliki akses ke data cabang.');
        }

        [$dateStart, $dateEnd] = $this->resolveDateRange($request);

        $pendapatanBengkel = DB::table('pembayaran')
            ->join('bookingservis', 'pembayaran.id_booking', '=', 'bookingservis.id_booking')
            ->where('bookingservis.id_cabang', $cabangId)
            ->where('pembayaran.status', 'lunas')
            ->whereBetween('bookingservis.tanggal_booking', [$dateStart, $dateEnd])
            ->sum('pembayaran.jumlah');

        $pendapatanHomeService = DB::table('pembayaran')
            ->join('homeservice', 'pembayaran.id_home_service', '=', 'homeservice.id_home_service')
            ->where('homeservice.id_cabang', $cabangId)
            ->where('pembayaran.status', 'lunas')
            ->whereBetween('homeservice.tanggal', [$dateStart, $dateEnd])
            ->sum('pembayaran.jumlah');

        $totalPendapatan = (float) $pendapatanBengkel + (float) $pendapatanHomeService;

        $baseBooking   = DB::table('bookingservis')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal_booking', [$dateStart, $dateEnd]);
        $totalBooking  = (clone $baseBooking)->count();
        $servisSelesai = (clone $baseBooking)->where('status', 'Completed')->count();

        $homeServiceCount = DB::table('homeservice')
            ->where('id_cabang', $cabangId)
            ->whereBetween('tanggal', [$dateStart, $dateEnd])
            ->count();

        $performaTeknisi = DB::table('PenugasanTeknisi as pt')
            ->join('Teknisi as t', 'pt.id_teknisi', '=', 't.id_teknisi')
            ->join('bookingservis as bs', 'pt.id_booking', '=', 'bs.id_booking')
            ->leftJoin('Rating as r', 'r.id_booking', '=', 'bs.id_booking')
            ->leftJoin('komisi_teknisi as kt', 'kt.id_penugasan', '=', 'pt.id_penugasan')
            ->where('bs.id_cabang', $cabangId)
            ->where('pt.status', 'Completed')
            ->whereBetween('bs.tanggal_booking', [$dateStart, $dateEnd])
            ->select(
                't.nama as nama_teknisi',
                DB::raw('COUNT(DISTINCT pt.id_penugasan) as jumlah_servis'),
                DB::raw('AVG(r.nilai) as avg_rating'),
                DB::raw('SUM(kt.nominal_komisi) as total_komisi')
            )
            ->groupBy('t.id_teknisi', 't.nama')
            ->orderByDesc('jumlah_servis')
            ->get();

        $pdf = Pdf::loadView('admin-cabang.laporan.pdf', compact(
            'totalBooking',
            'servisSelesai',
            'homeServiceCount',
            'pendapatanBengkel',
            'pendapatanHomeService',
            'totalPendapatan',
            'performaTeknisi',
            'dateStart',
            'dateEnd'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-cabang-' . $dateStart->format('Y-m-d') . '.pdf');
    }
}
