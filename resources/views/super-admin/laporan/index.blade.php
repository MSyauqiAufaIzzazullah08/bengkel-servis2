@extends('layouts.app')

@section('title', 'Laporan Eksekutif')

@section('styles')
<style>
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
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }
    .stat-label {
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6b7280;
    }
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2563eb;
        line-height: 1.2;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .disclaimer {
        background: #fefce8;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: .75rem 1rem;
        font-size: .8rem;
        color: #92400e;
    }
    tfoot td { font-weight: 700; background: #f1f5f9; }
</style>
@endsection

@section('breadcrumb')
    <span>Super Admin</span>
    <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
    <span class="breadcrumb-current">Laporan Eksekutif</span>
@endsection

@section('content')
<div style="display:flex;flex-direction:column;gap:1.5rem;">

    {{-- FILTER --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('laporan.superadmin') }}" style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;">
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
                <a href="{{ route('laporan.superadmin') }}" class="btn btn-secondary" style="padding:.5rem 1.25rem;margin-left:.5rem;">
                    Reset
                </a>
            </div>
            <div style="margin-left:auto;">
                <a href="{{ route('laporan.superadmin.pdf') }}?{{ http_build_query(request()->all()) }}"
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

    {{-- AGREGASI NASIONAL --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-calendar-check"></i> Grand Total Booking</div>
            <div class="stat-value">{{ number_format($grandTotalBooking) }}</div>
            <div style="font-size:.75rem;color:#6b7280;">Seluruh cabang</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-money-bill-wave"></i> Grand Total Pendapatan</div>
            <div class="stat-value" style="font-size:1.25rem;color:#059669;">
                Rp {{ number_format($grandTotalPendapatan, 0, ',', '.') }}
            </div>
            <div style="font-size:.75rem;color:#6b7280;">Bengkel + Home Service</div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <div class="chart-card">
            <div class="section-title">Pendapatan per Cabang</div>
            <canvas id="chartPendapatan" height="140"></canvas>
        </div>
        <div class="chart-card">
            <div class="section-title">Tren Booking 12 Bulan Terakhir</div>
            <canvas id="chartTren" height="140"></canvas>
        </div>
    </div>

    {{-- TABEL PERBANDINGAN CABANG --}}
    <div class="table-card">
        <div class="section-title"><i class="fas fa-building"></i> Perbandingan Performa Cabang</div>
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;">
                    <th style="padding:.6rem .75rem;text-align:left;border-bottom:1px solid #e5e7eb;">Cabang</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Total Booking</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Servis Selesai</th>
                    <th style="padding:.6rem .75rem;text-align:right;border-bottom:1px solid #e5e7eb;">Pend. Bengkel</th>
                    <th style="padding:.6rem .75rem;text-align:right;border-bottom:1px solid #e5e7eb;">Pend. Home Service</th>
                    <th style="padding:.6rem .75rem;text-align:right;border-bottom:1px solid #e5e7eb;">Total Pendapatan</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Home Service</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Avg Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataCabang as $row)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:.55rem .75rem;font-weight:600;">{{ $row['nama_cabang'] }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">{{ number_format($row['total_booking']) }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">{{ number_format($row['servis_selesai']) }}</td>
                    <td style="padding:.55rem .75rem;text-align:right;">Rp {{ number_format($row['pendapatan_bengkel'], 0, ',', '.') }}</td>
                    <td style="padding:.55rem .75rem;text-align:right;">Rp {{ number_format($row['pendapatan_home_service'], 0, ',', '.') }}</td>
                    <td style="padding:.55rem .75rem;text-align:right;font-weight:700;">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">{{ number_format($row['hs_jumlah']) }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">
                        @if ($row['avg_rating'])
                            <span style="color:#f59e0b;"><i class="fas fa-star"></i></span>
                            {{ $row['avg_rating'] }}
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:1rem;color:#9ca3af;">Tidak ada data cabang.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding:.6rem .75rem;font-weight:700;">TOTAL NASIONAL</td>
                    <td style="padding:.6rem .75rem;text-align:center;font-weight:700;">{{ number_format($grandTotalBooking) }}</td>
                    <td colspan="3"></td>
                    <td style="padding:.6rem .75rem;text-align:right;font-weight:700;">Rp {{ number_format($grandTotalPendapatan, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- TOP 10 TEKNISI NASIONAL --}}
    <div class="table-card">
        <div class="section-title"><i class="fas fa-trophy"></i> Top 10 Teknisi Nasional</div>
        @if ($topTeknisi->isEmpty())
            <p style="color:#6b7280;font-size:.875rem;">Belum ada data teknisi.</p>
        @else
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;">
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">#</th>
                    <th style="padding:.6rem .75rem;text-align:left;border-bottom:1px solid #e5e7eb;">Nama Teknisi</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Jumlah Servis</th>
                    <th style="padding:.6rem .75rem;text-align:center;border-bottom:1px solid #e5e7eb;">Avg Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topTeknisi as $i => $t)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:.55rem .75rem;text-align:center;">
                        @if ($i === 0)
                            <span style="color:#f59e0b;font-size:1rem;"><i class="fas fa-medal"></i></span>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </td>
                    <td style="padding:.55rem .75rem;font-weight:{{ $i === 0 ? '700' : '400' }};">{{ $t->nama_teknisi }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">{{ $t->jumlah_servis }}</td>
                    <td style="padding:.55rem .75rem;text-align:center;">
                        @if ($t->avg_rating)
                            <span style="color:#f59e0b;"><i class="fas fa-star"></i></span>
                            {{ number_format($t->avg_rating, 1) }}
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- DISCLAIMER --}}
    <div class="disclaimer">
        <i class="fas fa-info-circle"></i>
        <strong>Catatan:</strong>
        Laba bersih tidak ditampilkan karena data biaya operasional belum lengkap.
        Pendapatan bengkel dan home service dihitung secara terpisah untuk menghindari double-count.
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const dataCabang = @json($dataCabang);
    const cabangLabels = dataCabang.map(d => d.nama_cabang);
    const pendapatanValues = dataCabang.map(d => parseFloat(d.total_pendapatan) || 0);

    // Bar chart pendapatan per cabang
    new Chart(document.getElementById('chartPendapatan'), {
        type: 'bar',
        data: {
            labels: cabangLabels.length ? cabangLabels : ['Belum ada data'],
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: pendapatanValues.length ? pendapatanValues : [0],
                backgroundColor: 'rgba(5, 150, 105, 0.75)',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + (v >= 1000000 ? (v / 1000000).toFixed(1) + 'jt' : v.toLocaleString('id'))
                    }
                }
            }
        }
    });

    // Line chart tren booking 12 bulan
    const trenLabels = @json($trenLabels);
    const trenValues = @json($trenValues);

    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: trenLabels,
            datasets: [{
                label: 'Jumlah Booking',
                data: trenValues,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
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
