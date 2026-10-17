@extends('layouts.app')

@section('title', 'Catat Pembayaran Home Service')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold">Catat Pembayaran Home Service</h2>
        <p class="text-muted mb-0">
            Pilih servis yang sudah selesai dan catat pembayarannya.
        </p>
    </div>

    <div class="mb-3">
        <a href="{{ route('pembayaran-home-service.index') }}"
           class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data berikut:</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Form Pembayaran</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('pembayaran-home-service.store') }}"
                  method="POST">
                @csrf

                {{-- Pilih Home Service --}}
                <div class="mb-3">
                    <label for="id_home_service" class="form-label">
                        Home Service <span class="text-danger">*</span>
                    </label>

                    <select name="id_home_service"
                            id="id_home_service"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Home Service --</option>

                        @foreach ($homeServis as $item)
                            @php
                                $layanan = $item->layananServis;
                                $harga = $layanan ? (float) $layanan->harga : 0;
                            @endphp

                            <option
                                value="{{ $item->id_home_service }}"
                                data-harga="{{ $harga }}"
                                @selected((string) old('id_home_service') === (string) $item->id_home_service)
                            >
                                #{{ $item->id_home_service }}
                                — {{ $item->pelanggan->nama ?? 'Pelanggan' }}
                                — {{ $layanan->nama_layanan ?? 'Layanan belum tersedia' }}
                                — Rp {{ number_format($harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">
                        Hanya Home Service berstatus Completed yang ditampilkan.
                    </small>

                    @if ($homeServis->isEmpty())
                        <div class="alert alert-info mt-2 mb-0">
                            Belum ada Home Service yang bisa dibayar.
                            Pastikan montir sudah menyelesaikan servis.
                        </div>
                    @endif
                </div>

                {{-- Harga layanan --}}
                <div class="mb-3">
                    <label for="harga_layanan" class="form-label">
                        Harga Layanan
                    </label>
                    <input type="text"
                           id="harga_layanan"
                           class="form-control"
                           value="Rp 0"
                           readonly>
                </div>

                {{-- Metode pembayaran --}}
                <div class="mb-3">
                    <label for="metode_pembayaran_id" class="form-label">
                        Metode Pembayaran <span class="text-danger">*</span>
                    </label>

                    <select name="metode_pembayaran_id"
                            id="metode_pembayaran_id"
                            class="form-select"
                            required>
                        <option value="">-- Pilih metode --</option>

                        @foreach ($metode as $item)
                            <option
                                value="{{ $item->id_metode }}"
                                @selected((string) old('metode_pembayaran_id') === (string) $item->id_metode)
                            >
                                {{ $item->nama_metode ?? $item->nama ?? 'Metode pembayaran' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Promo --}}
                <div class="mb-3">
                    <label for="id_promo" class="form-label">Promo</label>

                    <select name="id_promo"
                            id="id_promo"
                            class="form-select">
                        <option value="">Tanpa promo</option>

                        @foreach ($promo as $item)
                            <option
                                value="{{ $item->id_promo }}"
                                data-diskon="{{ $item->nilai_diskon }}"
                                @selected((string) old('id_promo') === (string) $item->id_promo)
                            >
                                {{ $item->kode_promo }}
                                — Diskon {{ $item->nilai_diskon }}%
                            </option>
                        @endforeach
                    </select>

                    <small class="text-muted">
                        Harga akhir tetap dihitung ulang oleh server.
                    </small>
                </div>

                {{-- Total --}}
                <div class="mb-3">
                    <label for="total_bayar" class="form-label">
                        Perkiraan Total
                    </label>
                    <input type="text"
                           id="total_bayar"
                           class="form-control fw-bold"
                           value="Rp 0"
                           readonly>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status Pembayaran <span class="text-danger">*</span>
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select"
                            required>
                        <option value="Pending"
                            @selected(old('status', 'lunas') === 'Pending')>
                            Pending
                        </option>

                        <option value="lunas"
                            @selected(old('status', 'lunas') === 'lunas')>
                            Lunas
                        </option>
                    </select>
                </div>

                {{-- Tanggal bayar --}}
                <div class="mb-3">
                    <label for="tanggal_bayar" class="form-label">
                        Tanggal Bayar
                    </label>

                    <input type="datetime-local"
                           name="tanggal_bayar"
                           id="tanggal_bayar"
                           class="form-control"
                           value="{{ old('tanggal_bayar') }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const servis = document.getElementById('id_home_service');
    const promo = document.getElementById('id_promo');
    const hargaInput = document.getElementById('harga_layanan');
    const totalInput = document.getElementById('total_bayar');

    function formatRupiah(nilai) {
        return 'Rp ' + Number(nilai).toLocaleString('id-ID', {
            maximumFractionDigits: 0
        });
    }

    function hitungTotal() {
        const optionServis = servis.options[servis.selectedIndex];
        const harga = Number(optionServis?.dataset.harga || 0);

        const optionPromo = promo.options[promo.selectedIndex];
        const diskon = Number(optionPromo?.dataset.diskon || 0);

        const total = Math.max(
            0,
            harga - (harga * diskon / 100)
        );

        hargaInput.value = formatRupiah(harga);
        totalInput.value = formatRupiah(total);
    }

    servis.addEventListener('change', hitungTotal);
    promo.addEventListener('change', hitungTotal);

    hitungTotal();
});
</script>
@endsection
