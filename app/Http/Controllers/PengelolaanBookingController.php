<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PengelolaanBookingController extends Controller
{
    private function queryBooking()
    {
        $query = DB::table('bookingservis as b');

        if ((int) session('role_id') === 2) {
            $user = User::findOrFail(session('user_id'));

            $cabangId = $user->cabang_id
                ?? $user->adminCabang?->cabang_id;

            abort_if(
                !$cabangId,
                403,
                'Akun Admin Cabang belum terhubung dengan cabang.'
            );

            $query->where('b.id_cabang', $cabangId);
        }

        return $query;
    }

    public function index()
    {
        $bookings = $this->queryBooking()
            ->leftJoin('pelanggan as p', 'b.id_pelanggan', '=', 'p.id_pelanggan')
            ->leftJoin('kendaraan as k', 'b.id_kendaraan', '=', 'k.id_kendaraan')
            ->leftJoin('cabang as c', 'b.id_cabang', '=', 'c.id_cabang')
            ->leftJoin('layananservis as l', 'b.layanan_id', '=', 'l.id_layanan')
            ->select(
                'b.id_booking',
                'b.id_cabang',
                'b.tanggal_booking',
                'b.waktu_booking',
                'b.status',
                'b.catatan',
                'p.nama as nama_pelanggan',
                'p.email as email_pelanggan',
                'k.merk',
                'k.model',
                'k.nopol',
                'c.nama_cabang',
                'l.nama_layanan',
                'l.harga'
            )
            ->orderByRaw("
                CASE b.status
                    WHEN 'Pending' THEN 1
                    WHEN 'Confirmed' THEN 2
                    WHEN 'In Progress' THEN 3
                    WHEN 'Completed' THEN 4
                    WHEN 'Paid' THEN 5
                    ELSE 6
                END
            ")
            ->orderByDesc('b.id_booking')
            ->get();

        // Ambil teknisi aktif beserta cabang dari akun User.
        $teknisi = DB::table('Teknisi as t')
            ->join('User as u', 't.user_id', '=', 'u.id_user')
            ->where('u.role_id', 4)
            ->where('u.status', 'aktif')
            ->select(
                't.id_teknisi',
                't.nama',
                'u.cabang_id'
            )
            ->orderBy('t.nama')
            ->get();

        $totalBooking = $bookings->count();

        $menungguKonfirmasi = $bookings
            ->where('status', 'Pending')
            ->count();

        $sedangDiservis = $bookings
            ->where('status', 'In Progress')
            ->count();

        $servisSelesai = $bookings
            ->whereIn('status', ['Completed', 'Paid'])
            ->count();

        return view('pengelolaan-booking.index', compact(
            'bookings',
            'teknisi',
            'totalBooking',
            'menungguKonfirmasi',
            'sedangDiservis',
            'servisSelesai'
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['Pending', 'Confirmed']),
            ],
            'id_teknisi' => [
                'nullable',
                'integer',
                'exists:Teknisi,id_teknisi',
            ],
        ]);

        $user = User::findOrFail(session('user_id'));

        DB::transaction(function () use ($data, $id, $user) {
            $booking = $this->queryBooking()
                ->where('b.id_booking', $id)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                abort(404, 'Booking tidak ditemukan atau bukan milik cabang Anda.');
            }

            if ($booking->status === 'Paid') {
                throw ValidationException::withMessages([
                    'status' => 'Booking yang sudah lunas tidak dapat diubah.',
                ]);
            }

            // Status pengerjaan dan penyelesaian diubah oleh teknisi.
            if (in_array($booking->status, ['In Progress', 'Completed'], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Status pengerjaan harus diperbarui melalui proses penugasan teknisi.',
                ]);
            }

            if ($data['status'] === 'Pending') {
                if ($booking->status === 'Confirmed') {
                    throw ValidationException::withMessages([
                        'status' => 'Booking yang sudah dikonfirmasi tidak dapat dikembalikan ke Pending.',
                    ]);
                }

                return;
            }

            // Status yang diminta adalah Confirmed.
            $penugasan = DB::table('PenugasanTeknisi')
                ->where('id_booking', $id)
                ->lockForUpdate()
                ->first();

            if (!$penugasan) {
                if (empty($data['id_teknisi'])) {
                    throw ValidationException::withMessages([
                        'id_teknisi' => 'Pilih teknisi sebelum mengonfirmasi booking.',
                    ]);
                }

                $teknisi = DB::table('Teknisi as t')
                    ->join('User as u', 't.user_id', '=', 'u.id_user')
                    ->where('t.id_teknisi', $data['id_teknisi'])
                    ->where('u.role_id', 4)
                    ->where('u.status', 'aktif')
                    ->select('t.id_teknisi', 'u.cabang_id')
                    ->first();

                if (!$teknisi) {
                    throw ValidationException::withMessages([
                        'id_teknisi' => 'Teknisi tidak ditemukan atau akunnya tidak aktif.',
                    ]);
                }

                // Semua role wajib memilih teknisi dari cabang booking.
                if ((int) $teknisi->cabang_id !== (int) $booking->id_cabang) {
                    throw ValidationException::withMessages([
                        'id_teknisi' => 'Teknisi harus berasal dari cabang booking.',
                    ]);
                }

                // Validasi tambahan untuk Admin Cabang.
                if ((int) session('role_id') === 2) {
                    $cabangAdmin = $user->cabang_id
                        ?? $user->adminCabang?->cabang_id;

                    abort_unless(
                        (int) $booking->id_cabang === (int) $cabangAdmin,
                        403,
                        'Booking bukan milik cabang Anda.'
                    );
                }

                DB::table('PenugasanTeknisi')->insert([
                    'id_booking' => $id,
                    'id_teknisi' => $teknisi->id_teknisi,
                    'tanggal' => now()->toDateString(),
                    'status' => 'Assigned',
                    'catatan' => null,
                ]);
            }

            DB::table('bookingservis')
                ->where('id_booking', $id)
                ->update(['status' => 'Confirmed']);
        });

        return redirect()
            ->route('pengelolaan-booking.index')
            ->with('success', "Booking #{$id} berhasil dikonfirmasi dan penugasan teknisi diproses.");
    }
}
