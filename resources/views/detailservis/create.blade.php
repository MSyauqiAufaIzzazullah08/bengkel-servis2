@extends('layouts.app')

@section('title', 'Tambah Detail Servis')

@section('breadcrumb')
    <span>Manajemen Servis</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <a href="/detailservis">Detail Servis</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Tambah</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Detail Servis</h1>
            <p class="page-subtitle">Catat rincian layanan pada booking servis</p>
        </div>
        <a href="/detailservis" class="btn btn-secondary">
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
            <h3 class="card-title"><i class="fas fa-clipboard-list" style="color:var(--color-accent);margin-right:6px"></i> Form Detail Servis</h3>
        </div>
        <div class="card-body">
            <form action="/detailservis" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Booking Servis <span style="color:red">*</span></label>
                        <select name="booking_id" class="form-control" required>
                            <option value="">-- Pilih Booking --</option>
                            @foreach ($booking as $b)
                                <option value="{{ $b->id_booking }}"
                                    {{ old('booking_id') == $b->id_booking ? 'selected' : '' }}>
                                    #{{ $b->id_booking }} — {{ $b->pelanggan->nama ?? '-' }}
                                    ({{ $b->kendaraan->merk ?? '' }} {{ $b->kendaraan->model ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Layanan Servis <span style="color:red">*</span></label>
                        <select name="layanan_id" class="form-control" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach ($layanan as $l)
                                <option value="{{ $l->id_layanan }}"
                                    {{ old('layanan_id') == $l->id_layanan ? 'selected' : '' }}>
                                    {{ $l->nama_layanan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- [DISABLED - Dropdown Spare Part dihapus karena fitur penjualan sparepart online dinonaktifkan. Kolom id_sparepart di database tetap ada.] --}}

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga (Rp) <span style="color:red">*</span></label>
                        <input type="number" name="harga" class="form-control"
                               value="{{ old('harga') }}" min="0" step="0.01"
                               placeholder="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3"
                              placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="/detailservis" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
