@extends('layouts.app')

@section('title', 'Tambah Konfigurasi Komisi')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Tambah Konfigurasi Komisi</h2>
        <p class="text-muted mb-0">
            <a href="{{ route('komisi.konfigurasi.index') }}">Konfigurasi Komisi</a>
            &rsaquo; Tambah Baru
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width:560px">
        <div class="card-body p-4">
            <form action="{{ route('komisi.konfigurasi.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Berlaku Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="berlaku_mulai" class="form-control @error('berlaku_mulai') is-invalid @enderror"
                           value="{{ old('berlaku_mulai') }}" required>
                    @error('berlaku_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Konfigurasi ini akan aktif mulai tanggal yang dipilih.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Target Bulanan (jumlah servis) <span class="text-danger">*</span></label>
                    <input type="number" name="target_bulanan" class="form-control @error('target_bulanan') is-invalid @enderror"
                           value="{{ old('target_bulanan', 20) }}" min="1" required>
                    @error('target_bulanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Komisi per Servis (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="komisi_per_servis" class="form-control @error('komisi_per_servis') is-invalid @enderror"
                           value="{{ old('komisi_per_servis', 10000) }}" min="0" step="500" required>
                    @error('komisi_per_servis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Bonus Target Bulanan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="bonus_target_bulanan" class="form-control @error('bonus_target_bulanan') is-invalid @enderror"
                           value="{{ old('bonus_target_bulanan', 150000) }}" min="0" step="500" required>
                    @error('bonus_target_bulanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Bonus yang diberikan jika teknisi mencapai atau melampaui target bulanan.</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Konfigurasi</button>
                    <a href="{{ route('komisi.konfigurasi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
