<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganPembayaranController extends Controller
{
    /**
     * Mengambil data pelanggan yang sedang login.
     */
    private function pelangganLogin()
    {
        return DB::table('pelanggan')
            ->where('email', session('user_email'))
            ->first();
    }

    /**
     * Halaman Pembayaran Saya.
     */
    public function index()
    {
        $pelanggan = $this->pelangganLogin();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        /*
         * Ambil booking milik pelanggan yang sudah selesai
         * atau sudah lunas.
         */
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
            ->whereIn('b.status', ['Completed', 'Paid'])
            ->select(
                'b.id_booking',
                'b.tanggal_booking',
                'b.waktu_booking',
                'b.status as status_booking',
                'b.catatan',
                'k.merk',
                'k.model',
                'k.nopol',
                'c.nama_cabang',
                'l.nama_layanan',
                'l.harga'
            )
            ->orderByDesc('b.id_booking')
            ->get();

        /*
         * Ambil pembayaran yang berhubungan dengan booking.
         */
        $pembayaran = DB::table('Pembayaran')
            ->whereIn(
                'id_booking',
                $bookings->pluck('id_booking')->all()
            )
            ->get()
            ->keyBy('id_booking');

        /*
         * Ambil metode pembayaran.
         */
        $metodePembayaran = DB::table('MetodePembayaran')
            ->orderBy('nama_metode')
            ->get();

        /*
         * Ringkasan transaksi.
         */
        $totalTransaksi = $pembayaran->count();

        $menungguPembayaran = $bookings->filter(function ($booking) use ($pembayaran) {
            $transaksi = $pembayaran->get($booking->id_booking);

            return $booking->status_booking === 'Completed'
                && (
                    !$transaksi
                    || in_array(
                        strtolower($transaksi->status ?? ''),
                        ['pending', 'menunggu']
                    )
                );
        })->count();

        $pembayaranBerhasil = $pembayaran->filter(function ($transaksi) {
            return in_array(
                strtolower($transaksi->status ?? ''),
                ['lunas', 'berhasil', 'paid']
            );
        })->count();

        /*
         * Kirim semua variabel yang diperlukan oleh halaman lama
         * maupun ringkasan pembayaran.
         */
        return view('pelanggan.pembayaran.index', compact(
            'pelanggan',
            'bookings',
            'pembayaran',
            'metodePembayaran',
            'totalTransaksi',
            'menungguPembayaran',
            'pembayaranBerhasil'
        ));
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

    if ($pembayaran && strtolower($pembayaran->status) === 'lunas') {
        return redirect('/order-pelanggan')
            ->with('success', 'Pesanan ini sudah lunas.');
    }

    $metodePembayaran = DB::table('MetodePembayaran')
        ->orderBy('nama_metode')
        ->get();

    return view('pelanggan.pembayaran-sparepart', compact(
        'order',
        'metodePembayaran',
        'pembayaran'
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

    $pembayaranLama = DB::table('Pembayaran')
        ->where('id_order', $id_order)
        ->first();

    if ($pembayaranLama && strtolower($pembayaranLama->status) === 'lunas') {
        return redirect('/order-pelanggan')
            ->with('success', 'Pesanan ini sudah lunas.');
    }

    $request->validate([
        'metode_pembayaran_id' => 'required|exists:MetodePembayaran,id_metode',
    ]);

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
