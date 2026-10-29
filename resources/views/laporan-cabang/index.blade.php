@extends('layouts.app')

@section('title', 'Laporan Cabang')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Laporan Operasional Cabang</h2>
            <p class="text-muted mb-0">{{ $cabang->nama_cabang ?? 'Cabang Anda' }} &mdash; {{ $dari }} s/d {{ $sampai }}</p>
        </div>
        <a href="{{ route('laporan-cabang.pdf', ['dari' => $dari, 'sampai' => $sampai]) }}"
           class="btn btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan-cabang.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ $dari }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Kartu ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Booking</p>
                    <h2 class="fw-bold text-primary mb-0">{{ number_format($totalBooking) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Servis Selesai</p>
                    <h2 class="fw-bold text-success mb-0">{{ number_format($servisSelesai) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Servis Berjalan</p>
                    <h2 class="fw-bold text-warning mb-0">{{ number_format($servisBerjalan) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Pelanggan Unik</p>
                    <h2 class="fw-bold text-info mb-0">{{ number_format($jumlahPelanggan) }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Pendapatan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Pendapatan Bengkel</p>
                    <h3 class="fw-bold text-success mb-0">Rp {{ number_format($pendapatanBengkel, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Home Service <span class="badge bg-info text-dark ms-1">Terpisah</span></p>
                    <h3 class="fw-bold text-info mb-0">Rp {{ number_format($pendapatanHomeService, 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ $jumlahHomeService }} layanan</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 border-start border-3 border-success">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Pendapatan</p>
                    <h3 class="fw-bold text-success mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    <small class="text-muted">Bengkel + Home Service</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Tren Booking Harian</h5>
        </div>
        <div class="card-body">
            <canvas id="chartBooking" height="80"></canvas>
        </div>
    </div>

    {{-- Performa Teknisi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Performa Teknisi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Teknisi</th>
                            <th>Servis Selesai</th>
                            <th>Rata-rata Rating</th>
                            <th>Total Komisi Dibayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($performaTeknisi as $i => $t)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $t->nama }}</td>
                                <td>{{ $t->jumlah_servis_selesai }}</td>
                                <td>
                                    @if ($t->avg_rating > 0)
                                        <span class="text-warning">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="fas fa-star{{ $s <= round($t->avg_rating) ? '' : '-half-alt' }}"
                                                   style="font-size:0.8rem"></i>
                                            @endfor
                                        </span>
                                        {{ number_format($t->avg_rating, 1) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($t->total_komisi, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada data performa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    const labels = @json(array_keys($chartData));
    const values = @json(array_values($chartData));
    const ctx = document.getElementById('chartBooking');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Booking',
                data: values,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
})();
</script>
@endsection
