@extends('layouts.app')

@section('title', 'Daftarkan Booking Offline')

@section('styles')
<style>
    .form-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        margin-bottom: 1.5rem;
    }
    .form-card h2 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: .5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: .75rem 1rem;
        font-size: .85rem;
        color: #1e40af;
        margin-bottom: 1rem;
    }
    .found-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #dcfce7;
        color: #166534;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: .78rem;
        font-weight: 600;
        margin-top: .25rem;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;">
        <h1 style="font-size:1.35rem;font-weight:700;margin:0;">
            <i class="fas fa-calendar-plus me-2"></i> Daftarkan Booking Offline
        </h1>
        <a href="{{ route('booking-offline.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        Pendaftaran offline tidak memerlukan akun pelanggan. Masukkan no. HP untuk mencari pelanggan yang sudah terdaftar,
        dan nomor polisi untuk mencari kendaraan. Jika belum ada, data baru akan dibuat otomatis.
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0" style="padding-left:1.2rem;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('booking-offline.store') }}" id="formBookingOffline">
        @csrf

        {{-- DATA PELANGGAN --}}
        <div class="form-card">
            <h2><i class="fas fa-user me-2 text-primary"></i> Data Pelanggan</h2>

            <div class="mb-3">
                <label class="form-label">No. HP Pelanggan</label>
                <div style="display:flex;gap:.5rem;">
                    <input type="text" id="input_no_hp" name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}"
                           placeholder="Contoh: 08123456789"
                           style="max-width:260px;" />
                    <button type="button" class="btn btn-secondary" onclick="cariPelanggan()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                @error('no_hp')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <div id="pelanggan_found" style="display:none;" class="found-badge mt-1">
                    <i class="fas fa-check-circle"></i> <span id="pelanggan_found_label"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" id="input_nama_pelanggan" name="nama_pelanggan"
                           class="form-control @error('nama_pelanggan') is-invalid @enderror"
                           value="{{ old('nama_pelanggan') }}"
                           placeholder="Nama lengkap pelanggan" required />
                    @error('nama_pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" id="input_alamat" name="alamat"
                           class="form-control @error('alamat') is-invalid @enderror"
                           value="{{ old('alamat') }}"
                           placeholder="Alamat (opsional)" />
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- DATA KENDARAAN --}}
        <div class="form-card">
            <h2><i class="fas fa-motorcycle me-2 text-primary"></i> Data Kendaraan</h2>

            <div class="mb-3">
                <label class="form-label">Nomor Polisi</label>
                <div style="display:flex;gap:.5rem;">
                    <input type="text" id="input_nopol" name="nopol"
                           class="form-control @error('nopol') is-invalid @enderror"
                           value="{{ old('nopol') }}"
                           placeholder="Contoh: B 1234 ABC"
                           style="max-width:200px;text-transform:uppercase;" />
                    <button type="button" class="btn btn-secondary" onclick="cariKendaraan()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                @error('nopol')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <div id="kendaraan_found" style="display:none;" class="found-badge mt-1">
                    <i class="fas fa-check-circle"></i> <span id="kendaraan_found_label"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" id="input_merk" name="merk"
                           class="form-control @error('merk') is-invalid @enderror"
                           value="{{ old('merk') }}"
                           placeholder="Contoh: Honda" required />
                    @error('merk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" id="input_model" name="model"
                           class="form-control @error('model') is-invalid @enderror"
                           value="{{ old('model') }}"
                           placeholder="Contoh: Vario 125" required />
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <input type="number" id="input_tahun" name="tahun"
                           class="form-control @error('tahun') is-invalid @enderror"
                           value="{{ old('tahun') }}"
                           min="1980" max="{{ date('Y') + 1 }}"
                           placeholder="{{ date('Y') }}" required />
                    @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- DATA BOOKING --}}
        <div class="form-card">
            <h2><i class="fas fa-clipboard-list me-2 text-primary"></i> Data Booking</h2>

            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Layanan <span class="text-danger">*</span></label>
                    <select name="layanan_id" class="form-control @error('layanan_id') is-invalid @enderror" required>
                        <option value="">— Pilih Layanan —</option>
                        @foreach ($layanans as $l)
                            <option value="{{ $l->id_layanan }}"
                                {{ old('layanan_id') == $l->id_layanan ? 'selected' : '' }}>
                                {{ $l->nama_layanan }}
                                @if ($l->harga) (Rp {{ number_format($l->harga, 0, ',', '.') }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('layanan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Booking <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_booking"
                           class="form-control @error('tanggal_booking') is-invalid @enderror"
                           value="{{ old('tanggal_booking', date('Y-m-d')) }}" required />
                    @error('tanggal_booking')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Waktu <span class="text-danger">*</span></label>
                    <input type="time" name="waktu_booking"
                           class="form-control @error('waktu_booking') is-invalid @enderror"
                           value="{{ old('waktu_booking', '08:00') }}" required />
                    @error('waktu_booking')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan / Keluhan Awal</label>
                <textarea name="catatan" rows="3"
                          class="form-control @error('catatan') is-invalid @enderror"
                          placeholder="Keluhan awal atau catatan tambahan">{{ old('catatan') }}</textarea>
                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <a href="{{ route('booking-offline.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Booking
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function cariPelanggan() {
    const noHp = document.getElementById('input_no_hp').value.trim();
    if (!noHp) { alert('Masukkan nomor HP terlebih dahulu.'); return; }

    fetch('/booking-offline/cari-pelanggan?no_hp=' + encodeURIComponent(noHp), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data) {
            document.getElementById('input_nama_pelanggan').value = data.nama || '';
            document.getElementById('input_alamat').value = data.alamat || '';
            document.getElementById('pelanggan_found_label').textContent = 'Pelanggan ditemukan: ' + data.nama;
            document.getElementById('pelanggan_found').style.display = 'inline-flex';
        } else {
            document.getElementById('pelanggan_found').style.display = 'none';
            document.getElementById('input_nama_pelanggan').value = '';
            document.getElementById('input_alamat').value = '';
            alert('Pelanggan tidak ditemukan. Data baru akan dibuat saat booking disimpan.');
        }
    })
    .catch(() => alert('Gagal mencari pelanggan. Silakan coba lagi.'));
}

function cariKendaraan() {
    const nopol = document.getElementById('input_nopol').value.trim().toUpperCase();
    if (!nopol) { alert('Masukkan nomor polisi terlebih dahulu.'); return; }

    fetch('/booking-offline/cari-kendaraan?nopol=' + encodeURIComponent(nopol), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data) {
            document.getElementById('input_merk').value  = data.merk  || '';
            document.getElementById('input_model').value = data.model || '';
            document.getElementById('input_tahun').value = data.tahun || '';
            document.getElementById('kendaraan_found_label').textContent =
                'Kendaraan ditemukan: ' + data.merk + ' ' + data.model + ' (' + data.nopol + ')';
            document.getElementById('kendaraan_found').style.display = 'inline-flex';

            // Jika ada info pemilik, isi juga data pelanggan
            if (data.nama_pemilik && !document.getElementById('input_nama_pelanggan').value) {
                document.getElementById('input_nama_pelanggan').value = data.nama_pemilik;
                if (data.no_hp) document.getElementById('input_no_hp').value = data.no_hp;
            }
        } else {
            document.getElementById('kendaraan_found').style.display = 'none';
            document.getElementById('input_merk').value  = '';
            document.getElementById('input_model').value = '';
            document.getElementById('input_tahun').value = '';
            alert('Kendaraan tidak ditemukan. Data baru akan dibuat saat booking disimpan.');
        }
    })
    .catch(() => alert('Gagal mencari kendaraan. Silakan coba lagi.'));
}
</script>
@endsection
