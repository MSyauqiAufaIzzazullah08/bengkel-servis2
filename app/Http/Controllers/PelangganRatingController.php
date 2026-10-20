<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Pelanggan;
use App\Models\BookingServis;
use App\Models\HomeServis;
use App\Models\OrderSparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            'homeService',
            'orderSparePart',
        ])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderByDesc('tanggal')
            ->get();

        $ratingPelanggan = Rating::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        );

        // Booking yang sudah selesai dan belum dinilai.
        $booking = BookingServis::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )
            ->whereIn(DB::raw('LOWER(status)'), ['completed', 'paid'])
            ->whereNotIn(
                'id_booking',
                (clone $ratingPelanggan)
                    ->whereNotNull('id_booking')
                    ->select('id_booking')
            )
            ->orderByDesc('tanggal_booking')
            ->get();

        // Home Service yang sudah selesai dan belum dinilai.
        $homeServices = HomeServis::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )
            ->whereIn(DB::raw('LOWER(status)'), ['completed', 'paid'])
            ->whereNotIn(
                'id_home_service',
                (clone $ratingPelanggan)
                    ->whereNotNull('id_home_service')
                    ->select('id_home_service')
            )
            ->orderByDesc('tanggal')
            ->get();

        // Pesanan Spare Part yang sudah selesai dan belum dinilai.
        $orders = OrderSparePart::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )
            ->whereIn(
                DB::raw('LOWER(status)'),
                ['selesai', 'completed', 'delivered']
            )
            ->whereNotIn(
                'id_order',
                (clone $ratingPelanggan)
                    ->whereNotNull('id_order')
                    ->select('id_order')
            )
            ->orderByDesc('tanggal_order')
            ->get();

        return view('pelanggan.rating.index', compact(
            'pelanggan',
            'rating',
            'booking',
            'homeServices',
            'orders'
        ));
    }

    public function store(Request $request)
    {
        $pelanggan = $this->pelangganLogin();

        $request->validate([
            'jenis_transaksi' => 'required|in:booking,home_service,order',
            'id_transaksi' => 'required|integer',
            'nilai' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        $idBooking = null;
        $idHomeService = null;
        $idOrder = null;

        if ($request->jenis_transaksi === 'booking') {
            $transaksi = BookingServis::where(
                'id_booking',
                $request->id_transaksi
            )
                ->where('id_pelanggan', $pelanggan->id_pelanggan)
                ->whereIn(DB::raw('LOWER(status)'), ['completed', 'paid'])
                ->first();

            if (!$transaksi) {
                throw ValidationException::withMessages([
                    'id_transaksi' =>
                        'Booking tidak ditemukan atau servis belum selesai.',
                ]);
            }

            $idBooking = $transaksi->id_booking;

            $sudahDinilai = Rating::where(
                'id_pelanggan',
                $pelanggan->id_pelanggan
            )
                ->where('id_booking', $idBooking)
                ->exists();
        } elseif ($request->jenis_transaksi === 'home_service') {
            $transaksi = HomeServis::where(
                'id_home_service',
                $request->id_transaksi
            )
                ->where('id_pelanggan', $pelanggan->id_pelanggan)
                ->whereIn(DB::raw('LOWER(status)'), ['completed', 'paid'])
                ->first();

            if (!$transaksi) {
                throw ValidationException::withMessages([
                    'id_transaksi' =>
                        'Home Service tidak ditemukan atau belum selesai.',
                ]);
            }

            $idHomeService = $transaksi->id_home_service;

            $sudahDinilai = Rating::where(
                'id_pelanggan',
                $pelanggan->id_pelanggan
            )
                ->where('id_home_service', $idHomeService)
                ->exists();
        } else {
            $transaksi = OrderSparePart::where(
                'id_order',
                $request->id_transaksi
            )
                ->where('id_pelanggan', $pelanggan->id_pelanggan)
                ->whereIn(
                    DB::raw('LOWER(status)'),
                    ['selesai', 'completed', 'delivered']
                )
                ->first();

            if (!$transaksi) {
                throw ValidationException::withMessages([
                    'id_transaksi' =>
                        'Pesanan Spare Part tidak ditemukan atau belum selesai.',
                ]);
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
            throw ValidationException::withMessages([
                'id_transaksi' => 'Transaksi ini sudah memiliki rating.',
            ]);
        }

        Rating::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_booking' => $idBooking,
            'id_home_service' => $idHomeService,
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
