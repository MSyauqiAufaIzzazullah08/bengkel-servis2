<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\OrderSparePart;
use App\Models\Ekspedisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = Pengiriman::with([
            'orderSparePart.pelanggan',
            'ekspedisi'
        ])->get();

        return view('pengiriman.index', compact('pengiriman'));
    }

    public function create()
    {
        // Hanya pesanan Diproses yang belum memiliki pengiriman.
        $orders = OrderSparePart::with('pelanggan')
            ->where('status', 'Diproses')
            ->whereDoesntHave('pengiriman')
            ->get();

        $ekspedisi = Ekspedisi::all();

        return view('pengiriman.create', compact(
            'orders',
            'ekspedisi'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_order' => 'required|exists:ordersparepart,id_order',
            'id_ekspedisi' => 'required|exists:ekspedisi,id_ekspedisi',
            'kurir' => 'required|string|max:255',
            'no_resi' => 'required|string|max:255',
            'status' => 'required|in:pending,dikirim',
            'estimasi_tiba' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated) {
            $order = OrderSparePart::where(
                'id_order',
                $validated['id_order']
            )->lockForUpdate()->firstOrFail();

            if ($order->status !== 'Diproses') {
                throw ValidationException::withMessages([
                    'id_order' => 'Hanya pesanan berstatus Diproses yang dapat dikirim.',
                ]);
            }

            if ($order->pengiriman()->exists()) {
                throw ValidationException::withMessages([
                    'id_order' => 'Pesanan ini sudah memiliki data pengiriman.',
                ]);
            }

            $statusPengiriman = strtolower($validated['status']);

            // Pembayaran harus lunas sebelum barang dikirim.
            if ($statusPengiriman === 'dikirim') {
                $lunas = DB::table('Pembayaran')
                    ->where('id_order', $order->id_order)
                    ->whereRaw('LOWER(status) = ?', ['lunas'])
                    ->exists();

                if (!$lunas) {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan harus lunas sebelum dikirim.',
                    ]);
                }

                if (empty($validated['tanggal_kirim'])) {
                    $validated['tanggal_kirim'] = now()->toDateString();
                }
            }

            Pengiriman::create($validated);

            if ($statusPengiriman === 'dikirim') {
                $order->status = 'Dikirim';
                $order->save();
            }
        });

        return redirect('/pengiriman')
            ->with('success', 'Data pengiriman berhasil disimpan.');
    }

    public function edit($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        $orders = OrderSparePart::with('pelanggan')->get();
        $ekspedisi = Ekspedisi::all();

        return view('pengiriman.edit', compact(
            'pengiriman',
            'orders',
            'ekspedisi'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_order' => 'required|exists:ordersparepart,id_order',
            'id_ekspedisi' => 'required|exists:ekspedisi,id_ekspedisi',
            'kurir' => 'required|string|max:255',
            'no_resi' => 'required|string|max:255',
            'status' => 'required|in:pending,dikirim,tiba',
            'estimasi_tiba' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $pengiriman = Pengiriman::where(
                'id_pengiriman',
                $id
            )->lockForUpdate()->firstOrFail();

            // Satu pengiriman tidak boleh dipindahkan ke pesanan lain.
            if ((int) $validated['id_order'] !== (int) $pengiriman->id_order) {
                throw ValidationException::withMessages([
                    'id_order' => 'Pengiriman tidak boleh dipindahkan ke pesanan lain.',
                ]);
            }

            $order = OrderSparePart::where(
                'id_order',
                $pengiriman->id_order
            )->lockForUpdate()->firstOrFail();

            $statusLama = strtolower($pengiriman->status);
            $statusBaru = strtolower($validated['status']);

            if ($statusLama === $statusBaru) {
                $pengiriman->update($validated);
                return;
            }

            // Pengiriman hanya boleh maju: pending -> dikirim -> tiba.
            $alur = [
                'pending' => ['dikirim'],
                'dikirim' => ['tiba'],
                'tiba' => [],
            ];

            if (!in_array($statusBaru, $alur[$statusLama] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status pengiriman tidak diizinkan.',
                ]);
            }

            if ($statusBaru === 'dikirim') {
                if ($order->status !== 'Diproses') {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan harus berstatus Diproses sebelum dikirim.',
                    ]);
                }

                $lunas = DB::table('Pembayaran')
                    ->where('id_order', $order->id_order)
                    ->whereRaw('LOWER(status) = ?', ['lunas'])
                    ->exists();

                if (!$lunas) {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan harus lunas sebelum dikirim.',
                    ]);
                }

                if (empty($validated['tanggal_kirim'])) {
                    $validated['tanggal_kirim'] = now()->toDateString();
                }

                $order->status = 'Dikirim';
                $order->save();
            }

            if ($statusBaru === 'tiba') {
                if ($order->status !== 'Dikirim') {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan harus berstatus Dikirim sebelum dinyatakan selesai.',
                    ]);
                }

                $order->status = 'Selesai';
                $order->save();
            }

            $pengiriman->update($validated);
        });

        return redirect('/pengiriman')
            ->with('success', 'Status pengiriman dan pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pengiriman = Pengiriman::where(
                'id_pengiriman',
                $id
            )->lockForUpdate()->firstOrFail();

            // Data pengiriman aktif atau selesai tidak boleh dihapus.
            if (strtolower($pengiriman->status) !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya data pengiriman berstatus Pending yang dapat dihapus.',
                ]);
            }

            $order = OrderSparePart::where(
                'id_order',
                $pengiriman->id_order
            )->lockForUpdate()->firstOrFail();

            if ($order->status !== 'Diproses') {
                throw ValidationException::withMessages([
                    'status' => 'Pengiriman tidak dapat dihapus karena status pesanan sudah berubah.',
                ]);
            }

            $pengiriman->delete();
        });

        return redirect('/pengiriman')
            ->with('success', 'Data pengiriman berhasil dihapus.');
    }
}
