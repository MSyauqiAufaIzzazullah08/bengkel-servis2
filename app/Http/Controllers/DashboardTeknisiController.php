<?php

namespace App\Http\Controllers;

use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardTeknisiController extends Controller
{
    public function index()
    {
        $teknisi = Teknisi::where(
            'user_id',
            session('user_id')
        )->firstOrFail();

        $query = $teknisi->penugasanTeknisi();

        $totalPenugasan = (clone $query)->count();

        $penugasanBaru = (clone $query)
            ->where('status', 'Assigned')
            ->count();

        $penugasanProses = (clone $query)
            ->where('status', 'In Progress')
            ->count();

        $penugasanSelesai = (clone $query)
            ->where('status', 'Completed')
            ->count();

        $penugasan = $teknisi->penugasanTeknisi()
            ->with([
                'bookingServis.pelanggan',
                'bookingServis.kendaraan',
                'bookingServis.layananServis',
            ])
            ->orderByDesc('tanggal')
            ->get();

        return view('teknisi.dashboard', compact(
            'teknisi',
            'totalPenugasan',
            'penugasanBaru',
            'penugasanProses',
            'penugasanSelesai',
            'penugasan'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['In Progress', 'Completed']),
            ],
        ]);

        $userId = session('user_id');

        $penugasan = DB::table('PenugasanTeknisi as p')
            ->join('Teknisi as t', 'p.id_teknisi', '=', 't.id_teknisi')
            ->where('p.id_penugasan', $id)
            ->where('t.user_id', $userId)
            ->select('p.*')
            ->first();

        if (!$penugasan) {
            abort(404, 'Penugasan tidak ditemukan atau bukan milik Anda.');
        }

        if ($penugasan->status === 'Completed') {
            return back()->with(
                'error',
                'Pekerjaan ini sudah selesai dan tidak dapat diubah lagi.'
            );
        }

        if (
            $data['status'] === 'Completed'
            && $penugasan->status !== 'In Progress'
        ) {
            return back()->with(
                'error',
                'Mulai pengerjaan terlebih dahulu sebelum menyelesaikan servis.'
            );
        }

        if (
            $data['status'] === 'In Progress'
            && $penugasan->status !== 'Assigned'
        ) {
            return back()->with(
                'error',
                'Status pekerjaan tidak dapat diubah ke tahap tersebut.'
            );
        }

        DB::transaction(function () use ($penugasan, $data) {
            DB::table('PenugasanTeknisi')
                ->where('id_penugasan', $penugasan->id_penugasan)
                ->update([
                    'status' => $data['status'],
                ]);

            $booking = DB::table('bookingservis')
                ->where('id_booking', $penugasan->id_booking)
                ->lockForUpdate()
                ->first();

            if ($booking && $booking->status !== 'Paid') {
                DB::table('bookingservis')
                    ->where('id_booking', $penugasan->id_booking)
                    ->update([
                        'status' => $data['status'],
                    ]);
            }
        });

        $pesan = $data['status'] === 'In Progress'
            ? 'Pekerjaan berhasil dimulai.'
            : 'Servis berhasil diselesaikan. Petugas Service dapat melanjutkan proses pembayaran.';

        return redirect()
            ->route('dashboard.teknisi')
            ->with('success', $pesan);
    }
}
