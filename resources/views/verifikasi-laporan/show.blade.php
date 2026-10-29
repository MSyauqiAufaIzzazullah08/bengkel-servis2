@extends('layouts.app')

@section('title', 'Detail Laporan Servis')

@section('breadcrumb')
    <a href="{{ route('verifikasi-laporan.index') }}">Verifikasi Laporan</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Detail Laporan #{{ $laporan->id }}</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Detail Laporan Servis #{{ $laporan->id }}</h1>
        <p class="page-subtitle">
            Status:
            @php
                $badges = [
                    'draft'               => 'secondary',
                    'menunggu_verifikasi' => 'warning',
                    'perlu_revisi'        => 'danger',
                    'disetujui'           => 'success',
                ];
                $badge = $badges[$laporan->status] ?? 'secondary';
            @endphp
            <span class="badge badge-{{ $badge }}">
                {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
            </span>
        </p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@php
    $penugasan = optional($laporan->penugasanTeknisi);
    $booking   = optional($penugasan->bookingServis);
    $pelanggan = optional($booking->pelanggan);
    $kendaraan = optional($booking->kendaraan);
    $layanan   = optional($booking->layananServis);
    $teknisi   = optional($penugasan->teknisi);
@endphp

{{-- Informasi Umum --}}
<div class="card mb-4">
    <div class="card-header"><strong>Informasi Penugasan</strong></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Teknisi</dt>
            <dd class="col-sm-9">{{ $teknisi->nama ?? '-' }}</dd>
            <dt class="col-sm-3">Pelanggan</dt>
            <dd class="col-sm-9">{{ $pelanggan->nama ?? '-' }}</dd>
            <dt class="col-sm-3">Kendaraan</dt>
            <dd class="col-sm-9">{{ $kendaraan->merk ?? '' }} {{ $kendaraan->model ?? '' }} — {{ $kendaraan->nopol ?? '-' }}</dd>
            <dt class="col-sm-3">Layanan</dt>
            <dd class="col-sm-9">{{ $layanan->nama_layanan ?? '-' }}</dd>
            <dt class="col-sm-3">Tgl Penugasan</dt>
            <dd class="col-sm-9">{{ $penugasan->tanggal ?? '-' }}</dd>
            <dt class="col-sm-3">Tgl Selesai Kerja</dt>
            <dd class="col-sm-9">{{ $laporan->tanggal_selesai_pekerjaan ?? '-' }}</dd>
        </dl>
    </div>
</div>

{{-- Isi Laporan --}}
<div class="card mb-4">
    <div class="card-header"><strong>Isi Laporan</strong></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Keluhan Awal</dt>
            <dd class="col-sm-9">{{ $laporan->keluhan_awal ?? '-' }}</dd>

            <dt class="col-sm-3">Hasil Diagnosis</dt>
            <dd class="col-sm-9">{{ $laporan->hasil_diagnosis ?? '-' }}</dd>

            <dt class="col-sm-3">Tindakan Perbaikan</dt>
            <dd class="col-sm-9">{{ $laporan->tindakan_perbaikan ?? '-' }}</dd>

            {{-- [DISABLED - Komponen Digunakan dihapus karena fitur sparepart penjualan online dihapus] --}}

            <dt class="col-sm-3">Kondisi Motor Setelah</dt>
            <dd class="col-sm-9">{{ $laporan->kondisi_motor_setelah ?? '-' }}</dd>

            <dt class="col-sm-3">Kendala Pekerjaan</dt>
            <dd class="col-sm-9">{{ $laporan->kendala_pekerjaan ?? '-' }}</dd>

            <dt class="col-sm-3">Catatan Rekomendasi</dt>
            <dd class="col-sm-9">{{ $laporan->catatan_rekomendasi ?? '-' }}</dd>
        </dl>
    </div>
</div>

{{-- Riwayat Verifikasi --}}
@if ($laporan->riwayatVerifikasi->isNotEmpty())
<div class="card mb-4">
    <div class="card-header"><strong>Riwayat Verifikasi</strong></div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Admin</th>
                    <th>Aksi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan->riwayatVerifikasi->sortByDesc('id') as $riwayat)
                    <tr>
                        <td>{{ $riwayat->created_at ? \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ optional($riwayat->admin)->nama ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $riwayat->aksi === 'disetujui' ? 'success' : 'danger' }}">
                                {{ ucfirst(str_replace('_', ' ', $riwayat->aksi)) }}
                            </span>
                        </td>
                        <td>{{ $riwayat->catatan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Form Aksi (hanya jika masih menunggu_verifikasi) --}}
@if ($laporan->status === 'menunggu_verifikasi')
<div class="row">
    {{-- Setujui --}}
    <div class="col-md-6 mb-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white"><strong>Setujui Laporan</strong></div>
            <div class="card-body">
                <form action="{{ route('verifikasi-laporan.setujui', $laporan->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menyetujui laporan ini?')">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="catatan_setujui">Catatan (opsional)</label>
                        <textarea name="catatan" id="catatan_setujui" rows="3"
                                  class="form-control" placeholder="Catatan untuk teknisi..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Setujui</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Minta Revisi --}}
    <div class="col-md-6 mb-4">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white"><strong>Minta Revisi</strong></div>
            <div class="card-body">
                <form action="{{ route('verifikasi-laporan.minta-revisi', $laporan->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin meminta revisi laporan ini?')">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="catatan_revisi">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatan_revisi" rows="3"
                                  class="form-control @error('catatan') is-invalid @enderror"
                                  placeholder="Tuliskan hal yang perlu diperbaiki..." required></textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Minta Revisi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<a href="{{ route('verifikasi-laporan.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection
