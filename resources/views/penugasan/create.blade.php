@extends('layouts.app')

@section('title', 'Tambah Penugasan Teknisi')

@section('breadcrumb')
    <span>Transaksi</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <a href="/penugasan" style="color:inherit;text-decoration:none">Penugasan Teknisi</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Tambah</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Penugasan Teknisi</h1>
            <p class="page-subtitle">Isi form di bawah untuk menugaskan teknisi ke booking</p>
        </div>
        <a href="/penugasan" class="btn btn-accent">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px">
            <i class="fas fa-times-circle alert-icon"></i>
            <div>
                <div class="alert-title">Terjadi kesalahan:</div>
                <ul style="margin:4px 0 0 16px;padding:0">
                    @foreach ($errors->all() as $error)
                        <li style="font-size:0.875rem">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-plus" style="color:var(--color-accent);margin-right:6px"></i>
                Form Tambah Penugasan
            </h3>
        </div>
        <div style="padding:24px">
            <form action="/penugasan" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="id_booking">Booking Servis <span style="color:red">*</span></label>
                    <select name="id_booking" id="id_booking" class="form-control" required>
                        <option value="">-- Pilih Booking --</option>
                        @foreach ($booking as $item)
                            <option value="{{ $item->id_booking }}"
                                {{ old('id_booking') == $item->id_booking ? 'selected' : '' }}>
                                #{{ $item->id_booking }} - {{ $item->pelanggan->nama ?? '-' }} - {{ $item->kendaraan->merk ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="id_teknisi">Teknisi <span style="color:red">*</span></label>
                    <select name="id_teknisi" id="id_teknisi" class="form-control" required>
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach ($teknisi as $item)
                            <option value="{{ $item->id_teknisi }}"
                                {{ old('id_teknisi') == $item->id_teknisi ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal">Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="{{ old('tanggal') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status <span style="color:red">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                       
@foreach ([
    'Assigned' => 'Ditugaskan',
    'In Progress' => 'Sedang Dikerjakan',
    'Completed' => 'Selesai',
] as $value => $label)
    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
        {{ $label }}
    </option>
@endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="/penugasan" class="btn btn-accent">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
