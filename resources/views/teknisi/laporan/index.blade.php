@extends('layouts.app')

@section('title', 'Laporan Servis Saya')

@section('breadcrumb')
    <span class="breadcrumb-current">Laporan Servis Saya</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Laporan Servis Saya</h1>
        <p class="page-subtitle">Daftar laporan servis yang telah Anda buat</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        @if ($laporan->isEmpty())
            <p class="text-muted text-center py-4">Belum ada laporan servis. Buat laporan melalui menu Pekerjaan Saya setelah penugasan selesai.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Tgl Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $i => $item)
                            @php
                                $booking  = optional(optional($item->penugasanTeknisi)->bookingServis);
                                $pelanggan = optional($booking->pelanggan);
                                $kendaraan = optional($booking->kendaraan);
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $pelanggan->nama ?? '-' }}</td>
                                <td>{{ $kendaraan->merk ?? '' }} {{ $kendaraan->model ?? '' }} — {{ $kendaraan->nopol ?? '-' }}</td>
                                <td>{{ $item->tanggal_selesai_pekerjaan ?? '-' }}</td>
                                <td>
                                    @php
                                        $badges = [
                                            'draft'                => 'secondary',
                                            'menunggu_verifikasi'  => 'warning',
                                            'perlu_revisi'         => 'danger',
                                            'disetujui'            => 'success',
                                        ];
                                        $badge = $badges[$item->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if (in_array($item->status, ['draft', 'perlu_revisi']))
                                        <a href="{{ route('laporan-servis-teknisi.edit', $item->id) }}"
                                           class="btn btn-sm btn-primary">Edit</a>

                                        <form action="{{ route('laporan-servis-teknisi.submit', $item->id) }}"
                                              method="POST" style="display:inline"
                                              onsubmit="return confirm('Ajukan laporan ini untuk verifikasi?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">Ajukan</button>
                                        </form>
                                    @else
                                        <span class="text-muted text-sm">—</span>
                                    @endif
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
