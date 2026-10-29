<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif – BengkelServis</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; background: #fff; padding: 24px; }
        h1 { font-size: 17px; font-weight: bold; margin-bottom: 4px; }
        .sub { font-size: 10px; color: #555; margin-bottom: 20px; }
        .section-title { font-size: 12px; font-weight: bold; margin: 20px 0 8px; border-bottom: 2px solid #2563eb; padding-bottom: 4px; color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f1f5f9; text-align: left; padding: 5px 8px; border: 1px solid #d1d5db; font-size: 10px; }
        td { padding: 5px 8px; border: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f9fafb; }
        tfoot td { font-weight: bold; background: #e0f2fe; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-row { display: flex; gap: 16px; margin-bottom: 20px; }
        .summary-box { flex: 1; border: 1px solid #d1d5db; border-radius: 6px; padding: 10px; }
        .summary-box .lbl { font-size: 9px; color: #6b7280; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
        .summary-box .val { font-size: 14px; font-weight: bold; color: #1e40af; }
        .disclaimer { font-size: 10px; color: #92400e; background: #fefce8; border: 1px solid #fde68a; padding: 8px 12px; border-radius: 4px; margin-top: 16px; }
        .note { font-size: 10px; color: #6b7280; margin-top: 20px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <h1>Laporan Eksekutif – BengkelServis</h1>
    <p class="sub">
        Periode: {{ $dateStart->format('d M Y') }} – {{ $dateEnd->format('d M Y') }}
        &nbsp;|&nbsp; Dicetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </p>

    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()" style="padding:6px 16px;background:#2563eb;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:11px;">
            <b>Cetak / Simpan PDF</b>
        </button>
    </div>

    {{-- RINGKASAN NASIONAL --}}
    <div class="section-title">Ringkasan Nasional</div>
    <div class="summary-row">
        <div class="summary-box">
            <div class="lbl">Grand Total Booking</div>
            <div class="val">{{ number_format($grandTotalBooking) }}</div>
        </div>
        <div class="summary-box">
            <div class="lbl">Grand Total Pendapatan</div>
            <div class="val" style="font-size:12px;">Rp {{ number_format($grandTotalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- PERBANDINGAN CABANG --}}
    <div class="section-title">Perbandingan Performa Cabang</div>
    <table>
        <thead>
            <tr>
                <th>Cabang</th>
                <th class="text-center">Booking</th>
                <th class="text-center">Selesai</th>
                <th class="text-right">Pend. Bengkel</th>
                <th class="text-right">Pend. HS</th>
                <th class="text-right">Total Pend.</th>
                <th class="text-center">HS</th>
                <th class="text-center">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataCabang as $row)
            <tr>
                <td>{{ $row['nama_cabang'] }}</td>
                <td class="text-center">{{ number_format($row['total_booking']) }}</td>
                <td class="text-center">{{ number_format($row['servis_selesai']) }}</td>
                <td class="text-right">Rp {{ number_format($row['pendapatan_bengkel'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row['pendapatan_home_service'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $row['hs_jumlah'] }}</td>
                <td class="text-center">{{ $row['avg_rating'] ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="color:#9ca3af;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td><strong>TOTAL NASIONAL</strong></td>
                <td class="text-center"><strong>{{ number_format($grandTotalBooking) }}</strong></td>
                <td colspan="3"></td>
                <td class="text-right"><strong>Rp {{ number_format($grandTotalPendapatan, 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    {{-- TOP 10 TEKNISI --}}
    <div class="section-title">Top 10 Teknisi Nasional</div>
    @if ($topTeknisi->isEmpty())
        <p style="color:#6b7280;">Tidak ada data teknisi.</p>
    @else
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Teknisi</th>
                <th class="text-center">Jumlah Servis</th>
                <th class="text-center">Avg Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topTeknisi as $i => $t)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $t->nama_teknisi }}</td>
                <td class="text-center">{{ $t->jumlah_servis }}</td>
                <td class="text-center">{{ $t->avg_rating ? number_format($t->avg_rating, 1) : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="disclaimer">
        <strong>Catatan:</strong>
        Laba bersih tidak ditampilkan karena data biaya operasional belum lengkap.
        Pendapatan bengkel dan home service dihitung secara terpisah untuk menghindari double-count.
    </div>

    <p class="note">* Laporan ini digenerate secara otomatis dari sistem BengkelServis pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }}.</p>

</body>
</html>
