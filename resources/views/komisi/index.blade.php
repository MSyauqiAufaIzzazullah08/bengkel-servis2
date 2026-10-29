@extends('layouts.app')

@section('title', 'Data Komisi Teknisi')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Data Komisi Teknisi</h2>
        <p class="text-muted mb-0">Kelola dan tandai pembayaran komisi teknisi.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('komisi.index') }}" class="row g-3 align-items-end">
                @if ((int) session('role_id') === 1)
                    <div class="col-md-3">
                        <label class="form-label">Cabang</label>
                        <select name="cabang_id" class="form-select">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangList as $cabang)
                                <option value="{{ $cabang->id_cabang }}"
                                    {{ request('cabang_id') == $cabang->id_cabang ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-3">
                    <label class="form-label">Periode (Bulan)</label>
                    <input type="month" name="periode" class="form-control"
                           value="{{ request('periode') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="belum_dibayar" {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>
                            Belum Dibayar
                        </option>
                        <option value="sudah_dibayar" {{ request('status') === 'sudah_dibayar' ? 'selected' : '' }}>
                            Sudah Dibayar
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Teknisi</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Tanggal Dibayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($komisi as $item)
                            <tr>
                                <td>{{ $komisi->firstItem() + $loop->index }}</td>
                                <td>{{ $item->teknisi?->nama ?? '-' }}</td>
                                <td>
                                    @if ($item->is_bonus_target)
                                        <span class="badge bg-warning text-dark">Bonus Target</span>
                                    @elseif ($item->tipe_servis === 'home_service')
                                        <span class="badge bg-info text-dark">Home Service</span>
                                    @else
                                        <span class="badge bg-secondary">Per Servis</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->nominal_komisi, 0, ',', '.') }}</td>
                                <td>
                                    {{ $item->periode_bulan
                                        ? \Carbon\Carbon::parse($item->periode_bulan)->isoFormat('MMMM YYYY')
                                        : '-' }}
                                </td>
                                <td>
                                    @if ($item->status_pembayaran === 'sudah_dibayar')
                                        <span class="badge bg-success">Sudah Dibayar</span>
                                    @else
                                        <span class="badge bg-danger">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->tanggal_dibayar
                                        ? \Carbon\Carbon::parse($item->tanggal_dibayar)->isoFormat('D MMM YYYY HH:mm')
                                        : '-' }}
                                </td>
                                <td>
                                    @if ($item->status_pembayaran !== 'sudah_dibayar')
                                        <form action="{{ route('komisi.bayar', $item->id_komisi) }}" method="POST"
                                              onsubmit="return confirm('Tandai komisi ini sebagai sudah dibayar?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check me-1"></i> Bayar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Tidak ada data komisi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $komisi->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
