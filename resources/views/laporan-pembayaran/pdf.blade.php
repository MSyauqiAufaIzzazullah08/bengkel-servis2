
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran BengkelServis</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }
        .summary {
            width: 100%;
            margin-bottom: 18px;
        }
        .summary td {
            border: 1px solid #bbb;
            padding: 10px;
            width: 50%;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        .data th, .data td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
        .data th {
            background: #e8eef5;
        }
        .number {
            text-align: right !important;
            white-space: nowrap;
        }
        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>LAPORAN PEMBAYARAN</h1>
    <div class="subtitle">Sistem Manajemen BengkelServis</div>

    <p>
        <strong>Periode:</strong>
        {{ $tanggalMulai ?: 'Semua tanggal' }}
        sampai
        {{ $tanggalSelesai ?: 'Semua tanggal' }}
    </p>

    <table class="summary">
        <tr>
            <td>
                <strong>Total Transaksi</strong><br>
                {{ number_format($totalTransaksi, 0, ',', '.') }}
            </td>
            <td>
                <strong>Total Pembayaran Lunas</strong><br>
                Rp {{ number_format($totalLunas, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No.</th>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Jenis Transaksi</th>
                <th>Metode</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembayaran as $item)
                @php
                    $pelanggan = $item->bookingServis?->pelanggan
                        ?? $item->orderSparePart?->pelanggan;

                    $jenis = $item->id_booking
                        ? 'Servis Bengkel'
                        : ($item->id_order ? 'Spare Part' : 'Lainnya');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->id_pembayaran }}</td>
                    <td>{{ $pelanggan->nama ?? $pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ $item->metodePembayaran->nama_metode ?? '-' }}</td>
                    <td class="number">
                        Rp {{ number_format((float) $item->jumlah, 0, ',', '.') }}
                    </td>
                    <td>{{ ucfirst($item->status ?? '-') }}</td>
                    <td>
                        {{ $item->tanggal_bayar
                            ? \Illuminate\Support\Carbon::parse($item->tanggal_bayar)->format('d-m-Y')
                            : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center">
                        Tidak ada data pembayaran.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
