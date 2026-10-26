@extends('layouts.app')

@section('title', 'Booking Offline')

@section('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .page-header h1 {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--color-text, #111827);
        margin: 0;
    }
    .table-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        overflow-x: auto;
    }
    .badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }
    .badge-pending  { background: #fef9c3; color: #92400e; }
    .badge-progress { background: #dbeafe; color: #1e40af; }
    .badge-complete { background: #dcfce7; color: #166534; }
    .badge-cancel   { background: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-calendar-plus me-2"></i> Booking Offline</h1>
        <a href="{{ route('booking-offline.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Daftarkan Booking Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">Riwayat Booking Offline Cabang</h2>

        @if ($bookings->isEmpty())
            <p style="color:#6b7280;">Belum ada booking offline yang terdaftar.</p>
        @else
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>No. HP</th>
                    <th>Kendaraan</th>
                    <th>Nopol</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Didaftarkan Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $b)
                <tr>
                    <td>{{ $b->id_booking }}</td>
                    <td>{{ $b->nama_pelanggan }}</td>
                    <td>{{ $b->no_hp }}</td>
                    <td>{{ $b->merk }} {{ $b->model }}</td>
                    <td>{{ $b->nopol }}</td>
                    <td>{{ $b->nama_layanan ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal_booking)->format('d M Y') }} {{ substr($b->waktu_booking ?? '', 0, 5) }}</td>
                    <td>
                        @php $status = strtolower($b->status ?? ''); @endphp
                        @if ($status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif ($status === 'in progress')
                            <span class="badge badge-progress">In Progress</span>
                        @elseif ($status === 'completed')
                            <span class="badge badge-complete">Completed</span>
                        @else
                            <span class="badge badge-cancel">{{ $b->status }}</span>
                        @endif
                    </td>
                    <td>{{ $b->didaftarkan_oleh ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
