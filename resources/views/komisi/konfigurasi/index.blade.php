@extends('layouts.app')

@section('title', 'Konfigurasi Komisi')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Konfigurasi Komisi</h2>
            <p class="text-muted mb-0">Atur target bulanan dan nominal komisi teknisi.</p>
        </div>
        <a href="{{ route('komisi.konfigurasi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Konfigurasi
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Berlaku Mulai</th>
                            <th>Target Bulanan</th>
                            <th>Komisi / Servis</th>
                            <th>Bonus Target</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($konfigurasi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->berlaku_mulai)->isoFormat('D MMMM YYYY') }}</td>
                                <td>{{ number_format($item->target_bulanan) }} servis</td>
                                <td>Rp {{ number_format($item->komisi_per_servis, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->bonus_target_bulanan, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada konfigurasi komisi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
