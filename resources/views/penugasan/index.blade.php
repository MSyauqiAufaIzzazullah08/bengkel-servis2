
@extends('layouts.app')

@section('title', 'Penugasan Teknisi')

@section('breadcrumb')
    <span>Transaksi</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Penugasan Teknisi</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen Penugasan Teknisi</h1>
            <p class="page-subtitle">Data penugasan teknisi untuk booking servis</p>
        </div>

        <a href="/penugasan/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Penugasan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom:20px">
            <i class="fas fa-check-circle alert-icon"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom:20px">
            <i class="fas fa-times-circle alert-icon"></i>
            <div>{{ session('error') }}</div>
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
                <i class="fas fa-user-cog"
                   style="color:var(--color-accent);margin-right:6px"></i>
                Daftar Penugasan Teknisi

                <span class="badge badge-primary"
                      style="margin-left:8px;font-size:0.75rem">
                    {{ $penugasan->count() }} data
                </span>
            </h3>

            <div class="search-input-wrapper" style="max-width:280px">
                <i class="fas fa-search"></i>
                <input type="text"
                       id="cariPenugasan"
                       class="search-input"
                       placeholder="Cari booking, teknisi...">
            </div>
        </div>

        <div class="table-container">
            <table class="table" id="tabelPenugasan">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking</th>
                        <th>Kendaraan</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($penugasan as $index => $item)
                        <tr>
                            <td>
                                <span style="color:var(--color-text-muted);font-size:0.8rem">
                                    {{ $index + 1 }}
                                </span>
                            </td>

                            <td style="font-size:0.84rem">
                                <div style="font-weight:600">
                                    #{{ $item->bookingServis->id_booking ?? '-' }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--color-text-muted)">
                                    {{ $item->bookingServis->pelanggan->nama ?? '-' }}
                                </div>
                            </td>

                            <td style="font-size:0.84rem">
                                {{ $item->bookingServis->kendaraan->merk ?? '-' }}
                                {{ $item->bookingServis->kendaraan->model ?? '' }}
                            </td>

                            <td style="font-size:0.875rem;font-weight:600">
                                {{ $item->teknisi->nama ?? '-' }}
                            </td>

                            <td style="font-size:0.8rem;color:var(--color-text-muted)">
                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match($item->status) {
                                        'Assigned' => 'badge-warning',
                                        'In Progress' => 'badge-info',
                                        'Completed' => 'badge-success',
                                        default => 'badge-secondary',
                                    };

                                    $statusLabel = match($item->status) {
                                        'Assigned' => 'Ditugaskan',
                                        'In Progress' => 'Sedang Dikerjakan',
                                        'Completed' => 'Selesai',
                                        default => $item->status ?? '-',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td style="font-size:0.8rem;max-width:160px;
                                       white-space:nowrap;overflow:hidden;
                                       text-overflow:ellipsis"
                                title="{{ $item->catatan }}">
                                {{ $item->catatan ?? '-' }}
                            </td>

                            <td>
                                <div class="actions-cell">
                                    <a href="/penugasan/{{ $item->id_penugasan }}/edit"
                                       class="btn btn-accent btn-sm btn-icon"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if (!in_array($item->status, ['In Progress', 'Completed'], true))
                                        <form action="/penugasan/{{ $item->id_penugasan }}"
                                              method="POST"
                                              style="display:inline"
                                              onsubmit="return confirm('Hapus penugasan ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm btn-icon"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="table-empty">
                                    <i class="fas fa-user-cog"></i>
                                    <p>Belum ada data penugasan teknisi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-between align-center">
            <span class="text-xs text-muted">
                Menampilkan {{ $penugasan->count() }} penugasan dari database
            </span>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    const inputCari = document.getElementById('cariPenugasan');

    if (inputCari) {
        inputCari.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            const baris = document.querySelectorAll('#tabelPenugasan tbody tr');

            baris.forEach(function (tr) {
                const teks = tr.textContent.toLowerCase();
                tr.style.display = teks.includes(q) ? '' : 'none';
            });
        });
    }
</script>
@endsection
