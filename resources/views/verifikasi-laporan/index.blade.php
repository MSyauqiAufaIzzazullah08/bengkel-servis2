@extends('layouts.app')

@section('title', 'Verifikasi Laporan Servis')

@section('breadcrumb')
    <span class="breadcrumb-current">Verifikasi Laporan</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Verifikasi Laporan Servis</h1>
        <p class="page-subtitle">Laporan yang menunggu verifikasi di cabang Anda</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        @if ($laporan->isEmpty())
            <p class="text-muted text-center py-4">Tidak ada laporan yang menunggu verifikasi saat ini.</p>
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
                            <th>Diperbarui</th>
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
                                <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('verifikasi-laporan.show', $item->id) }}"
                                       class="btn btn-sm btn-primary">Periksa</a>
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
