@extends('layouts.app')

@section('title', 'Pembayaran')

@section('breadcrumb')
    <span>Keuangan</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Daftar Pembayaran</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Pembayaran</h1>
        <p class="page-subtitle">Data transaksi pembayaran dari database</p>
    </div>
    <a href="/pembayaran/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pembayaran
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success" style="margin-bottom:20px">
        <i class="fas fa-check-circle alert-icon"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

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
            Daftar Pembayaran
            <span class="badge badge-primary"
                  style="margin-left:8px;font-size:0.75rem">
                {{ $pembayaran->count() }} data
            </span>
        </h3>

        <div class="search-input-wrapper" style="max-width:280px">
            <i class="fas fa-search"></i>
            <input type="text" id="cariPembayaran" class="search-input"
                   placeholder="Cari pelanggan, metode, status...">
        </div>
    </div>

    <div class="table-container">
        <table class="table" id="tabelPembayaran">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Jenis</th>
                    <th>Metode</th>
                    <th>Promo</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tgl Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($pembayaran as $index => $item)
                <tr>
                    <td>
                        <span style="color:var(--color-text-muted);font-size:0.8rem">
                            {{ $index + 1 }}
                        </span>
                    </td>

                    <td style="font-size:0.84rem">
                        @if ($item->id_booking && $item->bookingServis)
                            {{ $item->bookingServis->pelanggan->nama ?? '-' }}
                            <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                Booking #{{ $item->id_booking }}
                            </div>

                        @elseif ($item->id_home_service && $item->homeServis)
                            {{ $item->homeServis->pelanggan->nama ?? '-' }}
                            <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                Home Service #{{ $item->id_home_service }}
                            </div>

                        @elseif ($item->id_order && $item->orderSparePart)
                            {{ $item->orderSparePart->pelanggan->nama ?? '-' }}
                            <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                Order #{{ $item->id_order }}
                            </div>

                        @else
                            <span style="color:var(--color-text-muted)">-</span>
                        @endif
                    </td>

                    <td>
                        @if ($item->id_booking)
                            <span class="badge badge-primary">Booking</span>
                        @elseif ($item->id_home_service)
                            <span class="badge badge-info">Home Service</span>
                        @elseif ($item->id_order)
                            <span class="badge badge-secondary">Spare Part</span>
                        @else
                            <span class="badge">-</span>
                        @endif
                    </td>

                    <td style="font-size:0.84rem">
                        {{ $item->metodePembayaran->nama_metode ?? '-' }}
                    </td>

                    <td style="font-size:0.82rem">
                        {{ $item->promo->kode_promo ?? '-' }}
                    </td>

                    <td style="font-size:0.85rem;font-weight:600">
                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>

                    <td>
                        @php
                            $statusColor = match(strtolower($item->status ?? '')) {
                                'lunas' => 'badge-success',
                                'pending', 'menunggu' => 'badge-warning',
                                'gagal' => 'badge-danger',
                                default => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusColor }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>

                    <td style="font-size:0.8rem;color:var(--color-text-muted)">
                        {{ $item->tanggal_bayar
                            ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y')
                            : '-' }}
                    </td>

                    <td>
                        <div class="actions-cell">
                            <a href="/pembayaran/{{ $item->id_pembayaran }}/edit"
                               class="btn btn-accent btn-sm btn-icon"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="/pembayaran/{{ $item->id_pembayaran }}"
                                  method="POST"
                                  style="display:inline"
                                  onsubmit="return confirm('Hapus data pembayaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm btn-icon"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="table-empty">
                            <i class="fas fa-credit-card"></i>
                            <p>Belum ada data pembayaran.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-between align-center">
        <span class="text-xs text-muted">
            Menampilkan {{ $pembayaran->count() }} data pembayaran
        </span>
    </div>
</div>
@endsection

@section('scripts')
<script>
const inputCari = document.getElementById('cariPembayaran');

if (inputCari) {
    inputCari.addEventListener('input', function () {
        const q = this.value.toLowerCase();

        document.querySelectorAll('#tabelPembayaran tbody tr')
            .forEach(function (tr) {
                tr.style.display =
                    tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
    });
}
</script>
@endsection
