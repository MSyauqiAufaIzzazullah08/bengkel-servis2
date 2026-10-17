
@extends('layouts.app')

@section('title', 'Pembayaran Home Service')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold">Pembayaran Home Service</h2>
        <p class="text-muted mb-0">
            Daftar pembayaran layanan servis di rumah pelanggan.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('pembayaran-home-service.create') }}"
           class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Catat Pembayaran
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Riwayat Pembayaran</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No.</th>
                            <th>Kode Home Service</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pembayaran as $item)
                            <tr>
                                <td class="px-3">{{ $loop->iteration }}</td>
                                <td>
                                    #{{ $item->id_home_service }}
                                </td>
                                <td>
                                    {{ $item->homeServis->pelanggan->nama ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->homeServis->layananServis->nama_layanan ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->metodePembayaran->nama_metode
                                        ?? $item->metodePembayaran->nama
                                        ?? '-' }}
                                </td>
                                <td class="fw-semibold">
                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($item->status === 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->tanggal_bayar
                                        ? \Illuminate\Support\Carbon::parse($item->tanggal_bayar)->format('d-m-Y H:i')
                                        : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
                                    <p class="fw-semibold mb-1">
                                        Belum ada pembayaran Home Service
                                    </p>
                                    <p class="text-muted mb-0">
                                        Pembayaran akan muncul setelah dicatat.
                                    </p>
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
