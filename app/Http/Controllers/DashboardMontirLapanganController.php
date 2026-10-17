<?php

namespace App\Http\Controllers;

use App\Models\MontirLapangan;
use App\Models\HomeServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardMontirLapanganController extends Controller
{
    /**
     * Dashboard montir lapangan.
     */
    public function index()
    {
        $montir = MontirLapangan::where(
            'user_id',
            session('user_id')
        )->firstOrFail();

        $tugas = HomeServis::with([
            'pelanggan',
            'kendaraan',
        ])
            ->where('id_montir', $montir->id_montir)
            ->orderByDesc('tanggal')
            ->get();

        $totalTugas = $tugas->count();

        $tugasAktif = $tugas->filter(function ($item) {
            return in_array($item->status, [
                'Requested',
                'On The Way',
                'Arrived',
                'In Service',
            ]);
        })->count();

        $tugasSelesai = $tugas->where(
            'status',
            'Completed'
        )->count();

        return view('montir-lapangan.dashboard', compact(
            'montir',
            'tugas',
            'totalTugas',
            'tugasAktif',
            'tugasSelesai'
        ));
    }

    /**
     * Memperbarui status tugas Home Service.
     */

    
public function tugas()
{
    $montir = MontirLapangan::where(
        'user_id',
        session('user_id')
    )->firstOrFail();

    $tugas = HomeServis::with([
        'pelanggan',
        'kendaraan',
    ])
        ->where('id_montir', $montir->id_montir)
        ->orderByDesc('tanggal')
        ->get();

    return view('montir-lapangan.tugas', compact(
        'montir',
        'tugas'
    ));
}


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => [
                'required',
                'in:On The Way,Arrived,In Service,Completed',
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $montir = MontirLapangan::where(
            'user_id',
            session('user_id')
        )->firstOrFail();

        return DB::transaction(function () use (
            $request,
            $id,
            $montir
        ) {
            $tugas = HomeServis::where(
                'id_home_service',
                $id
            )
                ->where('id_montir', $montir->id_montir)
                ->lockForUpdate()
                ->firstOrFail();

            $alurStatus = [
                'Requested' => 'On The Way',
                'On The Way' => 'Arrived',
                'Arrived' => 'In Service',
                'In Service' => 'Completed',
            ];

            if (
                !isset($alurStatus[$tugas->status]) ||
                $alurStatus[$tugas->status] !== $request->status
            ) {
                return back()->withErrors([
                    'status' => 'Status tugas harus diperbarui secara berurutan.',
                ]);
            }

            $tugas->status = $request->status;

            if ($request->has('catatan')) {
                $tugas->catatan = $request->catatan;
            }

            $tugas->save();

            return back()->with(
                'success',
                'Status Home Service berhasil diperbarui.'
            );
        });
    }
}
