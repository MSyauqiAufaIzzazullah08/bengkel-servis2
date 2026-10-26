@extends('layouts.app')

@section('title', 'Laporan Servis Saya')

@section('breadcrumb')
    <span class="breadcrumb-current">Laporan Servis</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Laporan Servis Saya</h1>
        <p class="page-subtitle">Riwayat servis kendaraan yang telah selesai dan diverifikasi</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- RIWAYAT SERVIS BENGKEL --}}
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-wrench me-1"></i> Riwayat Servis Bengkel
            <span style="background:#dbeafe;color:#1d4ed8;font-size:.72rem;font-weight:600;padding:.2rem .6rem;border-radius:999px;vertical-align:middle;margin-left:.4rem;">
                Booking Workshop
            </span>
        </h5>
        @if ($laporanBengkel->isEmpty())
            <p class="text-muted text-center py-4">Belum ada laporan servis bengkel yang tersedia.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis</th>
                            <th>Kendaraan</th>
                            <th>Layanan</th>
                            <th>Cabang</th>
                            <th>Tgl Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanBengkel as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ $item->merk }} {{ $item->model }} — {{ $item->nopol }}</td>
                                <td>{{ $item->nama_layanan ?? '-' }}</td>
                                <td>{{ $item->nama_cabang ?? '-' }}</td>
                                <td>{{ $item->tanggal_selesai_pekerjaan ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('laporan-servis-pelanggan.show', $item->id) }}"
                                       class="btn btn-sm btn-primary">Lihat</a>
                                    <a href="{{ route('laporan-servis-pelanggan.pdf', $item->id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- RIWAYAT HOME SERVICE (alur terpisah dari servis bengkel) --}}
<div class="card mt-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-home me-1"></i> Riwayat Home Service
            <span style="background:#f3e8ff;color:#7c3aed;font-size:.72rem;font-weight:600;padding:.2rem .6rem;border-radius:999px;vertical-align:middle;margin-left:.4rem;">
                Home Service
            </span>
        </h5>
        @if (!isset($homeServiceHistory) || $homeServiceHistory->isEmpty())
            <p class="text-muted text-center py-4">Belum ada riwayat home service.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Kendaraan</th>
                            <th>Layanan</th>
                            <th>Alamat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homeServiceHistory as $i => $hs)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $hs->tanggal ?? '-' }}</td>
                                <td>{{ $hs->merk ?? '' }} {{ $hs->model ?? '' }}@if($hs->nopol) — {{ $hs->nopol }}@endif</td>
                                <td>{{ $hs->nama_layanan ?? '-' }}</td>
                                <td style="max-width:200px;white-space:normal;">{{ $hs->alamat ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusColor = match($hs->status) {
                                            'Completed'   => '#16a34a',
                                            'In Service'  => '#2563eb',
                                            'Arrived'     => '#0891b2',
                                            'On The Way'  => '#d97706',
                                            default       => '#6b7280',
                                        };
                                    @endphp
                                    <span style="color:{{ $statusColor }};font-weight:600;font-size:.82rem;">
                                        {{ $hs->status ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
