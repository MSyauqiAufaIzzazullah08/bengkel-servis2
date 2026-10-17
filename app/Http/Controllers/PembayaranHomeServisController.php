<?php

namespace App\Http\Controllers;

use App\Models\HomeServis;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranHomeServisController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with([
            'homeServis.pelanggan',
            'homeServis.kendaraan',
            'homeServis.layananServis',
            'metodePembayaran',
            'promo',
        ])
        ->whereNotNull('id_home_service')
        ->orderByDesc('id_pembayaran')
        ->get();

        return view(
            'pembayaran-home-service.index',
            compact('pembayaran')
        );
    }

    public function create()
{
    $homeServis = HomeServis::with([
        'pelanggan',
        'kendaraan',
        'layananServis',
    ])
        ->where('status', 'Completed')
        ->whereDoesntHave('pembayaran', function ($query) {
            $query->where('status', 'lunas');
        })
        ->get();

    $metode = MetodePembayaran::all();
    $promo = Promo::all();

    return view(
        'pembayaran-home-service.create',
        compact('homeServis', 'metode', 'promo')
    );
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_home_service' => [
                'required',
                'integer',
                'exists:homeservice,id_home_service',
            ],
            'metode_pembayaran_id' => [
                'required',
                'integer',
                'exists:MetodePembayaran,id_metode',
            ],
            'id_promo' => [
                'nullable',
                'integer',
                'exists:Promo,id_promo',
            ],
            'status' => 'required|in:Pending,lunas',
            'tanggal_bayar' => 'nullable|date',
        ]);

        DB::transaction(function () use ($data) {
            $homeServis = HomeServis::where(
                'id_home_service',
                $data['id_home_service']
            )->lockForUpdate()->firstOrFail();

            if ($homeServis->status !== 'Completed') {
                throw ValidationException::withMessages([
                    'id_home_service' =>
                        'Home Service harus selesai sebelum dibayar.',
                ]);
            }

            $sudahLunas = Pembayaran::where(
                'id_home_service',
                $homeServis->id_home_service
            )
            ->where('status', 'lunas')
            ->exists();

            if ($sudahLunas) {
                throw ValidationException::withMessages([
                    'id_home_service' =>
                        'Home Service ini sudah memiliki pembayaran lunas.',
                ]);
            }

            $layanan = $homeServis->layananServis;

            if (!$layanan) {
                throw ValidationException::withMessages([
                    'id_home_service' =>
                        'Layanan servis belum dipilih. Lengkapi layanan pada data Home Service terlebih dahulu.',
                ]);
            }

            $harga = (float) $layanan->harga;
            $diskon = 0;

            if (!empty($data['id_promo'])) {
                $promo = Promo::findOrFail($data['id_promo']);

                $sekarang = now()->toDateString();

                if (
                    isset($promo->tanggal_mulai, $promo->tanggal_selesai) &&
                    (
                        $sekarang < $promo->tanggal_mulai ||
                        $sekarang > $promo->tanggal_selesai
                    )
                ) {
                    throw ValidationException::withMessages([
                        'id_promo' =>
                            'Promo tidak berlaku pada tanggal ini.',
                    ]);
                }

                $nilaiDiskon = (float) $promo->nilai_diskon;

                if ($nilaiDiskon < 0 || $nilaiDiskon > 100) {
                    throw ValidationException::withMessages([
                        'id_promo' =>
                            'Nilai diskon promo harus antara 0 sampai 100 persen.',
                    ]);
                }

                $diskon = $harga * ($nilaiDiskon / 100);
            }

            $jumlahBayar = max(0, $harga - $diskon);

            $pembayaran = Pembayaran::create([
                'id_booking' => null,
                'id_home_service' => $homeServis->id_home_service,
                'id_order' => null,
                'id_promo' => $data['id_promo'] ?? null,
                'metode_pembayaran_id' =>
                    $data['metode_pembayaran_id'],
                'jumlah' => $jumlahBayar,
                'status' => $data['status'],
                'tanggal_bayar' =>
                    $data['status'] === 'lunas'
                        ? ($data['tanggal_bayar'] ?? now())
                        : null,
            ]);
        });

        return redirect()
            ->route('pembayaran-home-service.index')
            ->with('success', 'Pembayaran Home Service berhasil disimpan.');
    }
}
