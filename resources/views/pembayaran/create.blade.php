
@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('breadcrumb')
    <span>Keuangan</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <a href="/pembayaran">Daftar Pembayaran</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Tambah</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Pembayaran</h1>
        <p class="page-subtitle">Isi form berikut untuk mencatat pembayaran baru</p>
    </div>
    <a href="/pembayaran" class="btn btn-secondary">
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
            <i class="fas fa-credit-card"
               style="color:var(--color-accent);margin-right:6px"></i>
            Form Pembayaran
        </h3>
    </div>

    <div class="card-body">
        <form action="/pembayaran" method="POST" id="formPembayaran">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Booking Servis</label>
                    <select name="id_booking" id="id_booking" class="form-control">
                        <option value="">-- Pilih Booking (jika ada) --</option>
                        @foreach ($booking as $b)
                            <option
                                value="{{ $b->id_booking }}"
                                data-harga="{{ $b->layananServis->harga ?? 0 }}"
                                {{ old('id_booking') == $b->id_booking ? 'selected' : '' }}>
                                #{{ $b->id_booking }} — {{ $b->pelanggan->nama ?? '-' }}
                                ({{ $b->status }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Order Spare Part</label>
                    <select name="id_order" id="id_order" class="form-control">
                        <option value="">-- Pilih Order (jika ada) --</option>
                        @foreach ($orders as $o)
                            <option
                                value="{{ $o->id_order }}"
                                data-harga="{{ $o->total_harga ?? 0 }}"
                                {{ old('id_order') == $o->id_order ? 'selected' : '' }}>
                                #{{ $o->id_order }} — {{ $o->pelanggan->nama ?? '-' }}
                                ({{ $o->status ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        Metode Pembayaran <span style="color:red">*</span>
                    </label>
                    <select name="metode_pembayaran_id" class="form-control" required>
                        <option value="">-- Pilih Metode --</option>
                        @foreach ($metode as $m)
                            <option value="{{ $m->id_metode }}"
                                {{ old('metode_pembayaran_id') == $m->id_metode ? 'selected' : '' }}>
                                {{ $m->nama_metode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Promo</label>
                    <select name="id_promo" id="id_promo" class="form-control">
                        <option value="">-- Tanpa Promo --</option>
                        @foreach ($promo as $p)
                            <option
                                value="{{ $p->id_promo }}"
                                data-jenis="{{ strtolower($p->jenis_diskon) }}"
                                data-nilai="{{ $p->nilai_diskon }}"
                                data-minimal="{{ $p->minimal_transaksi }}"
                                data-mulai="{{ $p->periode_mulai }}"
                                data-selesai="{{ $p->periode_selesai }}"
                                data-kuota="{{ $p->kuota }}"
                                {{ old('id_promo') == $p->id_promo ? 'selected' : '' }}>
                                {{ $p->kode_promo }} — {{ ucfirst($p->jenis_diskon) }}
                                {{ strtolower($p->jenis_diskon) === 'persen'
                                    ? $p->nilai_diskon . '%'
                                    : 'Rp ' . number_format($p->nilai_diskon, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        Jumlah Bayar (Rp) <span style="color:red">*</span>
                    </label>
                    <input
                        type="number"
                        name="jumlah"
                        id="jumlah"
                        class="form-control"
                        value="{{ old('jumlah', '') }}"
                        min="0"
                        step="0.01"
                        placeholder="Pilih transaksi terlebih dahulu"
                        required
                        readonly>
                    <small style="color:var(--color-text-muted)">
                        Nominal dihitung otomatis dari transaksi dan promo.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Status <span style="color:red">*</span>
                    </label>
                    <select name="status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach (['menunggu', 'lunas', 'gagal'] as $s)
                            <option value="{{ $s }}"
                                {{ old('status') == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="rincianPembayaran"
                 style="display:none;padding:16px;margin-bottom:20px;border:1px solid var(--color-border,#ddd);border-radius:8px">
                <h4 style="margin:0 0 12px">Rincian Pembayaran</h4>

                <div style="display:flex;justify-content:space-between;gap:12px;margin-bottom:8px">
                    <span>Harga awal</span>
                    <strong id="hargaAwal">Rp0</strong>
                </div>

                <div style="display:flex;justify-content:space-between;gap:12px;margin-bottom:8px">
                    <span>Diskon</span>
                    <strong id="nilaiPotongan">Rp0</strong>
                </div>

                <div style="display:flex;justify-content:space-between;gap:12px;padding-top:10px;border-top:1px solid var(--color-border,#ddd)">
                    <span>Total pembayaran</span>
                    <strong id="totalAkhir" style="color:var(--color-accent)">Rp0</strong>
                </div>

                <p id="pesanPromo" style="margin:10px 0 0;font-size:0.875rem"></p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control"
                           value="{{ old('tanggal_bayar') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Bukti Pembayaran
                        <small style="color:var(--color-text-muted)">(URL/nomor referensi)</small>
                    </label>
                    <input type="text" name="bukti_pembayaran" class="form-control"
                           value="{{ old('bukti_pembayaran') }}" placeholder="Opsional">
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="/pembayaran" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookingSelect = document.getElementById('id_booking');
    const orderSelect = document.getElementById('id_order');
    const promoSelect = document.getElementById('id_promo');
    const jumlahInput = document.getElementById('jumlah');
    const rincian = document.getElementById('rincianPembayaran');
    const hargaAwalEl = document.getElementById('hargaAwal');
    const potonganEl = document.getElementById('nilaiPotongan');
    const totalEl = document.getElementById('totalAkhir');
    const pesanPromo = document.getElementById('pesanPromo');

    const rupiah = angka => 'Rp' + Math.round(angka).toLocaleString('id-ID');

    function hitungPembayaran() {
        const bookingOption = bookingSelect.options[bookingSelect.selectedIndex];
        const orderOption = orderSelect.options[orderSelect.selectedIndex];
        const promoOption = promoSelect.options[promoSelect.selectedIndex];

        const adaBooking = bookingSelect.value !== '';
        const adaOrder = orderSelect.value !== '';

        // Satu pembayaran hanya untuk satu transaksi.
        if (adaBooking && adaOrder) {
            jumlahInput.value = '';
            rincian.style.display = 'block';
            hargaAwalEl.textContent = '-';
            potonganEl.textContent = '-';
            totalEl.textContent = '-';
            pesanPromo.textContent = 'Pilih booking servis atau order spare part, jangan keduanya.';
            pesanPromo.style.color = '#dc2626';
            return;
        }

        const harga = adaBooking
            ? Number(bookingOption.dataset.harga || 0)
            : (adaOrder ? Number(orderOption.dataset.harga || 0) : 0);

        if (!adaBooking && !adaOrder) {
            jumlahInput.value = '';
            rincian.style.display = 'none';
            return;
        }

        let potongan = 0;
        let pesan = '';
        let promoValid = true;

        if (harga <= 0) {
            promoValid = false;
            pesan = 'Harga transaksi tidak tersedia atau tidak valid.';
        }

        if (promoSelect.value !== '' && harga > 0) {
            const jenis = (promoOption.dataset.jenis || '').toLowerCase();
            const nilai = Number(promoOption.dataset.nilai || 0);
            const minimal = Number(promoOption.dataset.minimal || 0);
            const mulai = promoOption.dataset.mulai;
            const selesai = promoOption.dataset.selesai;
            const kuota = promoOption.dataset.kuota;

            // Tanggal lokal browser, format YYYY-MM-DD.
            const sekarang = new Date();
            const tahun = sekarang.getFullYear();
            const bulan = String(sekarang.getMonth() + 1).padStart(2, '0');
            const hari = String(sekarang.getDate()).padStart(2, '0');
            const hariIni = `${tahun}-${bulan}-${hari}`;

            if (harga < minimal) {
                promoValid = false;
                pesan = 'Promo tidak memenuhi minimal transaksi ' + rupiah(minimal) + '.';
            } else if ((mulai && hariIni < mulai) || (selesai && hariIni > selesai)) {
                promoValid = false;
                pesan = 'Promo belum berlaku atau sudah kedaluwarsa.';
            } else if (kuota !== '' && Number(kuota) <= 0) {
                promoValid = false;
                pesan = 'Kuota promo mungkin sudah habis. Server akan memeriksa kembali.';
            } else if (jenis === 'persen') {
                potongan = harga * nilai / 100;
                pesan = 'Diskon ' + nilai + '% diterapkan.';
            } else if (jenis === 'nominal') {
                potongan = nilai;
                pesan = 'Potongan nominal ' + rupiah(nilai) + ' diterapkan.';
            } else {
                promoValid = false;
                pesan = 'Jenis promo tidak dikenali.';
            }
        }

        if (!promoValid) {
            jumlahInput.value = '';
            hargaAwalEl.textContent = rupiah(harga);
            potonganEl.textContent = rupiah(0);
            totalEl.textContent = '-';
            pesanPromo.textContent = pesan;
            pesanPromo.style.color = '#dc2626';
            rincian.style.display = 'block';
            return;
        }

        potongan = Math.min(potongan, harga);
        const total = Math.max(0, harga - potongan);

        jumlahInput.value = total.toFixed(2);
        hargaAwalEl.textContent = rupiah(harga);
        potonganEl.textContent = rupiah(potongan);
        totalEl.textContent = rupiah(total);
        pesanPromo.textContent = pesan || 'Tidak menggunakan promo.';
        pesanPromo.style.color = '#15803d';
        rincian.style.display = 'block';
    }

    bookingSelect.addEventListener('change', function () {
        if (this.value) orderSelect.value = '';
        hitungPembayaran();
    });

    orderSelect.addEventListener('change', function () {
        if (this.value) bookingSelect.value = '';
        hitungPembayaran();
    });

    promoSelect.addEventListener('change', hitungPembayaran);

    hitungPembayaran();
});
</script>
@endsection
