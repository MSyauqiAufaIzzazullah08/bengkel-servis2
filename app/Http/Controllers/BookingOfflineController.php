<?php

namespace App\Http\Controllers;

use App\Models\AdminCabang;
use App\Models\BookingServis;
use App\Models\Kendaraan;
use App\Models\LayananServis;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingOfflineController extends Controller
{
    /**
     * Resolve cabang_id dari session atau tabel AdminCabang.
     */
    private function resolveCabangId(): int
    {
        $user = User::findOrFail(session('user_id'));
        $cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;

        abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');

        return (int) $cabangId;
    }

    /**
     * Daftar antrean dan riwayat booking offline di cabang admin.
     */
    public function index()
    {
        $cabangId = $this->resolveCabangId();

        $bookings = DB::table('bookingservis as b')
            ->join('pelanggan as p', 'b.id_pelanggan', '=', 'p.id_pelanggan')
            ->join('kendaraan as k', 'b.id_kendaraan', '=', 'k.id_kendaraan')
            ->leftJoin('layananservis as l', 'b.layanan_id', '=', 'l.id_layanan')
            ->leftJoin('User as u', 'b.didaftarkan_oleh', '=', 'u.id_user')
            ->where('b.id_cabang', $cabangId)
            ->where('b.sumber_pendaftaran', 'offline')
            ->select(
                'b.id_booking',
                'b.tanggal_booking',
                'b.waktu_booking',
                'b.status',
                'b.catatan',
                'p.nama as nama_pelanggan',
                'p.no_hp',
                'k.merk',
                'k.model',
                'k.nopol',
                'l.nama_layanan',
                'u.nama as didaftarkan_oleh'
            )
            ->orderByDesc('b.tanggal_booking')
            ->orderByDesc('b.id_booking')
            ->paginate(20);

        return view('booking-offline.index', compact('bookings'));
    }

    /**
     * AJAX — cari pelanggan berdasarkan no_hp.
     */
    public function cariPelanggan(Request $request)
    {
        $noHp = trim($request->get('no_hp', ''));

        if (!$noHp) {
            return response()->json(null);
        }

        $pelanggan = DB::table('pelanggan')
            ->where('no_hp', $noHp)
            ->first();

        return response()->json($pelanggan);
    }

    /**
     * AJAX — cari kendaraan berdasarkan nomor polisi.
     */
    public function cariKendaraan(Request $request)
    {
        $nopol = strtoupper(trim($request->get('nopol', '')));

        if (!$nopol) {
            return response()->json(null);
        }

        $kendaraan = DB::table('kendaraan as k')
            ->join('pelanggan as p', 'k.id_pelanggan', '=', 'p.id_pelanggan')
            ->where('k.nopol', $nopol)
            ->select('k.*', 'p.nama as nama_pemilik', 'p.no_hp', 'p.id_pelanggan')
            ->first();

        return response()->json($kendaraan);
    }

    /**
     * Form booking offline baru.
     */
    public function create()
    {
        $cabangId = $this->resolveCabangId();
        $layanans = LayananServis::orderBy('nama_layanan')->get();

        return view('booking-offline.create', compact('layanans', 'cabangId'));
    }

    /**
     * Simpan booking offline baru.
     * Flow: cek/buat pelanggan → cek/buat kendaraan → buat booking.
     */
    public function store(Request $request)
    {
        $cabangId = $this->resolveCabangId();

        $request->validate([
            'nama_pelanggan'  => 'required|string|max:100',
            'no_hp'           => 'required|string|max:20',
            'alamat'          => 'nullable|string|max:255',
            'merk'            => 'required|string|max:50',
            'model'           => 'required|string|max:50',
            'tahun'           => 'required|integer|min:1980|max:' . (date('Y') + 1),
            'nopol'           => 'required|string|max:15',
            'layanan_id'      => 'required|integer|exists:layananservis,id_layanan',
            'tanggal_booking' => 'required|date',
            'waktu_booking'   => 'required',
            'catatan'         => 'nullable|string|max:500',
        ]);

        $nopol = strtoupper(trim($request->nopol));

        DB::transaction(function () use ($request, $cabangId, $nopol) {
            // 1. Cari atau buat pelanggan berdasarkan no_hp
            $pelanggan = Pelanggan::where('no_hp', $request->no_hp)->first();

            if (!$pelanggan) {
                $pelanggan = Pelanggan::create([
                    'nama'          => $request->nama_pelanggan,
                    'no_hp'         => $request->no_hp,
                    'alamat'        => $request->alamat,
                    'email'         => null,
                    'tanggal_daftar' => now()->toDateString(),
                    'user_id'       => null, // offline — tidak punya akun
                ]);
            }

            // 2. Cari atau buat kendaraan berdasarkan nopol
            $kendaraan = Kendaraan::where('nopol', $nopol)->first();

            if (!$kendaraan) {
                $kendaraan = Kendaraan::create([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'merk'         => $request->merk,
                    'model'        => $request->model,
                    'tahun'        => $request->tahun,
                    'nopol'        => $nopol,
                ]);
            }

            // 3. Buat booking dengan sumber_pendaftaran='offline'
            BookingServis::create([
                'id_pelanggan'       => $pelanggan->id_pelanggan,
                'id_kendaraan'       => $kendaraan->id_kendaraan,
                'id_cabang'          => $cabangId,
                'layanan_id'         => $request->layanan_id,
                'tanggal_booking'    => $request->tanggal_booking,
                'waktu_booking'      => $request->waktu_booking,
                'status'             => 'Pending',
                'catatan'            => $request->catatan,
                'sumber_pendaftaran' => 'offline',
                'didaftarkan_oleh'   => (int) session('user_id'),
            ]);
        });

        return redirect()
            ->route('booking-offline.index')
            ->with('success', 'Booking offline berhasil didaftarkan.');
    }
}
