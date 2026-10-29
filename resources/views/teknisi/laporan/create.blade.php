@extends('layouts.app')

@section('title', 'Buat Laporan Servis')

@section('breadcrumb')
    <a href="{{ route('laporan-servis-teknisi.index') }}">Laporan Servis</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Buat Laporan</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">Buat Laporan Servis</h1>
        <p class="page-subtitle">Isi laporan untuk penugasan yang sudah selesai</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

@php
    $booking   = optional($penugasan->bookingServis);
    $pelanggan = optional($booking->pelanggan);
    $kendaraan = optional($booking->kendaraan);
    $layanan   = optional($booking->layananServis);
@endphp

<div class="card mb-4">
    <div class="card-header"><strong>Informasi Penugasan</strong></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Pelanggan</dt>
            <dd class="col-sm-9">{{ $pelanggan->nama ?? '-' }}</dd>
            <dt class="col-sm-3">Kendaraan</dt>
            <dd class="col-sm-9">{{ $kendaraan->merk ?? '' }} {{ $kendaraan->model ?? '' }} — {{ $kendaraan->nopol ?? '-' }}</dd>
            <dt class="col-sm-3">Layanan</dt>
            <dd class="col-sm-9">{{ $layanan->nama_layanan ?? '-' }}</dd>
            <dt class="col-sm-3">Tanggal Penugasan</dt>
            <dd class="col-sm-9">{{ $penugasan->tanggal }}</dd>
        </dl>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('laporan-servis-teknisi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="penugasan_id" value="{{ $penugasan->id_penugasan }}">

            <div class="form-group mb-3">
                <label for="keluhan_awal">Keluhan Awal Pelanggan</label>
                <textarea name="keluhan_awal" id="keluhan_awal" rows="3"
                          class="form-control @error('keluhan_awal') is-invalid @enderror">{{ old('keluhan_awal') }}</textarea>
                @error('keluhan_awal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label for="hasil_diagnosis">Hasil Diagnosis</label>
                <textarea name="hasil_diagnosis" id="hasil_diagnosis" rows="3"
                          class="form-control @error('hasil_diagnosis') is-invalid @enderror">{{ old('hasil_diagnosis') }}</textarea>
                @error('hasil_diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label for="tindakan_perbaikan">Tindakan Perbaikan</label>
                <textarea name="tindakan_perbaikan" id="tindakan_perbaikan" rows="3"
                          class="form-control @error('tindakan_perbaikan') is-invalid @enderror">{{ old('tindakan_perbaikan') }}</textarea>
                @error('tindakan_perbaikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- [DISABLED - Field komponen_digunakan dihapus karena fitur sparepart penjualan online dihapus] --}}

            <div class="form-group mb-3">
                <label for="kondisi_motor_setelah">Kondisi Motor Setelah Servis</label>
                <textarea name="kondisi_motor_setelah" id="kondisi_motor_setelah" rows="3"
                          class="form-control @error('kondisi_motor_setelah') is-invalid @enderror">{{ old('kondisi_motor_setelah') }}</textarea>
                @error('kondisi_motor_setelah')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label for="kendala_pekerjaan">Kendala Selama Pekerjaan</label>
                <textarea name="kendala_pekerjaan" id="kendala_pekerjaan" rows="2"
                          class="form-control @error('kendala_pekerjaan') is-invalid @enderror">{{ old('kendala_pekerjaan') }}</textarea>
                @error('kendala_pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-3">
                <label for="catatan_rekomendasi">Catatan & Rekomendasi untuk Pelanggan</label>
                <textarea name="catatan_rekomendasi" id="catatan_rekomendasi" rows="3"
                          class="form-control @error('catatan_rekomendasi') is-invalid @enderror">{{ old('catatan_rekomendasi') }}</textarea>
                @error('catatan_rekomendasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group mb-4">
                <label for="tanggal_selesai_pekerjaan">Tanggal Selesai Pekerjaan</label>
                <input type="date" name="tanggal_selesai_pekerjaan" id="tanggal_selesai_pekerjaan"
                       class="form-control @error('tanggal_selesai_pekerjaan') is-invalid @enderror"
                       value="{{ old('tanggal_selesai_pekerjaan') }}">
                @error('tanggal_selesai_pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Draft</button>
                <a href="{{ route('laporan-servis-teknisi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
