<?php

namespace App\Http\Controllers;

use App\Models\PenugasanTeknisi;
use App\Models\BookingServis;
use App\Models\Teknisi;
use Illuminate\Http\Request;

class PenugasanTeknisiController extends Controller
{
    
public function index()
{
    $query = PenugasanTeknisi::with([
        'bookingServis.pelanggan',
        'bookingServis.kendaraan',
        'bookingServis.cabang',
        'teknisi.user',
    ]);

    // Admin Cabang hanya melihat penugasan dari cabangnya.
    if ((int) session('role_id') === 2) {
        $user = \App\Models\User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        $query->whereHas('bookingServis', function ($bookingQuery) use ($cabangId) {
            $bookingQuery->where('id_cabang', $cabangId);
        });
    }

    $penugasan = $query
        ->orderByDesc('id_penugasan')
        ->get();

    return view('penugasan.index', compact('penugasan'));
}


    
public function create()
{
    $bookingQuery = BookingServis::with([
        'pelanggan',
        'kendaraan',
        'cabang',
        'penugasanTeknisi',
    ]);

    $teknisiQuery = Teknisi::with('user');

    // Admin Cabang hanya boleh memilih booking dan teknisi cabangnya.
    if ((int) session('role_id') === 2) {
        $user = \App\Models\User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        $bookingQuery->where('id_cabang', $cabangId);

        $teknisiQuery->whereHas('user', function ($userQuery) use ($cabangId) {
            $userQuery->where('cabang_id', $cabangId)
                ->where('role_id', 4);
        });
    } else {
        $teknisiQuery->whereHas('user', function ($userQuery) {
            $userQuery->where('role_id', 4);
        });
    }

    // Jangan tampilkan booking yang sudah memiliki penugasan.
    $bookingQuery->whereDoesntHave('penugasanTeknisi');

    $booking = $bookingQuery
        ->orderByDesc('id_booking')
        ->get();

    $teknisi = $teknisiQuery
        ->orderBy('nama')
        ->get();

    return view('penugasan.create', compact(
        'booking',
        'teknisi'
    ));
}


    
public function store(Request $request)
{
    $data = $request->validate([
        'id_booking' => 'required|integer|exists:bookingservis,id_booking',
        'id_teknisi' => 'required|integer|exists:Teknisi,id_teknisi',
        'tanggal' => 'required|date',
        'status' => 'required|in:Assigned,In Progress,Completed',
        'catatan' => 'nullable|string',
    ]);

    $booking = BookingServis::with('penugasanTeknisi')
        ->findOrFail($data['id_booking']);

    $teknisi = Teknisi::with('user')
        ->findOrFail($data['id_teknisi']);

    // Jangan membuat penugasan kedua untuk booking yang sama.
    if ($booking->penugasanTeknisi) {
        return back()
            ->withInput()
            ->withErrors([
                'id_booking' => 'Booking ini sudah memiliki penugasan teknisi.',
            ]);
    }

    // Hanya teknisi dengan akun ber-role Teknisi yang boleh ditugaskan.
    if (!$teknisi->user || (int) $teknisi->user->role_id !== 4) {
        abort(422, 'Akun yang dipilih bukan akun Teknisi.');
    }

    // Admin Cabang hanya boleh mengelola booking dan teknisi cabangnya.
    if ((int) session('role_id') === 2) {
        $user = \App\Models\User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        abort_unless(
            (int) $booking->id_cabang === (int) $cabangId
            && (int) $teknisi->user->cabang_id === (int) $cabangId,
            403,
            'Booking dan teknisi harus berasal dari cabang Anda.'
        );
    }

    // Jangan menimpa status booking yang sudah lunas.
    if ($booking->status === 'Paid') {
        return back()
            ->withInput()
            ->withErrors([
                'id_booking' => 'Booking sudah lunas dan tidak dapat ditugaskan kembali.',
            ]);
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($data, $booking) {
        PenugasanTeknisi::create([
            'id_booking' => $booking->id_booking,
            'id_teknisi' => $data['id_teknisi'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        // Sinkronkan status booking dengan status penugasan.
        if ($data['status'] === 'In Progress') {
            $booking->update(['status' => 'In Progress']);
        } elseif ($data['status'] === 'Completed') {
            $booking->update(['status' => 'Completed']);
        } elseif ($booking->status === 'Pending') {
            $booking->update(['status' => 'Confirmed']);
        }
    });

    return redirect('/penugasan')
        ->with('success', 'Teknisi berhasil ditugaskan.');
}

    
public function edit($id)
{
    $query = PenugasanTeknisi::with('bookingServis');

    if ((int) session('role_id') === 2) {
        $user = \App\Models\User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        $query->whereHas('bookingServis', function ($bookingQuery) use ($cabangId) {
            $bookingQuery->where('id_cabang', $cabangId);
        });
    }

    $penugasan = $query->findOrFail($id);

    $bookingQuery = BookingServis::with(['pelanggan', 'kendaraan']);

    $teknisiQuery = Teknisi::with('user')
        ->whereHas('user', function ($userQuery) {
            $userQuery->where('role_id', 4);
        });

    if ((int) session('role_id') === 2) {
        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        $bookingQuery->where('id_cabang', $cabangId);

        $teknisiQuery->whereHas('user', function ($userQuery) use ($cabangId) {
            $userQuery->where('cabang_id', $cabangId);
        });
    }

    // Booking yang sedang diedit tetap boleh muncul.
    // Booking lain yang sudah ditugaskan tidak ditampilkan.
    $bookingQuery->where(function ($bookingFilter) use ($penugasan) {
        $bookingFilter
            ->whereDoesntHave('penugasanTeknisi')
            ->orWhere('id_booking', $penugasan->id_booking);
    });

    $booking = $bookingQuery->orderByDesc('id_booking')->get();
    $teknisi = $teknisiQuery->orderBy('nama')->get();

    return view('penugasan.edit', compact(
        'penugasan',
        'booking',
        'teknisi'
    ));
}


    
public function update(Request $request, $id)
{
    $data = $request->validate([
        'id_booking' => 'required|integer|exists:bookingservis,id_booking',
        'id_teknisi' => 'required|integer|exists:Teknisi,id_teknisi',
        'tanggal' => 'required|date',
        'status' => 'required|in:Assigned,In Progress,Completed',
        'catatan' => 'nullable|string',
    ]);

    $penugasan = PenugasanTeknisi::findOrFail($id);

    $bookingBaru = BookingServis::findOrFail($data['id_booking']);
    $teknisiBaru = Teknisi::with('user')->findOrFail($data['id_teknisi']);

    if (!$teknisiBaru->user || (int) $teknisiBaru->user->role_id !== 4) {
        abort(422, 'Akun yang dipilih bukan akun Teknisi.');
    }

    // Jangan izinkan dua penugasan menggunakan booking yang sama.
    $duplikat = PenugasanTeknisi::where('id_booking', $data['id_booking'])
        ->where('id_penugasan', '!=', $penugasan->id_penugasan)
        ->exists();

    if ($duplikat) {
        return back()->withInput()->withErrors([
            'id_booking' => 'Booking ini sudah memiliki penugasan lain.',
        ]);
    }

    // Admin Cabang hanya boleh mengedit penugasan dalam cabangnya.
    if ((int) session('role_id') === 2) {
        $user = \App\Models\User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        $bookingLama = BookingServis::findOrFail($penugasan->id_booking);

        abort_unless(
            (int) $bookingLama->id_cabang === (int) $cabangId
            && (int) $bookingBaru->id_cabang === (int) $cabangId
            && (int) $teknisiBaru->user->cabang_id === (int) $cabangId,
            403,
            'Penugasan, booking, dan teknisi harus berasal dari cabang Anda.'
        );
    }

    // Booking lunas tidak boleh diubah melalui penugasan.
    $bookingLama = BookingServis::findOrFail($penugasan->id_booking);

    if (
        $bookingLama->status === 'Paid'
        || $bookingBaru->status === 'Paid'
    ) {
        return back()->withInput()->withErrors([
            'status' => 'Booking yang sudah lunas tidak dapat diubah melalui penugasan.',
        ]);
    }

    \Illuminate\Support\Facades\DB::transaction(function () use (
        $data,
        $penugasan,
        $bookingLama,
        $bookingBaru
    ) {
        $penugasan->update([
            'id_booking' => $data['id_booking'],
            'id_teknisi' => $data['id_teknisi'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        // Jika booking diganti, pulihkan status booking lama
        // hanya jika statusnya masih mengikuti penugasan lama.
        if ($bookingLama->id_booking !== $bookingBaru->id_booking) {
            if ($bookingLama->status === 'In Progress') {
                $bookingLama->update(['status' => 'Confirmed']);
            } elseif ($bookingLama->status === 'Completed') {
                $bookingLama->update(['status' => 'Confirmed']);
            }
        }

        if ($data['status'] === 'In Progress') {
            $bookingBaru->update(['status' => 'In Progress']);
        } elseif ($data['status'] === 'Completed') {
            $bookingBaru->update(['status' => 'Completed']);
        } elseif ($bookingBaru->status === 'Pending') {
            $bookingBaru->update(['status' => 'Confirmed']);
        }
    });

    return redirect('/penugasan')
        ->with('success', 'Penugasan berhasil diperbarui.');
}


    
public function destroy($id)
{
    $query = PenugasanTeknisi::with('bookingServis');

    if ((int) session('role_id') === 2) {
        $user = \App\Models\User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        $query->whereHas('bookingServis', function ($bookingQuery) use ($cabangId) {
            $bookingQuery->where('id_cabang', $cabangId);
        });
    }

    $penugasan = $query->findOrFail($id);
    $booking = $penugasan->bookingServis;

    // Jangan menghapus penugasan yang sedang atau sudah selesai.
    if (in_array($penugasan->status, ['In Progress', 'Completed'], true)) {
        return redirect('/penugasan')->with(
            'error',
            'Penugasan yang sedang dikerjakan atau sudah selesai tidak dapat dihapus.'
        );
    }

    \Illuminate\Support\Facades\DB::transaction(function () use ($penugasan, $booking) {
        $penugasan->delete();

        // Kembalikan booking ke Pending hanya jika masih Confirmed.
        if ($booking && $booking->status === 'Confirmed') {
            $booking->update(['status' => 'Pending']);
        }
    });

    return redirect('/penugasan')->with(
        'success',
        'Penugasan berhasil dihapus.'
    );
}

}