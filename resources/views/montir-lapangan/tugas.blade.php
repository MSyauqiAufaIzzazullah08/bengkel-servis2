
@extends('layouts.app')

@section('title', 'Tugas Home Service')

@section('content')
<style>
    .hs-page {
        max-width: 1050px;
        margin: 0 auto;
        padding: 28px 24px;
        color: #172b4d;
    }

    .hs-heading {
        margin-bottom: 22px;
    }

    .hs-heading h2 {
        font-size: 24px;
        font-weight: 700;
        color: #142f59;
        margin: 0 0 7px;
    }

    .hs-heading p {
        color: #718096;
        font-size: 14px;
        margin: 0;
    }

    .hs-card {
        background: #fff;
        border: 1px solid #dce5f0;
        border-radius: 13px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 3px 12px rgba(25, 55, 95, 0.05);
    }

    .hs-card-header {
        padding: 22px 24px 18px;
        border-bottom: 1px solid #dce5f0;
    }

    .hs-booking-code {
        font-size: 12px;
        color: #718096;
        margin-bottom: 7px;
    }

    .hs-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .hs-title-row h3 {
        font-size: 19px;
        font-weight: 700;
        color: #142f59;
        margin: 0;
    }

    .hs-badge {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .hs-requested {
        background: #fff1d6;
        color: #956000;
    }

    .hs-travel {
        background: #e4efff;
        color: #2457a6;
    }

    .hs-arrived {
        background: #efe7ff;
        color: #7040a0;
    }

    .hs-service {
        background: #dff7ee;
        color: #13795b;
    }

    .hs-completed {
        background: #dff5e4;
        color: #21743a;
    }

    .hs-card-body {
        padding: 22px 24px 24px;
    }

    .hs-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px 28px;
    }

    .hs-info-label {
        display: block;
        font-size: 12px;
        color: #718096;
        margin-bottom: 7px;
    }

    .hs-info-value {
        font-size: 14px;
        line-height: 1.6;
        color: #172b4d;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .hs-info-sub {
        display: block;
        font-size: 12px;
        color: #718096;
        margin-top: 3px;
        font-weight: 400;
    }

    .hs-divider {
        height: 1px;
        background: #dce5f0;
        margin: 20px 0;
    }

    .hs-note {
        padding: 14px 15px;
        background: #f6f8fc;
        border: 1px solid #e3eaf4;
        border-left: 3px solid #f5a623;
        border-radius: 8px;
    }

    .hs-note-title {
        font-size: 12px;
        color: #142f59;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .hs-note-text {
        font-size: 13px;
        line-height: 1.7;
        color: #53647c;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .hs-form-title {
        font-size: 15px;
        color: #142f59;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .hs-form-desc {
        font-size: 12px;
        color: #718096;
        margin: 0 0 18px;
        line-height: 1.6;
    }

    .hs-field {
        margin-bottom: 17px;
    }

    .hs-field label {
        display: block;
        color: #354762;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .hs-field select,
    .hs-field textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d6e0ed;
        border-radius: 7px;
        padding: 11px 12px;
        color: #172b4d;
        background: #fff;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .hs-field select:focus,
    .hs-field textarea:focus {
        border-color: #244b82;
        box-shadow: 0 0 0 3px rgba(36, 75, 130, 0.09);
    }

    .hs-field textarea {
        min-height: 95px;
        resize: vertical;
        line-height: 1.6;
    }

    .hs-help {
        font-size: 11px;
        color: #8592a6;
        margin-top: 6px;
    }

    .hs-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: #193f78;
        color: #fff !important;
        border: none;
        border-radius: 7px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        cursor: pointer;
        transition: background 0.2s;
    }

    .hs-button:hover {
        background: #102f5e;
    }

    .hs-alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .hs-alert-success {
        background: #e5f6e9;
        border: 1px solid #c5e8cd;
        color: #21743a;
    }

    .hs-alert-error {
        background: #fff0f0;
        border: 1px solid #f2cccc;
        color: #a52828;
    }

    .hs-empty {
        text-align: center;
        padding: 55px 20px;
        color: #718096;
    }

    .hs-empty h3 {
        font-size: 17px;
        color: #142f59;
        margin: 14px 0 8px;
    }

    .hs-empty p {
        font-size: 13px;
        margin: 0;
        line-height: 1.6;
    }

    @media (max-width: 600px) {
        .hs-page {
            padding: 20px 12px;
        }

        .hs-card-header,
        .hs-card-body {
            padding: 17px;
        }

        .hs-info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 19px 14px;
        }

        .hs-heading h2 {
            font-size: 21px;
        }
    }
</style>

<div class="hs-page">

    <div class="hs-heading">
        <h2>Tugas Home Service</h2>
        <p>
            Daftar pekerjaan Home Service yang ditugaskan kepada
            {{ $montir->nama }}.
        </p>
    </div>

    @if (session('success'))
        <div class="hs-alert hs-alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="hs-alert hs-alert-error">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin:8px 0 0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @forelse ($tugas as $item)

        @php
            $statusClass = [
                'Requested' => 'hs-requested',
                'On The Way' => 'hs-travel',
                'Arrived' => 'hs-arrived',
                'In Service' => 'hs-service',
                'Completed' => 'hs-completed',
            ];

            $statusLabel = [
                'Requested' => 'Menunggu Penugasan',
                'On The Way' => 'Menuju Lokasi',
                'Arrived' => 'Tiba di Lokasi',
                'In Service' => 'Servis Berlangsung',
                'Completed' => 'Selesai',
            ];

            $nextStatus = [
                'Requested' => 'On The Way',
                'On The Way' => 'Arrived',
                'Arrived' => 'In Service',
                'In Service' => 'Completed',
            ];
        @endphp

        <div class="hs-card">

            <div class="hs-card-header">
                <div class="hs-booking-code">
                    Kode tugas #{{ $item->id_home_service }}
                </div>

                <div class="hs-title-row">
                    <h3>Home Service</h3>

                    <span class="hs-badge {{ $statusClass[$item->status] ?? 'hs-requested' }}">
                        {{ $statusLabel[$item->status] ?? $item->status }}
                    </span>
                </div>
            </div>

            <div class="hs-card-body">

                <div class="hs-info-grid">

                    <div>
                        <span class="hs-info-label">Pelanggan</span>
                        <div class="hs-info-value">
                            {{ $item->pelanggan->nama ?? 'Data pelanggan tidak tersedia' }}
                        </div>
                    </div>

                    <div>
                        <span class="hs-info-label">Kendaraan</span>
                        <div class="hs-info-value">
                            {{ $item->kendaraan->merk ?? '-' }}
                            {{ $item->kendaraan->model ?? '' }}

                            <span class="hs-info-sub">
                                {{ $item->kendaraan->nopol ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="hs-info-label">Alamat Servis</span>
                        <div class="hs-info-value">
                            {{ $item->alamat ?: '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="hs-info-label">Tanggal Servis</span>
                        <div class="hs-info-value">
                            {{ \Illuminate\Support\Carbon::parse($item->tanggal)->format('d F Y') }}
                        </div>
                    </div>

                </div>

                <div class="hs-divider"></div>

                <div class="hs-note">
                    <div class="hs-note-title">Catatan Pekerjaan</div>
                    <div class="hs-note-text">{{ $item->catatan ?: 'Belum ada catatan pekerjaan.' }}</div>
                </div>

                <div class="hs-divider"></div>

                @if ($item->status !== 'Completed')

                    <h4 class="hs-form-title">Perbarui Status Servis</h4>
                    <p class="hs-form-desc">
                        Perbarui progres setelah kamu menyelesaikan tahap pekerjaan saat ini.
                    </p>

                    <form
                        action="{{ route('montir-lapangan.tugas.status', $item->id_home_service) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="hs-field">
                            <label for="status_{{ $item->id_home_service }}">
                                Status Berikutnya
                            </label>

                            <select
                                name="status"
                                id="status_{{ $item->id_home_service }}"
                                required
                            >
                                @if (isset($nextStatus[$item->status]))
                                    <option value="{{ $nextStatus[$item->status] }}">
                                        {{ $nextStatus[$item->status] }}
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="hs-field">
                            <label for="catatan_{{ $item->id_home_service }}">
                                Catatan Hasil Servis
                            </label>

                            <textarea
                                name="catatan"
                                id="catatan_{{ $item->id_home_service }}"
                                maxlength="5000"
                                placeholder="Tuliskan hasil pemeriksaan atau pekerjaan servis..."
                            >{{ $item->catatan }}</textarea>

                            <div class="hs-help">
                                Catatan ini akan tersimpan bersama progres pekerjaan.
                            </div>
                        </div>

                        <button type="submit" class="hs-button">
                            Simpan Perubahan
                        </button>
                    </form>

                @else

                    <div class="hs-alert hs-alert-success" style="margin:0;">
                        <strong>Servis selesai.</strong>
                        <div style="margin-top:5px;">
                            Tugas ini telah mencapai tahap akhir.
                        </div>
                    </div>

                @endif

            </div>
        </div>

    @empty

        <div class="hs-card">
            <div class="hs-empty">
                <div style="font-size:36px;color:#244b82;">▤</div>
                <h3>Belum Ada Tugas Home Service</h3>
                <p>
                    Tugas akan muncul setelah admin menetapkan kamu sebagai montir.
                </p>
            </div>
        </div>

    @endforelse

</div>
@endsection
