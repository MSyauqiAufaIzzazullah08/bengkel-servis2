<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganBookingController extends Controller
{
    private function pelangganLogin()
    {
        return DB::table('pelanggan')
            ->where('email', session('user_email'))
            ->first();
    }

    /**
     * Riwayat booking bengkel dan Home Service pelanggan.
     */
    public function index()
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        // Booking servis langsung ke bengkel.
        $bookings = DB::table('bookingservis as b')
            ->leftJoin(
                'kendaraan as k',
                'b.id_kendaraan',
                '=',
                'k.id_kendaraan'
            )
            ->leftJoin(
                'cabang as c',
                'b.id_cabang',
                '=',
                'c.id_cabang'
            )
            ->leftJoin(
                'layananservis as l',
                'b.layanan_id',
                '=',
                'l.id_layanan'
            )
            ->where('b.id_pelanggan', $pelanggan->id_pelanggan)
            ->select(
                'b.id_booking',
                'b.tanggal_booking',
                'b.waktu_booking',
                'b.status',
                'b.catatan',
                'k.merk',
                'k.model',
                'k.nopol',
                'c.nama_cabang',
                'l.nama_layanan',
                'l.harga'
            )
            ->get()
            ->map(function ($booking) {
                $booking->jenis_pengajuan = 'Servis Bengkel';
                $booking->alamat_home_service = null;

                return $booking;
            });

        // Pengajuan Home Service pelanggan.
        $homeServices = DB::table('homeservice as hs')
            ->leftJoin(
                'kendaraan as k',
                'hs.id_kendaraan',
                '=',
                'k.id_kendaraan'
            )
            ->leftJoin(
                'cabang as c',
                'hs.id_cabang',
                '=',
                'c.id_cabang'
            )
            ->leftJoin(
                'layananservis as l',
                'hs.id_layanan_servis',
                '=',
                'l.id_layanan'
            )
            ->where('hs.id_pelanggan', $pelanggan->id_pelanggan)
            ->select(
                'hs.id_home_service as id_booking',
                'hs.tanggal as tanggal_booking',
                'hs.status',
                'hs.catatan',
                'hs.alamat as alamat_home_service',
                'k.merk',
                'k.model',
                'k.nopol',
                'c.nama_cabang',
                'l.nama_layanan',
                'l.harga'
            )
            ->get()
            ->map(function ($homeService) {
                $homeService->waktu_booking = null;
                $homeService->jenis_pengajuan = 'Home Service';

                return $homeService;
            });

        // Gabungkan kedua jenis pengajuan dan urutkan berdasarkan tanggal.
        $bookings = $bookings
            ->concat($homeServices)
            ->sortByDesc(function ($item) {
                return $item->tanggal_booking
                    . ' '
                    . ($item->waktu_booking ?? '00:00:00');
            })
            ->values();

        return view('pelanggan.booking.index', compact(
            'pelanggan',
            'bookings'
        ));
    }

    /**
     * Menampilkan formulir booking.
     */
    public function create()
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $kendaraan = DB::table('kendaraan')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderByDesc('id_kendaraan')
            ->get();

        $cabang = DB::table('cabang')
            ->orderBy('nama_cabang')
            ->get();

        $layanan = DB::table('layananservis')
            ->orderBy('nama_layanan')
            ->get();

        return view('booking-pelanggan.create', compact(
            'pelanggan',
            'kendaraan',
            'cabang',
            'layanan'
        ));
    }

    /**
     * Menyimpan booking baru.
     */
    public function store(Request $request)
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $data = $request->validate([
            'id_kendaraan' => 'required|integer',
            'id_cabang' => 'required|integer',
            'layanan_id' => 'required|integer',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'waktu_booking' => 'required|date_format:H:i',
            'catatan' => 'nullable|string|max:2000',
        ]);

        $kendaraanValid = DB::table('kendaraan')
            ->where('id_kendaraan', $data['id_kendaraan'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->exists();

        if (!$kendaraanValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_kendaraan' =>
                        'Kendaraan tidak valid atau bukan milik akunmu.',
                ]);
        }

        $cabangValid = DB::table('cabang')
            ->where('id_cabang', $data['id_cabang'])
            ->exists();

        if (!$cabangValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_cabang' => 'Cabang bengkel tidak ditemukan.',
                ]);
        }

        $layananValid = DB::table('layananservis')
            ->where('id_layanan', $data['layanan_id'])
            ->exists();

        if (!$layananValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'layanan_id' => 'Layanan servis tidak ditemukan.',
                ]);
        }

        DB::table('bookingservis')->insert([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_kendaraan' => $data['id_kendaraan'],
            'id_cabang' => $data['id_cabang'],
            'layanan_id' => $data['layanan_id'],
            'tanggal_booking' => $data['tanggal_booking'],
            'waktu_booking' => $data['waktu_booking'],
            'status' => 'Pending',
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect('/booking-pelanggan')
            ->with('success', 'Booking servis berhasil diajukan.');
    }
}
