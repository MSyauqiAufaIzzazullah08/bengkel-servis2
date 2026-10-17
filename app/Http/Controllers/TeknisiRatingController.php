<?php

namespace App\Http\Controllers;

use App\Models\Teknisi;
use App\Models\Rating;

class TeknisiRatingController extends Controller
{
    public function index()
    {
        // Cari data teknisi berdasarkan akun yang sedang login.
        $teknisi = Teknisi::where(
            'user_id',
            session('user_id')
        )->firstOrFail();

        // Ambil ID booking yang memang ditugaskan kepada teknisi ini.
        $idBooking = $teknisi->penugasanTeknisi()
            ->pluck('id_booking')
            ->filter()
            ->unique()
            ->values();

        // Tampilkan rating hanya untuk booking milik teknisi ini.
        $rating = Rating::with([
            'pelanggan',
            'bookingServis.kendaraan',
            'bookingServis.layananServis',
        ])
            ->whereIn('id_booking', $idBooking)
            ->whereNotNull('id_booking')
            ->orderByDesc('tanggal')
            ->get();

        // Hitung rata-rata bintang dan jumlah ulasan.
        $totalRating = $rating->count();

        $rataRata = $totalRating > 0
            ? round($rating->avg('nilai'), 1)
            : 0;

        return view('teknisi.rating.index', compact(
            'teknisi',
            'rating',
            'totalRating',
            'rataRata'
        ));
    }
}
