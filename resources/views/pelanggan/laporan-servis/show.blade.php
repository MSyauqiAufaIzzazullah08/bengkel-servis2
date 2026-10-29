@extends('layouts.app')

@section('title', 'Detail Laporan Servis')

@section('breadcrumb')
    <a href="{{ route('laporan-servis-pelanggan.index') }}">Laporan Servis</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Detail</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Detail Laporan Servis</h1>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('laporan-servis-pelanggan.pdf', $laporan->id) }}"
           class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
    </div>
</div>

@php
    $penugasan = optional($laporan->penugasanTeknisi);
    $booking   = optional($penugasan->bookingServis);
    $kendaraan = optional($booking->kendaraan);
    $layanan   = optional($booking->layananServis);
    $cabang    = optional($booking->cabang);
    $teknisi   = optional($penugasan->teknisi);
@endphp

<div class="card mb-4">
    <div class="card-header"><strong>Informasi Servis</strong></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Kendaraan</dt>
            <dd class="col-sm-9">{{ $kendaraan->merk ?? '' }} {{ $kendaraan->model ?? '' }} — {{ $kendaraan->nopol ?? '-' }}</dd>
            <dt class="col-sm-3">Layanan</dt>
            <dd class="col-sm-9">{{ $layanan->nama_layanan ?? '-' }}</dd>
            <dt class="col-sm-3">Cabang</dt>
            <dd class="col-sm-9">{{ $cabang->nama_cabang ?? '-' }}</dd>
            <dt class="col-sm-3">Teknisi</dt>
            <dd class="col-sm-9">{{ $teknisi->nama ?? '-' }}</dd>
            <dt class="col-sm-3">Tgl Selesai</dt>
            <dd class="col-sm-9">{{ $laporan->tanggal_selesai_pekerjaan ?? '-' }}</dd>
        </dl>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><strong>Laporan Teknis</strong></div>
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

            <dt class="col-sm-3">Catatan & Rekomendasi</dt>
            <dd class="col-sm-9">{{ $laporan->catatan_rekomendasi ?? '-' }}</dd>
        </dl>
    </div>
</div>

<a href="{{ route('laporan-servis-pelanggan.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection
