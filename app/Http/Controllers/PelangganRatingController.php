<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Pelanggan;
use App\Models\BookingServis;
use App\Models\OrderSparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganRatingController extends Controller
{
    private function pelangganLogin()
    {
        return Pelanggan::where(
            'email',
            session('user_email')
        )->firstOrFail();
    }

    public function index()
    {
        $pelanggan = $this->pelangganLogin();

        $rating = Rating::with([
            'bookingServis',
            'orderSparePart'
        ])
        ->where('id_pelanggan', $pelanggan->id_pelanggan)
        ->orderByDesc('tanggal')
        ->get();

        // Booking Completed atau Paid dapat diberi rating.
        $booking = BookingServis::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )
        ->whereIn(
            DB::raw('LOWER(status)'),
            ['completed', 'paid']
        )
        ->whereNotIn(
            'id_booking',
            Rating::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->whereNotNull('id_booking')
                ->select('id_booking')
        )
        ->orderByDesc('tanggal_booking')
        ->get();

        // Pesanan spare part yang sudah selesai juga dapat dinilai.
        $orders = OrderSparePart::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )
        ->whereIn(
            DB::raw('LOWER(status)'),
            ['completed', 'delivered']
        )
        ->whereNotIn(
            'id_order',
            Rating::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->whereNotNull('id_order')
                ->select('id_order')
        )
        ->orderByDesc('tanggal_order')
        ->get();

        return view('pelanggan.rating.index', compact(
            'pelanggan',
            'rating',
            'booking',
            'orders'
        ));
    }

    public function store(Request $request)
    {
        $pelanggan = $this->pelangganLogin();

        $request->validate([
            'jenis_transaksi' => 'required|in:booking,order',
            'id_transaksi' => 'required|integer',
            'nilai' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        $idBooking = null;
        $idOrder = null;

        if ($request->jenis_transaksi === 'booking') {
            $transaksi = BookingServis::where(
                'id_booking',
                $request->id_transaksi
            )
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn(
                DB::raw('LOWER(status)'),
                ['completed', 'paid']
            )
            ->first();

            if (!$transaksi) {
                return back()->withInput()->with(
                    'error',
                    'Booking tidak ditemukan atau servis belum selesai.'
                );
            }

            $idBooking = $transaksi->id_booking;

            $sudahDinilai = Rating::where(
                'id_pelanggan',
                $pelanggan->id_pelanggan
            )
            ->where('id_booking', $idBooking)
            ->exists();

        } else {
            $transaksi = OrderSparePart::where(
                'id_order',
                $request->id_transaksi
            )
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn(
                DB::raw('LOWER(status)'),
                ['completed', 'delivered']
            )
            ->first();

            if (!$transaksi) {
                return back()->withInput()->with(
                    'error',
                    'Pesanan tidak ditemukan atau belum selesai.'
                );
            }

            $idOrder = $transaksi->id_order;

            $sudahDinilai = Rating::where(
                'id_pelanggan',
                $pelanggan->id_pelanggan
            )
            ->where('id_order', $idOrder)
            ->exists();
        }

        if ($sudahDinilai) {
            return back()->with(
                'error',
                'Transaksi ini sudah memiliki rating.'
            );
        }

        Rating::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_booking' => $idBooking,
            'id_order' => $idOrder,
            'nilai' => $request->nilai,
            'ulasan' => $request->ulasan,
            'tanggal' => now(),
        ]);

        return redirect('/rating-pelanggan')->with(
            'success',
            'Terima kasih! Rating dan ulasan kamu berhasil disimpan.'
        );
    }

    public function destroy($id)
    {
        $pelanggan = $this->pelangganLogin();

        $rating = Rating::where(
            'id_rating',
            $id
        )
        ->where('id_pelanggan', $pelanggan->id_pelanggan)
        ->firstOrFail();

        $rating->delete();

        return redirect('/rating-pelanggan')->with(
            'success',
            'Rating berhasil dihapus.'
        );
    }
}
