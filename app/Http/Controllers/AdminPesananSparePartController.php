<?php

namespace App\Http\Controllers;

use App\Models\OrderSparePart;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminPesananSparePartController extends Controller
{
    public function index()
    {
        $orders = OrderSparePart::with([
            'pelanggan',
            'orderDetail.sparePart',
            'pengiriman.ekspedisi',
            'pembayaran.metodePembayaran',
        ])
            ->orderByDesc('tanggal_order')
            ->get();

        $totalPesanan = $orders->count();

        $pesananPending = $orders
            ->where('status', 'Pending')
            ->count();

        $pesananDiproses = $orders
            ->where('status', 'Diproses')
            ->count();

        $pesananSelesai = $orders
            ->where('status', 'Selesai')
            ->count();

        return view('admin.pesanan-sparepart.index', compact(
            'orders',
            'totalPesanan',
            'pesananPending',
            'pesananDiproses',
            'pesananSelesai'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => [
                'required',
                'in:Pending,Diproses,Dikirim,Selesai,Dibatalkan',
            ],
        ]);

        $statusBaru = $request->status;

        DB::transaction(function () use ($id, $statusBaru) {
            $order = OrderSparePart::with('orderDetail')
                ->where('id_order', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $statusLama = $order->status;

            // Status yang sama tidak boleh mengurangi stok lagi.
            if ($statusLama === $statusBaru) {
                return;
            }

            // Aturan perpindahan status pesanan.
            $alurStatus = [
                'Pending' => ['Diproses', 'Dibatalkan'],
                'Diproses' => ['Dikirim', 'Dibatalkan'],
                'Dikirim' => ['Selesai'],
                'Selesai' => [],
                'Dibatalkan' => [],
            ];

            if (!in_array(
                $statusBaru,
                $alurStatus[$statusLama] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status pesanan tidak diizinkan.',
                ]);
            }

            // Saat mulai diproses, validasi dan kurangi stok.
            if (
                $statusLama === 'Pending'
                && $statusBaru === 'Diproses'
            ) {
                if ($order->orderDetail->isEmpty()) {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan belum memiliki rincian barang.',
                    ]);
                }

                // Kunci semua barang dan periksa stok terlebih dahulu.
                $barangPesanan = [];

                foreach ($order->orderDetail as $detail) {
                    $sparepart = SparePart::where(
                        'id_sparepart',
                        $detail->id_sparepart
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$sparepart) {
                        throw ValidationException::withMessages([
                            'status' => 'Spare part pada pesanan tidak ditemukan.',
                        ]);
                    }

                    $jumlah = (int) $detail->jumlah;

                    if ($jumlah < 1 || $sparepart->stok < $jumlah) {
                        throw ValidationException::withMessages([
                            'status' => 'Stok ' . $sparepart->nama
                                . ' tidak mencukupi. Stok tersedia: '
                                . $sparepart->stok,
                        ]);
                    }

                    $barangPesanan[] = [
                        'sparepart' => $sparepart,
                        'jumlah' => $jumlah,
                    ];
                }

                // Semua stok cukup, baru kurangi stok tiap barang.
                foreach ($barangPesanan as $barang) {
                    $barang['sparepart']->decrement(
                        'stok',
                        $barang['jumlah']
                    );
                }
            }

            // Jika pesanan yang sedang diproses dibatalkan,
            // kembalikan stok yang sebelumnya telah dikurangi.
            if (
                $statusLama === 'Diproses'
                && $statusBaru === 'Dibatalkan'
            ) {
                foreach ($order->orderDetail as $detail) {
                    $sparepart = SparePart::where(
                        'id_sparepart',
                        $detail->id_sparepart
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$sparepart) {
                        throw ValidationException::withMessages([
                            'status' => 'Spare part tidak ditemukan. '
                                . 'Pembatalan tidak dapat diproses.',
                        ]);
                    }

                    $sparepart->increment(
                        'stok',
                        (int) $detail->jumlah
                    );
                }
            }

            $order->status = $statusBaru;
            $order->save();
        });

        return redirect('/admin-pesanan-sparepart')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
