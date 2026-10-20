<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PelangganPembayaranController extends Controller
{
    private function pelangganLogin()
    {
        return DB::table('pelanggan')
            ->where('email', session('user_email'))
            ->first();
    }

    public function index()
    {
        $pelanggan = $this->pelangganLogin();
        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $bookings = DB::table('bookingservis as b')
            ->leftJoin('kendaraan as k', 'b.id_kendaraan', '=', 'k.id_kendaraan')
            ->leftJoin('cabang as c', 'b.id_cabang', '=', 'c.id_cabang')
            ->leftJoin('layananservis as l', 'b.layanan_id', '=', 'l.id_layanan')
            ->where('b.id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn('b.status', ['Completed', 'Paid'])
            ->select(
                'b.id_booking', 'b.tanggal_booking', 'b.waktu_booking',
                'b.status as status_booking', 'b.catatan', 'k.merk', 'k.model',
                'k.nopol', 'c.nama_cabang', 'l.nama_layanan', 'l.harga'
            )
            ->orderByDesc('b.id_booking')
            ->get();

        $pembayaran = DB::table('Pembayaran')
            ->whereIn('id_booking', $bookings->pluck('id_booking')->all())
            ->get()
            ->keyBy('id_booking');

        $metodePembayaran = DB::table('MetodePembayaran')
            ->orderBy('nama_metode')
            ->get();

        $totalTransaksi = $pembayaran->count();

        $menungguPembayaran = $bookings->filter(function ($booking) use ($pembayaran) {
            $transaksi = $pembayaran->get($booking->id_booking);
            return strtolower(trim((string) $booking->status_booking)) === 'completed'
                && (!$transaksi || in_array(
                    strtolower(trim((string) ($transaksi->status ?? ''))),
                    ['pending', 'menunggu'],
                    true
                ));
        })->count();

        $pembayaranBerhasil = $pembayaran->filter(function ($transaksi) {
            return in_array(
                strtolower(trim((string) ($transaksi->status ?? ''))),
                ['lunas', 'berhasil', 'paid'],
                true
            );
        })->count();

        return view('pelanggan.pembayaran.index', compact(
            'pelanggan', 'bookings', 'pembayaran', 'metodePembayaran',
            'totalTransaksi', 'menungguPembayaran', 'pembayaranBerhasil'
        ));
    }

    /**
     * Menyimpan pengajuan pembayaran Booking Servis pelanggan.
     */
    public function store(Request $request, $id_booking)
    {
        $pelanggan = $this->pelangganLogin();
        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $data = $request->validate([
            'metode_pembayaran_id' => 'required|integer|exists:MetodePembayaran,id_metode',
        ]);

        $hasil = DB::transaction(function () use ($pelanggan, $id_booking, $data) {
            $booking = DB::table('bookingservis')
                ->where('id_booking', $id_booking)
                ->where('id_pelanggan', $pelanggan->id_pelanggan)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                abort(404, 'Booking tidak ditemukan.');
            }

            if (!in_array(strtolower(trim((string) $booking->status)), ['completed', 'paid'], true)) {
                throw ValidationException::withMessages([
                    'id_booking' => 'Pembayaran hanya dapat diajukan setelah servis selesai.',
                ]);
            }

            // Harga diambil dari database, bukan dari input pelanggan.
            $layanan = DB::table('bookingservis as b')
                ->leftJoin('layananservis as l', 'b.layanan_id', '=', 'l.id_layanan')
                ->where('b.id_booking', $booking->id_booking)
                ->select('l.harga')
                ->first();

            $jumlah = (float) ($layanan->harga ?? 0);
            if ($jumlah <= 0) {
                throw ValidationException::withMessages([
                    'metode_pembayaran_id' => 'Harga layanan tidak ditemukan atau tidak valid. Hubungi petugas bengkel.',
                ]);
            }

            $pembayaranLama = DB::table('Pembayaran')
                ->where('id_booking', $booking->id_booking)
                ->lockForUpdate()
                ->first();

            if ($pembayaranLama) {
                $statusLama = strtolower(trim((string) $pembayaranLama->status));

                if (in_array($statusLama, ['lunas', 'berhasil', 'paid'], true)) {
                    return 'sudah_lunas';
                }

                DB::table('Pembayaran')
                    ->where('id_pembayaran', $pembayaranLama->id_pembayaran)
                    ->update([
                        'id_home_service' => null,
                        'id_order' => null,
                        'id_promo' => null,
                        'metode_pembayaran_id' => $data['metode_pembayaran_id'],
                        'jumlah' => $jumlah,
                        'status' => 'menunggu',
                        'tanggal_bayar' => null,
                        'bukti_pembayaran' => null,
                    ]);
            } else {
                DB::table('Pembayaran')->insert([
                    'id_booking' => $booking->id_booking,
                    'id_home_service' => null,
                    'id_order' => null,
                    'id_promo' => null,
                    'metode_pembayaran_id' => $data['metode_pembayaran_id'],
                    'jumlah' => $jumlah,
                    'status' => 'menunggu',
                    'tanggal_bayar' => null,
                    'bukti_pembayaran' => null,
                ]);
            }

            return 'berhasil';
        });

        if ($hasil === 'sudah_lunas') {
            return redirect('/pembayaran-pelanggan')
                ->with('info', 'Booking ini sudah lunas. Tidak ada pembayaran baru yang dibuat.');
        }

        return redirect('/pembayaran-pelanggan')
            ->with('success', 'Pengajuan pembayaran berhasil dicatat. Silakan tunggu konfirmasi petugas bengkel.');
    }

    /**
     * Form pembayaran pesanan spare part pelanggan.
     */
    public function formSparePart($id_order)
    {
        $pelanggan = $this->pelangganLogin();
        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $order = DB::table('ordersparepart')
            ->where('id_order', $id_order)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        $pembayaran = DB::table('Pembayaran')
            ->where('id_order', $id_order)
            ->first();

        if ($pembayaran && in_array(
            strtolower(trim((string) $pembayaran->status)),
            ['lunas', 'berhasil', 'paid'],
            true
        )) {
            return redirect('/order-pelanggan')->with('success', 'Pesanan ini sudah lunas.');
        }

        $metodePembayaran = DB::table('MetodePembayaran')
            ->orderBy('nama_metode')
            ->get();

        return view('pelanggan.pembayaran-sparepart', compact(
            'order', 'metodePembayaran', 'pembayaran'
        ));
    }

    /**
     * Menyimpan pengajuan pembayaran spare part.
     */
    public function storeSparePart(Request $request, $id_order)
    {
        $pelanggan = $this->pelangganLogin();
        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $order = DB::table('ordersparepart')
            ->where('id_order', $id_order)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        $request->validate([
            'metode_pembayaran_id' => 'required|integer|exists:MetodePembayaran,id_metode',
        ]);

        $pembayaranLama = DB::table('Pembayaran')
            ->where('id_order', $id_order)
            ->first();

        if ($pembayaranLama && in_array(
            strtolower(trim((string) $pembayaranLama->status)),
            ['lunas', 'berhasil', 'paid'],
            true
        )) {
            return redirect('/order-pelanggan')->with('success', 'Pesanan ini sudah lunas.');
        }

        $data = [
            'id_booking' => null,
            'id_home_service' => null,
            'id_order' => $order->id_order,
            'id_promo' => null,
            'metode_pembayaran_id' => $request->metode_pembayaran_id,
            'jumlah' => $order->total_harga,
            'status' => 'menunggu',
            'tanggal_bayar' => null,
            'bukti_pembayaran' => null,
        ];

        if ($pembayaranLama) {
            DB::table('Pembayaran')
                ->where('id_pembayaran', $pembayaranLama->id_pembayaran)
                ->update($data);
        } else {
            DB::table('Pembayaran')->insert($data);
        }

        return redirect('/order-pelanggan')
            ->with('success', 'Pengajuan pembayaran berhasil. Silakan tunggu verifikasi petugas.');
    }
}
