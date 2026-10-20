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
        $validated = $request->validate([
            'status' => [
                'required',
                'in:Pending,Diproses,Dikirim,Selesai,Dibatalkan',
            ],
        ]);

        $statusBaru = $validated['status'];

        DB::transaction(function () use ($id, $statusBaru) {
            $order = OrderSparePart::with('orderDetail')
                ->where('id_order', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $statusLama = $order->status;

            // Status yang sama tidak memproses stok kembali.
            if ($statusLama === $statusBaru) {
                return;
            }

            // Petugas Spare Part tidak boleh mengatur
            // status Dikirim atau Selesai secara langsung.
            $alurStatus = [
                'Pending' => ['Diproses', 'Dibatalkan'],
                'Diproses' => ['Dibatalkan'],
                'Dikirim' => [],
                'Selesai' => [],
                'Dibatalkan' => [],
            ];

            if (!in_array(
                $statusBaru,
                $alurStatus[$statusLama] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' =>
                        'Perubahan status tidak diizinkan. '
                        . 'Status Dikirim dan Selesai harus '
                        . 'diperbarui melalui proses pengiriman.',
                ]);
            }

            // Pending -> Diproses:
            // periksa seluruh barang sebelum mengurangi stok.
            if (
                $statusLama === 'Pending'
                && $statusBaru === 'Diproses'
            ) {
                if ($order->orderDetail->isEmpty()) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Pesanan belum memiliki rincian barang.',
                    ]);
                }

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
                            'status' =>
                                'Spare part pada pesanan tidak ditemukan.',
                        ]);
                    }

                    $jumlah = (int) $detail->jumlah;

                    if ($jumlah < 1 || $sparepart->stok < $jumlah) {
                        throw ValidationException::withMessages([
                            'status' =>
                                'Stok ' . $sparepart->nama
                                . ' tidak mencukupi. Stok tersedia: '
                                . $sparepart->stok,
                        ]);
                    }

                    $barangPesanan[] = [
                        'sparepart' => $sparepart,
                        'jumlah' => $jumlah,
                    ];
                }

                // Kurangi stok hanya setelah semua barang lolos.
                foreach ($barangPesanan as $barang) {
                    $barang['sparepart']->decrement(
                        'stok',
                        $barang['jumlah']
                    );
                }
            }

            // Diproses -> Dibatalkan:
            // jika sudah ada data pengiriman, selesaikan
            // atau hapus pengiriman Pending terlebih dahulu.
            if (
                $statusLama === 'Diproses'
                && $statusBaru === 'Dibatalkan'
            ) {
                if ($order->pengiriman()->exists()) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Pesanan sudah memiliki data pengiriman. '
                            . 'Hapus data pengiriman yang masih Pending '
                            . 'terlebih dahulu sebelum membatalkan pesanan.',
                    ]);
                }

                foreach ($order->orderDetail as $detail) {
                    $sparepart = SparePart::where(
                        'id_sparepart',
                        $detail->id_sparepart
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$sparepart) {
                        throw ValidationException::withMessages([
                            'status' =>
                                'Spare part tidak ditemukan. '
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
            ->with(
                'success',
                'Status pesanan berhasil diperbarui.'
            );
    }
}
