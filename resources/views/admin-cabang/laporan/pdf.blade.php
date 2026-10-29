<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Cabang – BengkelServis</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; background: #fff; padding: 24px; }
        h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .sub { font-size: 11px; color: #555; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; margin: 20px 0 8px; border-bottom: 2px solid #2563eb; padding-bottom: 4px; color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #f1f5f9; text-align: left; padding: 6px 10px; border: 1px solid #d1d5db; font-size: 11px; }
        td { padding: 6px 10px; border: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
        .summary-box { border: 1px solid #d1d5db; border-radius: 6px; padding: 10px; }
        .summary-box .lbl { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
        .summary-box .val { font-size: 14px; font-weight: bold; color: #1e40af; }
        .note { font-size: 10px; color: #6b7280; margin-top: 24px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <h1>Laporan Cabang – BengkelServis</h1>
    <p class="sub">
        Periode: {{ $dateStart->format('d M Y') }} – {{ $dateEnd->format('d M Y') }}
        &nbsp;|&nbsp; Dicetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </p>

    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()" style="padding:6px 16px;background:#2563eb;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;">
            <b>Cetak / Simpan PDF</b>
        </button>
    </div>

    {{-- RINGKASAN --}}
    <div class="section-title">Ringkasan</div>
    <div class="summary-grid">
        <div class="summary-box">
            <div class="lbl">Total Booking</div>
            <div class="val">{{ number_format($totalBooking) }}</div>
        </div>
        <div class="summary-box">
            <div class="lbl">Servis Selesai</div>
            <div class="val">{{ number_format($servisSelesai) }}</div>
        </div>
        <div class="summary-box">
            <div class="lbl">Home Service</div>
            <div class="val">{{ number_format($homeServiceCount) }}</div>
        </div>
        <div class="summary-box">
            <div class="lbl">Total Pendapatan</div>
            <div class="val" style="font-size:12px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <tr>
            <th>Keterangan</th>
            <th class="text-right">Nilai</th>
        </tr>
        <tr>
            <td>Pendapatan Bengkel (Booking)</td>
            <td class="text-right">Rp {{ number_format($pendapatanBengkel, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pendapatan Home Service</td>
            <td class="text-right">Rp {{ number_format($pendapatanHomeService, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Pendapatan</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    {{-- PERFORMA TEKNISI --}}
    <div class="section-title">Performa Teknisi</div>
    @if ($performaTeknisi->isEmpty())
        <p style="color:#6b7280;">Tidak ada data teknisi untuk periode ini.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Teknisi</th>
                <th class="text-center">Jumlah Servis</th>
                <th class="text-center">Avg Rating</th>
                <th class="text-right">Total Komisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($performaTeknisi as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->nama_teknisi }}</td>
                <td class="text-center">{{ $t->jumlah_servis }}</td>
                <td class="text-center">{{ $t->avg_rating ? number_format($t->avg_rating, 1) : '—' }}</td>
                <td class="text-right">Rp {{ number_format($t->total_komisi ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <p class="note">* Laporan ini digenerate secara otomatis dari sistem BengkelServis. Data sudah dipisahkan antara booking bengkel dan home service untuk menghindari double-count.</p>

</body>
</html>
