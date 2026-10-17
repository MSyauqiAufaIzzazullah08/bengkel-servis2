@extends('layouts.app')

@section('title', 'Pengelolaan Booking')

@section('breadcrumb')
    <span>Operasional</span>
    <i class="fas fa-chevron-right" style="font-size: 0.65rem"></i>
    <span class="breadcrumb-current">Pengelolaan Booking</span>
@endsection

@section('styles')
<style>
    .booking-page {
        width: 100%;
    }

    .booking-page .booking-heading {
        margin-bottom: 24px;
    }

    .booking-page .booking-heading h1 {
        font-size: 26px;
        margin-bottom: 8px;
        color: var(--text-primary, #172b4d);
    }

    .booking-page .subtitle {
        color: var(--text-muted, #718096);
        font-size: 14px;
        line-height: 1.6;
    }

    .booking-page .stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .booking-page .stat-card {
        background: #fff;
        border: 1px solid #dfe7f1;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, 0.04);
    }

    .booking-page .stat-label {
        color: #718096;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .booking-page .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #172b4d;
    }

    .booking-page .notice {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 14px;
        background: #dcfce7;
        color: #166534;
    }

    .booking-page .notice.error {
        background: #fee2e2;
        color: #991b1b;
    }

    .booking-page .section-title {
        font-size: 20px;
        margin: 26px 0 16px;
    }

    .booking-page .booking-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .booking-page .booking-card {
        min-width: 0;
        background: #fff;
        border: 1px solid #dfe7f1;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, 0.04);
    }

    .booking-page .card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 1px solid #edf1f5;
    }

    .booking-page .booking-code {
        color: #718096;
        font-size: 12px;
        margin-bottom: 7px;
    }

    .booking-page .service-name {
        font-size: 18px;
        overflow-wrap: anywhere;
    }

    .booking-page .status-badge {
        display: inline-block;
        border-radius: 20px;
        padding: 7px 10px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .booking-page .pending {
        color: #92400e;
        background: #fef3c7;
    }

    .booking-page .confirmed {
        color: #1d4ed8;
        background: #dbeafe;
    }

    .booking-page .in-progress {
        color: #6d28d9;
        background: #ede9fe;
    }

    .booking-page .completed {
        color: #166534;
        background: #dcfce7;
    }

    .booking-page .paid {
        color: #0f766e;
        background: #ccfbf1;
    }

    .booking-page .details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        padding: 18px 0;
    }

    .booking-page .detail-label {
        font-size: 12px;
        color: #718096;
        margin-bottom: 6px;
    }

    .booking-page .detail-value {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .booking-page .note {
        background: #f8fafc;
        padding: 13px;
        border-radius: 9px;
        margin-bottom: 18px;
        font-size: 13px;
        line-height: 1.6;
    }

    .booking-page .status-form {
        border-top: 1px solid #edf1f5;
        padding-top: 17px;
    }

    .booking-page .status-form label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .booking-page .status-form select {
        width: 100%;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        margin-bottom: 12px;
        color: #172b4d;
    }

    .booking-page .save-button {
        width: 100%;
        border: 0;
        border-radius: 8px;
        padding: 12px 16px;
        background: #087f73;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
    }

    .booking-page .save-button:hover {
        background: #06665d;
    }

    .booking-page .empty-booking {
        grid-column: 1 / -1;
        background: #fff;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
    }

    @media (max-width: 1100px) {
        .booking-page .stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .booking-page .booking-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .booking-page .stats {
            gap: 10px;
        }

        .booking-page .stat-card {
            padding: 14px;
        }

        .booking-page .stat-number {
            font-size: 23px;
        }

        .booking-page .details {
            grid-template-columns: 1fr;
        }

        .booking-page .card-top {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-page">

    <div class="booking-heading">
        <h1>Pengelolaan Booking Servis</h1>
        <p class="subtitle">
            Periksa booking pelanggan dan perbarui status pengerjaan servis.
        </p>
    </div>

    @if(session('success'))
        <div class="notice">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="notice error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notice error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total Booking</div>
            <div class="stat-number">{{ $totalBooking }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Menunggu Konfirmasi</div>
            <div class="stat-number">{{ $menungguKonfirmasi }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Sedang Diservis</div>
            <div class="stat-number">{{ $sedangDiservis }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Servis Selesai / Lunas</div>
            <div class="stat-number">{{ $servisSelesai }}</div>
        </div>
    </div>

    <h2 class="section-title">Daftar Booking Pelanggan</h2>

    <div class="booking-grid">
        @forelse($bookings as $booking)
            @php
                $statusClass = match($booking->status) {
                    'Pending' => 'pending',
                    'Confirmed' => 'confirmed',
                    'In Progress' => 'in-progress',
                    'Completed' => 'completed',
                    'Paid' => 'paid',
                    default => 'pending',
                };

                $statusLabel = match($booking->status) {
                    'Pending' => 'Menunggu Konfirmasi',
                    'Confirmed' => 'Dikonfirmasi',
                    'In Progress' => 'Sedang Diservis',
                    'Completed' => 'Servis Selesai',
                    'Paid' => 'Lunas',
                    default => $booking->status,
                };
            @endphp

            <article class="booking-card">
                <div class="card-top">
                    <div>
                        <div class="booking-code">
                            Kode booking #{{ $booking->id_booking }}
                        </div>

                        <h3 class="service-name">
                            {{ $booking->nama_layanan ?? 'Layanan servis' }}
                        </h3>
                    </div>

                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="details">
                    <div>
                        <div class="detail-label">Pelanggan</div>
                        <div class="detail-value">
                            {{ $booking->nama_pelanggan ?? 'Nama tidak tersedia' }}
                        </div>
                        <div class="detail-label" style="margin-top:5px">
                            {{ $booking->email_pelanggan ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="detail-label">Kendaraan</div>
                        <div class="detail-value">
                            {{ $booking->merk ?? '-' }}
                            {{ $booking->model ?? '' }}
                        </div>
                        <div class="detail-label" style="margin-top:5px">
                            {{ $booking->nopol ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="detail-label">Cabang Bengkel</div>
                        <div class="detail-value">
                            {{ $booking->nama_cabang ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="detail-label">Jadwal Servis</div>
                        <div class="detail-value">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                        </div>
                        <div class="detail-label" style="margin-top:5px">
                            {{ substr($booking->waktu_booking ?? '00:00', 0, 5) }} WIB
                        </div>
                    </div>

                    <div>
                        <div class="detail-label">Harga Layanan</div>
                        <div class="detail-value" style="color:#087f73">
                            Rp {{ number_format((float)($booking->harga ?? 0), 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if($booking->catatan)
                    <div class="note">
                        <strong>Catatan Pelanggan</strong>
                        <p>{{ $booking->catatan }}</p>
                    </div>
                @endif

                <form
                    class="status-form"
                    action="{{ route('pengelolaan-booking.update', $booking->id_booking) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <label for="status-{{ $booking->id_booking }}">
                        Perbarui Status Booking
                    </label>

                    
@if($booking->status === 'Pending' || $booking->status === 'Confirmed')
    @php
        $penugasanAda = \App\Models\PenugasanTeknisi::where(
            'id_booking',
            $booking->id_booking
        )->exists();
    @endphp

    @if(!$penugasanAda)
        <label for="teknisi-{{ $booking->id_booking }}">
            Pilih Teknisi <span style="color:red">*</span>
        </label>

        <select
            name="id_teknisi"
            id="teknisi-{{ $booking->id_booking }}"
            required
        >
            <option value="">-- Pilih Teknisi --</option>

            @foreach($teknisi->where('cabang_id', $booking->id_cabang) as $item)
                <option value="{{ $item->id_teknisi }}">
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>
    @endif
@endif


                    <select
                        name="status"
                        id="status-{{ $booking->id_booking }}"
                        {{ $booking->status === 'Paid' ? 'disabled' : '' }}
                    >
                        <option value="Pending"
                            {{ $booking->status === 'Pending' ? 'selected' : '' }}>
                            Pending — Menunggu Konfirmasi
                        </option>

                        <option value="Confirmed"
                            {{ $booking->status === 'Confirmed' ? 'selected' : '' }}>
                            Confirmed — Dikonfirmasi
                        </option>

                        <option value="In Progress"
                            {{ $booking->status === 'In Progress' ? 'selected' : '' }}>
                            In Progress — Sedang Diservis
                        </option>

                        <option value="Completed"
                            {{ $booking->status === 'Completed' ? 'selected' : '' }}>
                            Completed — Servis Selesai
                        </option>

                        @if($booking->status === 'Paid')
                            <option value="Paid" selected>Paid — Lunas</option>
                        @endif
                    </select>

                    @if($booking->status === 'Paid')
                        <p class="subtitle">
                            Booking sudah lunas dan tidak dapat diubah dari halaman ini.
                        </p>
                    @else
                        <button type="submit" class="save-button">
                            Simpan Status
                        </button>
                    @endif
                </form>
            </article>
        @empty
            <div class="empty-booking">
                <h3>Belum Ada Booking Servis</h3>
                <p class="subtitle" style="margin-top:10px">
                    Booking pelanggan akan tampil di bagian ini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
