@extends('layouts.app')

@section('title', 'Laporan Servis Cabang')

@section('breadcrumb')
    <span class="breadcrumb-current">Laporan Servis</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Laporan Servis Cabang</h1>
        <p class="page-subtitle">Laporan servis yang sudah disetujui di cabang Anda</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        @if ($laporan->isEmpty())
            <p class="text-muted text-center py-4">Belum ada laporan servis yang tersedia.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Teknisi</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Tgl Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $i => $item)
                            @php
                                $penugasan = optional($item->penugasanTeknisi);
                                $booking   = optional($penugasan->bookingServis);
                                $pelanggan = optional($booking->pelanggan);
                                $kendaraan = optional($booking->kendaraan);
                                $teknisi   = optional($penugasan->teknisi);
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $teknisi->nama ?? '-' }}</td>
                                <td>{{ $pelanggan->nama ?? '-' }}</td>
                                <td>{{ $kendaraan->merk ?? '' }} {{ $kendaraan->model ?? '' }} — {{ $kendaraan->nopol ?? '-' }}</td>
                                <td>{{ $item->tanggal_selesai_pekerjaan ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.laporan-servis.pdf', $item->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-pdf"></i> Download PDF
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
@endsection
