<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\BookingServis;
use App\Models\OrderSparePart;
use App\Models\MetodePembayaran;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with([
            'bookingServis.pelanggan',
            'orderSparePart.pelanggan',
            'metodePembayaran',
            'promo',
        ])->get();

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        // Hanya booking yang servisnya sudah selesai
        // dan belum mempunyai pembayaran yang lunas.
        $booking = BookingServis::with([
            'pelanggan',
            'layananServis',
        ])
            ->where('status', 'Completed')
            ->whereDoesntHave('pembayaran', function ($query) {
                $query->where('status', 'lunas');
            })
            ->get();

        $orders = OrderSparePart::with('pelanggan')->get();
        $metode = MetodePembayaran::all();
        $promo = Promo::all();

        return view('pembayaran.create', compact(
            'booking',
            'orders',
            'metode',
            'promo'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validasiPembayaran($request);

        DB::transaction(function () use (&$data) {
            $this->cekSumberTransaksi($data);

            $data['jumlah'] = $this->hitungJumlahBayar($data);

            if ($data['status'] === 'lunas' && empty($data['tanggal_bayar'])) {
                $data['tanggal_bayar'] = now()->toDateString();
            }

            $pembayaran = Pembayaran::create($data);

            if (
                $pembayaran->status === 'lunas' &&
                !empty($pembayaran->id_booking)
            ) {
                BookingServis::where('id_booking', $pembayaran->id_booking)
                    ->update(['status' => 'Paid']);
            }
        });

        return redirect('/pembayaran')
            ->with('success', 'Data pembayaran berhasil disimpan.');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $booking = BookingServis::with([
            'pelanggan',
            'layananServis',
        ])->get();

        $orders = OrderSparePart::with('pelanggan')->get();
        $metode = MetodePembayaran::all();
        $promo = Promo::all();

        return view('pembayaran.edit', compact(
            'pembayaran',
            'booking',
            'orders',
            'metode',
            'promo'
        ));
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $pembayaran = Pembayaran::where(
                'id_pembayaran',
                $id
            )->lockForUpdate()->firstOrFail();

            $data = $this->validasiPembayaran($request);

            // Pembayaran yang sudah lunas tidak boleh
            // diubah kembali menjadi menunggu atau gagal.
            if (
                $pembayaran->status === 'lunas' &&
                $data['status'] !== 'lunas'
            ) {
                throw ValidationException::withMessages([
                    'status' => 'Pembayaran yang sudah lunas tidak dapat dibatalkan melalui formulir ini.',
                ]);
            }

            $this->cekSumberTransaksi($data, $pembayaran->id_pembayaran);

            $data['jumlah'] = $this->hitungJumlahBayar(
                $data,
                $pembayaran->id_pembayaran
            );

            if ($data['status'] === 'lunas' && empty($data['tanggal_bayar'])) {
                $data['tanggal_bayar'] = now()->toDateString();
            }

            $pembayaran->update($data);

            if (!empty($pembayaran->id_booking)) {
                BookingServis::where(
                    'id_booking',
                    $pembayaran->id_booking
                )->update([
                    'status' => $pembayaran->status === 'lunas'
                        ? 'Paid'
                        : 'Completed',
                ]);
            }
        });

        return redirect('/pembayaran')
            ->with('success', 'Data pembayaran berhasil diubah.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->status === 'lunas') {
            throw ValidationException::withMessages([
                'status' => 'Pembayaran lunas tidak boleh dihapus melalui menu ini.',
            ]);
        }

        $pembayaran->delete();

        return redirect('/pembayaran')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }

    private function validasiPembayaran(Request $request): array
    {
        $data = $request->validate([
            'id_booking' => 'nullable|integer|exists:bookingservis,id_booking',
            'id_order' => 'nullable|integer|exists:OrderSparePart,id_order',
            'id_promo' => 'nullable|integer|exists:Promo,id_promo',
            'metode_pembayaran_id' => 'required|integer|exists:MetodePembayaran,id_metode',
            'status' => 'required|in:menunggu,lunas,gagal',
            'tanggal_bayar' => 'nullable|date',
            'bukti_pembayaran' => 'nullable|string|max:255',
        ]);

        if (empty($data['id_booking']) && empty($data['id_order'])) {
            throw ValidationException::withMessages([
                'id_booking' => 'Pilih booking servis atau order spare part.',
            ]);
        }

        if (!empty($data['id_booking']) && !empty($data['id_order'])) {
            throw ValidationException::withMessages([
                'id_order' => 'Pembayaran hanya boleh terkait satu transaksi.',
            ]);
        }

        return $data;
    }

    private function cekSumberTransaksi(
        array $data,
        ?int $idPembayaranSaatIni = null
    ): void {
        if (!empty($data['id_booking'])) {
            $booking = BookingServis::where(
                'id_booking',
                $data['id_booking']
            )->lockForUpdate()->firstOrFail();

            if (
                !in_array($booking->status, ['Completed', 'Paid'], true)
            ) {
                throw ValidationException::withMessages([
                    'id_booking' => 'Booking hanya dapat dibayar setelah servis selesai.',
                ]);
            }

            $pembayaranLain = Pembayaran::where(
                'id_booking',
                $booking->id_booking
            )
                ->where('status', 'lunas');

            if ($idPembayaranSaatIni !== null) {
                $pembayaranLain->where(
                    'id_pembayaran',
                    '!=',
                    $idPembayaranSaatIni
                );
            }

            if ($pembayaranLain->exists()) {
                throw ValidationException::withMessages([
                    'id_booking' => 'Booking ini sudah memiliki pembayaran lunas.',
                ]);
            }
        }

        if (!empty($data['id_order'])) {
            $order = OrderSparePart::where(
                'id_order',
                $data['id_order']
            )->lockForUpdate()->firstOrFail();

            $pembayaranLain = Pembayaran::where(
                'id_order',
                $order->id_order
            )
                ->where('status', 'lunas');

            if ($idPembayaranSaatIni !== null) {
                $pembayaranLain->where(
                    'id_pembayaran',
                    '!=',
                    $idPembayaranSaatIni
                );
            }

            if ($pembayaranLain->exists()) {
                throw ValidationException::withMessages([
                    'id_order' => 'Order ini sudah memiliki pembayaran lunas.',
                ]);
            }
        }
    }

    private function hitungJumlahBayar(
        array $data,
        ?int $idPembayaranSaatIni = null
    ): float {
        // Ambil nilai transaksi dari database, bukan dari input form.
        if (!empty($data['id_booking'])) {
            $booking = BookingServis::with('layananServis')
                ->findOrFail($data['id_booking']);

            $hargaDasar = (float) ($booking->layananServis->harga ?? 0);
        } else {
            $order = OrderSparePart::findOrFail($data['id_order']);
            $hargaDasar = (float) $order->total_harga;
        }

        if ($hargaDasar <= 0) {
            throw ValidationException::withMessages([
                'jumlah' => 'Total transaksi harus lebih besar dari nol.',
            ]);
        }

        if (empty($data['id_promo'])) {
            return round($hargaDasar, 2);
        }

        $promo = Promo::where(
            'id_promo',
            $data['id_promo']
        )->lockForUpdate()->firstOrFail();

        $hariIni = now()->toDateString();

        if (
            ($promo->periode_mulai && $hariIni < $promo->periode_mulai) ||
            ($promo->periode_selesai && $hariIni > $promo->periode_selesai)
        ) {
            throw ValidationException::withMessages([
                'id_promo' => 'Promo belum berlaku atau sudah kedaluwarsa.',
            ]);
        }

        if ($hargaDasar < (float) $promo->minimal_transaksi) {
            throw ValidationException::withMessages([
                'id_promo' => 'Total transaksi belum memenuhi minimal penggunaan promo.',
            ]);
        }

        // Kuota dihitung berdasarkan pembayaran yang sudah lunas.
        $promoTerpakai = Pembayaran::where(
            'id_promo',
            $promo->id_promo
        )
            ->where('status', 'lunas');

        if ($idPembayaranSaatIni !== null) {
            $promoTerpakai->where(
                'id_pembayaran',
                '!=',
                $idPembayaranSaatIni
            );
        }

        if (
            $promo->kuota !== null &&
            $promoTerpakai->count() >= (int) $promo->kuota
        ) {
            throw ValidationException::withMessages([
                'id_promo' => 'Kuota promo sudah habis.',
            ]);
        }

        $jenisDiskon = strtolower(trim($promo->jenis_diskon));
        $nilaiDiskon = (float) $promo->nilai_diskon;

        if ($jenisDiskon === 'persen') {
            $potongan = $hargaDasar * ($nilaiDiskon / 100);
        } elseif ($jenisDiskon === 'nominal') {
            $potongan = $nilaiDiskon;
        } else {
            throw ValidationException::withMessages([
                'id_promo' => 'Jenis diskon promo tidak dikenali.',
            ]);
        }

        // Harga akhir tidak boleh negatif.
        $potongan = min($potongan, $hargaDasar);

        return round($hargaDasar - $potongan, 2);
    }
}
