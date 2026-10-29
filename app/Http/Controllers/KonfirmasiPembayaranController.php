<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\BookingServis;
use App\Models\HomeServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Konfirmasi Pembayaran — Admin Cabang (role:2)
 *
 * Admin Cabang dapat melihat daftar pembayaran Booking Servis dan
 * Home Service yang statusnya "menunggu", lalu meng-ACC (lunas) atau
 * Menolak (gagal) setiap transaksi di cabangnya sendiri.
 *
 * Keputusan disimpan di: verified_by, verified_at, alasan_penolakan.
 * Status HomeServis (progres servis) TIDAK diubah di sini karena
 * kolom tersebut menggambarkan tahap pengerjaan, bukan pembayaran.
 * Hanya status BookingServis yang diubah ke "Paid" karena seluruh
 * sistem sudah bergantung pada nilai tersebut (Dashboard Teknisi,
 * Laporan Cabang, Pembayaran Pelanggan, dll.).
 */
class KonfirmasiPembayaranController extends Controller
{
    /**
     * Daftar pembayaran yang menunggu konfirmasi di cabang Admin.
     */
    public function index()
    {
        $cabangId = $this->resolveCabangId();

        // Pembayaran Booking Servis milik cabang ini, status menunggu
        $pembayaranBooking = Pembayaran::with([
            'bookingServis.pelanggan',
            'bookingServis.kendaraan',
            'bookingServis.layananServis',
            'metodePembayaran',
        ])
        ->whereNotNull('id_booking')
        ->where('status', 'menunggu')
        ->whereHas('bookingServis', function ($q) use ($cabangId) {
            $q->where('id_cabang', $cabangId);
        })
        ->orderByDesc('id_pembayaran')
        ->get()
        ->map(function ($p) {
            $p->jenis = 'Booking Servis';
            return $p;
        });

        // Pembayaran Home Service milik cabang ini, status menunggu
        $pembayaranHome = Pembayaran::with([
            'homeServis.pelanggan',
            'homeServis.kendaraan',
            'homeServis.layananServis',
            'metodePembayaran',
        ])
        ->whereNotNull('id_home_service')
        ->where('status', 'menunggu')
        ->whereHas('homeServis', function ($q) use ($cabangId) {
            $q->where('id_cabang', $cabangId);
        })
        ->orderByDesc('id_pembayaran')
        ->get()
        ->map(function ($p) {
            $p->jenis = 'Home Service';
            return $p;
        });

        $semuaPembayaran = $pembayaranBooking
            ->concat($pembayaranHome)
            ->sortByDesc('id_pembayaran')
            ->values();

        return view('konfirmasi-pembayaran.index', compact('semuaPembayaran'));
    }

    /**
     * ACC pembayaran → status "lunas", catat verified_by & verified_at.
     */
    public function acc(Request $request, int $id)
    {
        DB::transaction(function () use ($id) {
            $pembayaran = Pembayaran::where('id_pembayaran', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pembayaran->status !== 'menunggu') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya pembayaran berstatus "menunggu" yang dapat di-ACC.',
                ]);
            }

            // Validasi hak akses lintas cabang
            $this->validateCabangAccess($pembayaran);

            $pembayaran->update([
                'status'       => 'lunas',
                'tanggal_bayar' => now(),
                'verified_by'  => (int) session('user_id'),
                'verified_at'  => now(),
            ]);

            // Hanya BookingServis yang diupdate ke "Paid" —
            // status ini dipakai di seluruh sistem (Teknisi, Laporan, dll.)
            if (!empty($pembayaran->id_booking)) {
                BookingServis::where('id_booking', $pembayaran->id_booking)
                    ->update(['status' => 'Paid']);
            }

            // Status HomeServis (Pending/In Progress/Completed) TIDAK diubah
            // karena menggambarkan progres servis, bukan status pembayaran.
        });

        return redirect()
            ->route('konfirmasi-pembayaran.index')
            ->with('success', 'Pembayaran berhasil di-ACC. Status diubah menjadi Lunas.');
    }

    /**
     * Tolak pembayaran → status "gagal", alasan wajib diisi, catat verifikator.
     */
    public function tolak(Request $request, int $id)
    {
        // Alasan penolakan WAJIB diisi
        $data = $request->validate([
            'alasan_tolak' => 'required|string|min:5|max:500',
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
            'alasan_tolak.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($id, $data) {
            $pembayaran = Pembayaran::where('id_pembayaran', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pembayaran->status !== 'menunggu') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya pembayaran berstatus "menunggu" yang dapat ditolak.',
                ]);
            }

            // Validasi hak akses lintas cabang
            $this->validateCabangAccess($pembayaran);

            $pembayaran->update([
                'status'           => 'gagal',
                'alasan_penolakan' => $data['alasan_tolak'],
                'verified_by'      => (int) session('user_id'),
                'verified_at'      => now(),
            ]);

            // Status BookingServis & HomeServis tidak diubah saat penolakan —
            // transaksi bisa diajukan ulang oleh pelanggan.
        });

        return redirect()
            ->route('konfirmasi-pembayaran.index')
            ->with('warning', 'Pembayaran ditolak. Alasan telah disimpan.');
    }

    /**
     * Pastikan pembayaran berasal dari cabang admin yang sedang login.
     * Mencegah admin satu cabang mengkonfirmasi transaksi cabang lain.
     */
    private function validateCabangAccess(Pembayaran $pembayaran): void
    {
        $cabangId = $this->resolveCabangId();

        if (!empty($pembayaran->id_booking)) {
            $booking = BookingServis::find($pembayaran->id_booking);
            if (!$booking || (int) $booking->id_cabang !== $cabangId) {
                abort(403, 'Anda tidak berwenang mengkonfirmasi pembayaran dari cabang lain.');
            }
        }

        if (!empty($pembayaran->id_home_service)) {
            $home = HomeServis::find($pembayaran->id_home_service);
            if (!$home || (int) $home->id_cabang !== $cabangId) {
                abort(403, 'Anda tidak berwenang mengkonfirmasi pembayaran dari cabang lain.');
            }
        }
    }

    /**
     * Ambil cabang_id dari akun Admin Cabang yang sedang login.
     */
    private function resolveCabangId(): int
    {
        $user = \App\Models\User::findOrFail(session('user_id'));
        $cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;

        abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');

        return (int) $cabangId;
    }
}
