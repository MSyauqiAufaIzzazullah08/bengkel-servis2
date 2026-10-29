@extends('layouts.app')

@section('title', 'Laporan Cabang')

@section('styles')
<style>
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }
    .stat-card .stat-label {
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--color-text-muted, #6b7280);
    }
    .stat-card .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--color-primary, #2563eb);
        line-height: 1.2;
    }
    .stat-card .stat-sub {
        font-size: .75rem;
        color: var(--color-text-muted, #6b7280);
    }
    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        margin-bottom: 1.5rem;
    }
    .chart-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }
    .table-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        overflow-x: auto;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--color-text, #111827);
    }
    .badge-hs {
        background: #eff6ff;
        color: #1d4ed8;
        font-size: .7rem;
        font-weight: 600;
        padding: .2rem .55rem;
        border-radius: 999px;
        white-space: nowrap;
    }
</style>
@endsection

@section('breadcrumb')
    <span>Admin Cabang</span>
    <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
    <span class="breadcrumb-current">Laporan Cabang</span>
@endsection

@section('content')
<div style="display:flex;flex-direction:column;gap:1.5rem;">

    {{-- FILTER --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('laporan.cabang') }}" style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;">
            <div>
                <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.3rem;">Periode</label>
                <select name="periode" style="padding:.45rem .75rem;border:1px solid #d1d5db;border-radius:8px;font-size:.875rem;">
                    <option value="harian"   {{ request('periode') === 'harian'   ? 'selected' : '' }}>Harian</option>
                    <option value="mingguan" {{ request('periode') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulanan"  {{ !request('periode') || request('periode') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan"  {{ request('periode') === 'tahunan'  ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            <div>
                <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.3rem;">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                       style="padding:.45rem .75rem;border:1px solid #d1d5db;border-radius:8px;font-size:.875rem;">
            </div>
            <div>
                <label style="font-size:.8rem;font-weight:600;display:block;margin-bottom:.3rem;">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                       style="padding:.45rem .75rem;border:1px solid #d1d5db;border-radius:8px;font-size:.875rem;">
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="padding:.5rem 1.25rem;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('laporan.cabang') }}" class="btn btn-secondary" style="padding:.5rem 1.25rem;margin-left:.5rem;">
                    Reset
                </a>
            </div>
            <div style="margin-left:auto;">
                <a href="{{ route('laporan.cabang.pdf') }}?{{ http_build_query(request()->all()) }}"
                   target="_blank"
                   class="btn btn-danger" style="padding:.5rem 1.25rem;">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
        <p style="font-size:.78rem;color:#6b7280;margin-top:.75rem;margin-bottom:0;">
            Periode: <strong>{{ $dateStart->format('d M Y') }}</strong> &ndash; <strong>{{ $dateEnd->format('d M Y') }}</strong>
        </p>
    </div>

    {{-- STAT CARDS --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-calendar-check"></i> Total Booking</span>
            <span class="stat-value">{{ number_format($totalBooking) }}</span>
            <span class="stat-sub">Dalam periode</span>
        </div>
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-check-circle"></i> Servis Selesai</span>
            <span class="stat-value" style="color:#16a34a;">{{ number_format($servisSelesai) }}</span>
            <span class="stat-sub">Status: Completed</span>
        </div>
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-spinner"></i> Sedang Berjalan</span>
            <span class="stat-value" style="color:#d97706;">{{ number_format($servisBerjalan) }}</span>
            <span class="stat-sub">Status: In Progress</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">
                <i class="fas fa-home"></i> Home Service
                <span class="badge-hs" style="margin-left:.3rem;">Terpisah</span>
            </span>
            <span class="stat-value" style="color:#7c3aed;">{{ number_format($homeServiceCount) }}</span>
            <span class="stat-sub">Kunjungan rumah</span>
        </div>
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-users"></i> Pelanggan Unik</span>
            <span class="stat-value">{{ number_format($jumlahPelanggan) }}</span>
            <span class="stat-sub">Distinct pelanggan</span>
        </div>
        <div class="stat-card">
            <span class="stat-label"><i class="fas fa-money-bill-wave"></i> Total Pendapatan</span>
            <span class="stat-value" style="color:#059669;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            <span class="stat-sub">Bengkel + Home Service</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">
                <i class="fas fa-wrench"></i> Pendapatan Bengkel
            </span>
            <span class="stat-value" style="color:#2563eb;font-size:1.2rem;">Rp {{ number_format($pendapatanBengkel, 0, ',', '.') }}</span>
            <span class="stat-sub">Booking servis lunas</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">
                <i class="fas fa-house-user"></i> Pend. Home Service
                <span class="badge-hs" style="margin-left:.3rem;">Terpisah</span>
            </span>
            <span class="stat-value" style="color:#7c3aed;font-size:1.2rem;">Rp {{ number_format($pendapatanHomeService, 0, ',', '.') }}</span>
            <span class="stat-sub">Home service lunas</span>
        </div>
    </div>

    {{-- CHARTS --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
        <div class="chart-card">
            <div class="section-title">Booking per Hari</div>
            <canvas id="chartBooking" height="120"></canvas>
        </div>
        <div class="chart-card">
            <div class="section-title">Status Booking</div>
            <canvas id="chartStatus" height="120"></canvas>
        </div>
    </div>

    {{-- TABEL PERFORMA TEKNISI --}}
    <div class="table-card">
        <div class="section-title"><i class="fas fa-hard-hat"></i> Performa Teknisi</div>
        @if ($performaTeknisi->isEmpty())
            <p style="color:#6b7280;font-size:.875rem;">Belum ada data teknisi untuk periode ini.</p>
        @else
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;">
                    <th style="padding:.6rem .75rem;text-align:left;border-bottom:1px solid #e5e7eb;">Nama Teknisi</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Jumlah Servis</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Avg Rating</th>
                    <th style="padding:.6rem .75rem;text-align:right;border-bottom:1px solid #e5e7eb;">Total Komisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($performaTeknisi as $t)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:.55rem .75rem;">{{ $t->nama_teknisi }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">{{ $t->jumlah_servis }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">
                        @if ($t->avg_rating)
                            <span style="color:#f59e0b;">
                                <i class="fas fa-star"></i>
                            </span>
                            {{ number_format($t->avg_rating, 1) }}
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td style="padding:.55rem .75rem;text-align:right;">
                        Rp {{ number_format($t->total_komisi ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // --- Bar Chart: Booking per Hari ---
    const chartData = @json($chartData);
    const labels = chartData.map(d => d.tanggal);
    const values = chartData.map(d => d.jumlah);

    new Chart(document.getElementById('chartBooking'), {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['Belum ada data'],
            datasets: [{
                label: 'Jumlah Booking',
                data: values.length ? values : [0],
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // --- Pie Chart: Status Booking ---
    const statusData = @json($statusData);
    const statusLabels = statusData.map(d => d.status);
    const statusValues = statusData.map(d => d.jumlah);
    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'];

    new Chart(document.getElementById('chartStatus'), {
        type: 'pie',
        data: {
            labels: statusLabels.length ? statusLabels : ['Belum ada data'],
            datasets: [{
                data: statusValues.length ? statusValues : [1],
                backgroundColor: colors.slice(0, Math.max(statusLabels.length, 1)),
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
        }
    });
})();
</script>
@endsection
