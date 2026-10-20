<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPembayaranController extends Controller
{
    /**
     * Menampilkan halaman laporan pembayaran.
     */
    public function index(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        $query = Pembayaran::with([
            'bookingServis.pelanggan',
            'orderSparePart.pelanggan',
            'metodePembayaran',
            'promo',
        ]);

        if ($tanggalMulai) {
            $query->whereDate('tanggal_bayar', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('tanggal_bayar', '<=', $tanggalSelesai);
        }

        $pembayaran = $query
            ->orderByDesc('tanggal_bayar')
            ->orderByDesc('id_pembayaran')
            ->get();

        $totalTransaksi = $pembayaran->count();

        $totalLunas = $pembayaran
            ->where('status', 'lunas')
            ->sum('jumlah');

        return view('laporan-pembayaran.index', compact(
            'pembayaran',
            'tanggalMulai',
            'tanggalSelesai',
            'totalTransaksi',
            'totalLunas'
        ));
    }

    /**
     * Mengunduh laporan pembayaran dalam bentuk PDF.
     */
    public function download(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        $query = Pembayaran::with([
            'bookingServis.pelanggan',
            'orderSparePart.pelanggan',
            'metodePembayaran',
            'promo',
        ]);

        if ($tanggalMulai) {
            $query->whereDate('tanggal_bayar', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('tanggal_bayar', '<=', $tanggalSelesai);
        }

        $pembayaran = $query
            ->orderByDesc('tanggal_bayar')
            ->orderByDesc('id_pembayaran')
            ->get();

        $totalTransaksi = $pembayaran->count();

        $totalLunas = $pembayaran
            ->where('status', 'lunas')
            ->sum('jumlah');

        $pdf = Pdf::loadView('laporan-pembayaran.pdf', compact(
            'pembayaran',
            'tanggalMulai',
            'tanggalSelesai',
            'totalTransaksi',
            'totalLunas'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pembayaran.pdf');
    }
}
