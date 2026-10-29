@extends('layouts.app')

@section('title', 'Detail Servis')

@section('breadcrumb')
    <span>Manajemen Servis</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Detail Servis</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Servis</h1>
            <p class="page-subtitle">Rincian layanan yang digunakan dalam setiap booking</p>
        </div>
        <a href="/detailservis/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Detail
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
                <i class="fas fa-clipboard-list" style="color:var(--color-accent);margin-right:6px"></i>
                Daftar Detail Servis
                <span class="badge badge-primary" style="margin-left:8px;font-size:0.75rem">
                    {{ $detail->count() }} data
                </span>
            </h3>
            <div class="search-input-wrapper" style="max-width:280px">
                <i class="fas fa-search"></i>
                <input type="text" id="cariDetail" class="search-input"
                       placeholder="Cari pelanggan, layanan...">
            </div>
        </div>

        <div class="table-container">
            <table class="table" id="tabelDetail">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking</th>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detail as $index => $item)
                        <tr>
                            <td><span style="color:var(--color-text-muted);font-size:0.8rem">{{ $index + 1 }}</span></td>
                            <td style="font-size:0.84rem">
                                <div style="font-weight:600">{{ $item->bookingServis->pelanggan->nama ?? '-' }}</div>
                                <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                    {{ $item->bookingServis->kendaraan->merk ?? '' }}
                                    {{ $item->bookingServis->kendaraan->model ?? '' }}
                                    &bull; Booking #{{ $item->booking_id }}
                                </div>
                            </td>
                            <td style="font-size:0.84rem">{{ $item->layananServis->nama_layanan ?? '-' }}</td>
                            {{-- [DISABLED - Kolom Spare Part dihapus dari tampilan. id_sparepart di database tetap ada.] --}}
                            <td style="font-size:0.85rem;font-weight:600">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </td>
                            <td style="font-size:0.8rem;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                title="{{ $item->catatan }}">
                                {{ $item->catatan ?? '-' }}
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="/detailservis/{{ $item->id_detail }}/edit"
                                       class="btn btn-accent btn-sm btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/detailservis/{{ $item->id_detail }}" method="POST"
                                          style="display:inline"
                                          onsubmit="return confirm('Hapus detail servis ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="table-empty">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>Belum ada data detail servis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-between align-center">
            <span class="text-xs text-muted">Menampilkan {{ $detail->count() }} data detail servis</span>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    var inputCari = document.getElementById('cariDetail');
    if (inputCari) {
        inputCari.addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#tabelDetail tbody tr').forEach(function (tr) {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
</script>
@endsection
