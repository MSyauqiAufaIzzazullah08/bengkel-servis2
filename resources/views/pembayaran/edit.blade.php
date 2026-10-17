
@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('breadcrumb')
    <span>Keuangan</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <a href="/pembayaran">Daftar Pembayaran</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Pembayaran</h1>
        <p class="page-subtitle">
            Ubah data pembayaran #{{ $pembayaran->id_pembayaran }}
        </p>
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

@if ($pembayaran->status === 'lunas')
    <div class="alert alert-danger" style="margin-bottom:20px">
        <i class="fas fa-lock alert-icon"></i>
        <div>
            <div class="alert-title">Pembayaran sudah lunas</div>
            Transaksi lunas dikunci agar nominal, sumber transaksi, dan statusnya
            tidak berubah secara tidak sengaja.
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-credit-card"
               style="color:var(--color-accent);margin-right:6px"></i>
            Form Edit Pembayaran
        </h3>
    </div>

    <div class="card-body">
        <form action="/pembayaran/{{ $pembayaran->id_pembayaran }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Booking Servis</label>
                    <select name="id_booking" id="id_booking" class="form-control"
                        {{ $pembayaran->status === 'lunas' ? 'disabled' : '' }}>
                        <option value="">-- Pilih Booking --</option>
                        @foreach ($booking as $b)
                            <option value="{{ $b->id_booking }}"
                                data-harga="{{ $b->layananServis->harga ?? 0 }}"
                                {{ old('id_booking', $pembayaran->id_booking) == $b->id_booking ? 'selected' : '' }}>
                                #{{ $b->id_booking }} — {{ $b->pelanggan->nama ?? '-' }}
                                ({{ $b->status }})
                            </option>
                        @endforeach
                    </select>
                    @if ($pembayaran->status === 'lunas')
                        <input type="hidden" name="id_booking"
                            value="{{ $pembayaran->id_booking }}">
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Order Spare Part</label>
                    <select name="id_order" id="id_order" class="form-control"
                        {{ $pembayaran->status === 'lunas' ? 'disabled' : '' }}>
                        <option value="">-- Pilih Order --</option>
                        @foreach ($orders as $o)
                            <option value="{{ $o->id_order }}"
                                data-harga="{{ $o->total_harga ?? 0 }}"
                                {{ old('id_order', $pembayaran->id_order) == $o->id_order ? 'selected' : '' }}>
                                #{{ $o->id_order }} — {{ $o->pelanggan->nama ?? '-' }}
                                ({{ $o->status ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @if ($pembayaran->status === 'lunas')
                        <input type="hidden" name="id_order"
                            value="{{ $pembayaran->id_order }}">
                    @endif
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
                                {{ old('metode_pembayaran_id', $pembayaran->metode_pembayaran_id) == $m->id_metode ? 'selected' : '' }}>
                                {{ $m->nama_metode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Promo</label>
                    <select name="id_promo" id="id_promo" class="form-control"
                        {{ $pembayaran->status === 'lunas' ? 'disabled' : '' }}>
                        <option value="">-- Tanpa Promo --</option>
                        @foreach ($promo as $p)
                            <option value="{{ $p->id_promo }}"
                                data-jenis="{{ strtolower($p->jenis_diskon) }}"
                                data-nilai="{{ $p->nilai_diskon }}"
                                data-minimal="{{ $p->minimal_transaksi }}"
                                data-mulai="{{ $p->periode_mulai }}"
                                data-selesai="{{ $p->periode_selesai }}"
                                data-kuota="{{ $p->kuota }}"
                                {{ old('id_promo', $pembayaran->id_promo) == $p->id_promo ? 'selected' : '' }}>
                                {{ $p->kode_promo }} — {{ ucfirst($p->jenis_diskon) }}
                                {{ strtolower($p->jenis_diskon) === 'persen'
                                    ? $p->nilai_diskon . '%'
                                    : 'Rp ' . number_format($p->nilai_diskon, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @if ($pembayaran->status === 'lunas')
                        <input type="hidden" name="id_promo"
                            value="{{ $pembayaran->id_promo }}">
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        Jumlah Bayar (Rp) <span style="color:red">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                        value="{{ old('jumlah', $pembayaran->jumlah) }}"
                        min="0" step="0.01" required readonly>
                    <small style="color:var(--color-text-muted)">
                        Nominal dihitung otomatis dari transaksi dan promo.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Status <span style="color:red">*</span>
                    </label>
                    <select name="status" class="form-control" required
                        {{ $pembayaran->status === 'lunas' ? 'disabled' : '' }}>
                        @foreach (['menunggu', 'lunas', 'gagal'] as $s)
                            <option value="{{ $s }}"
                                {{ old('status', $pembayaran->status) == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    @if ($pembayaran->status === 'lunas')
                        <input type="hidden" name="status" value="lunas">
                    @endif
                </div>
            </div>

            <div id="rincianPembayaran"
                style="padding:16px;margin-bottom:20px;border:1px solid var(--color-border,#ddd);border-radius:8px">
                <h4 style="margin:0 0 12px">Rincian Pembayaran</h4>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span>Harga awal</span>
                    <strong id="hargaAwal">-</strong>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span>Diskon</span>
                    <strong id="nilaiPotongan">-</strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:1px solid var(--color-border,#ddd)">
                    <span>Total pembayaran</span>
                    <strong id="totalAkhir" style="color:var(--color-accent)">-</strong>
                </div>
                <p id="pesanPromo" style="margin:10px 0 0;font-size:0.875rem"></p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control"
                        value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Bukti Pembayaran</label>
                    <input type="text" name="bukti_pembayaran" class="form-control"
                        value="{{ old('bukti_pembayaran', $pembayaran->bukti_pembayaran) }}"
                        placeholder="URL/nomor referensi (opsional)">
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="/pembayaran" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const booking = document.getElementById('id_booking');
    const order = document.getElementById('id_order');
    const promo = document.getElementById('id_promo');
    const jumlah = document.getElementById('jumlah');
    const hargaAwal = document.getElementById('hargaAwal');
    const potonganEl = document.getElementById('nilaiPotongan');
    const totalEl = document.getElementById('totalAkhir');
    const pesan = document.getElementById('pesanPromo');

    const rupiah = n => 'Rp' + Math.round(n).toLocaleString('id-ID');

    function hitung() {
        const sumber = booking.value ? booking : order;
        const opsi = sumber.options[sumber.selectedIndex];
        const harga = Number(opsi?.dataset.harga || 0);
        let potongan = 0;
        let valid = true;
        let keterangan = 'Tidak menggunakan promo.';

        if (booking.value && order.value) {
            valid = false;
            keterangan = 'Pilih satu sumber transaksi saja.';
        } else if (harga <= 0) {
            valid = false;
            keterangan = 'Harga transaksi tidak tersedia.';
        } else if (promo.value) {
            const p = promo.options[promo.selectedIndex];
            const jenis = (p.dataset.jenis || '').toLowerCase();
            const nilai = Number(p.dataset.nilai || 0);
            const minimal = Number(p.dataset.minimal || 0);

            const now = new Date();
            const today = [
                now.getFullYear(),
                String(now.getMonth() + 1).padStart(2, '0'),
                String(now.getDate()).padStart(2, '0')
            ].join('-');

            if (harga < minimal) {
                valid = false;
                keterangan = 'Minimal transaksi promo adalah ' + rupiah(minimal) + '.';
            } else if ((p.dataset.mulai && today < p.dataset.mulai) ||
                       (p.dataset.selesai && today > p.dataset.selesai)) {
                valid = false;
                keterangan = 'Promo belum berlaku atau sudah kedaluwarsa.';
            } else if (jenis === 'persen') {
                potongan = harga * nilai / 100;
                keterangan = 'Diskon ' + nilai + '% diterapkan.';
            } else if (jenis === 'nominal') {
                potongan = nilai;
                keterangan = 'Potongan ' + rupiah(nilai) + ' diterapkan.';
            } else {
                valid = false;
                keterangan = 'Jenis promo tidak dikenali.';
            }
        }

        potongan = Math.min(potongan, harga);
        const total = Math.max(0, harga - potongan);

        hargaAwal.textContent = rupiah(harga);
        potonganEl.textContent = rupiah(potongan);
        totalEl.textContent = valid ? rupiah(total) : '-';
        pesan.textContent = keterangan;
        pesan.style.color = valid ? '#15803d' : '#dc2626';

        if (valid) {
            jumlah.value = total.toFixed(2);
        } else {
            jumlah.value = '';
        }
    }

    if (booking && order && promo) {
        booking.addEventListener('change', function () {
            if (this.value) order.value = '';
            hitung();
        });

        order.addEventListener('change', function () {
            if (this.value) booking.value = '';
            hitung();
        });

        promo.addEventListener('change', hitung);
        hitung();
    }
});
</script>
@endsection
