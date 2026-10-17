
@extends('layouts.app')

@section('title', 'Dashboard Montir Lapangan')

@section('content')
<style>
    .md-page {
        max-width: 1150px;
        margin: 0 auto;
        padding: 28px 24px;
        color: #172b4d;
    }

    .md-heading {
        margin-bottom: 24px;
    }

    .md-heading h2 {
        color: #142f59;
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 7px;
    }

    .md-heading p {
        color: #718096;
        font-size: 13px;
        margin: 0;
    }

    .md-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .md-stat {
        background: #fff;
        border: 1px solid #dce5f0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(25, 55, 95, .04);
    }

    .md-stat-label {
        font-size: 12px;
        color: #718096;
        margin-bottom: 10px;
    }

    .md-stat-value {
        font-size: 28px;
        line-height: 1.2;
        font-weight: 700;
        color: #142f59;
    }

    .md-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #142f59;
        margin: 0 0 16px;
    }

    .md-card {
        background: #fff;
        border: 1px solid #dce5f0;
        border-radius: 13px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(25, 55, 95, .05);
    }

    .md-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #dce5f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .md-code {
        font-size: 12px;
        color: #718096;
        margin-bottom: 6px;
    }

    .md-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #142f59;
        margin: 0;
    }

    .md-badge {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .md-requested { background: #fff1d6; color: #956000; }
    .md-travel { background: #e4efff; color: #2457a6; }
    .md-arrived { background: #efe7ff; color: #7040a0; }
    .md-service { background: #dff7ee; color: #13795b; }
    .md-completed { background: #dff5e4; color: #21743a; }

    .md-card-body {
        padding: 22px 24px 24px;
    }

    .md-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 28px;
    }

    .md-label {
        display: block;
        font-size: 12px;
        color: #718096;
        margin-bottom: 7px;
    }

    .md-value {
        font-size: 14px;
        line-height: 1.6;
        font-weight: 600;
        color: #172b4d;
        overflow-wrap: anywhere;
    }

    .md-subvalue {
        display: block;
        font-size: 12px;
        color: #718096;
        font-weight: 400;
        margin-top: 3px;
    }

    .md-divider {
        height: 1px;
        background: #dce5f0;
        margin: 21px 0;
    }

    .md-note {
        padding: 14px 15px;
        background: #f6f8fc;
        border: 1px solid #e3eaf4;
        border-left: 3px solid #f5a623;
        border-radius: 8px;
    }

    .md-note-title {
        color: #142f59;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .md-note-text {
        color: #53647c;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .md-form-title {
        color: #142f59;
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .md-form-desc {
        color: #718096;
        font-size: 12px;
        line-height: 1.6;
        margin: 0 0 18px;
    }

    .md-field {
        margin-bottom: 17px;
    }

    .md-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #354762;
        margin-bottom: 8px;
    }

    .md-field select,
    .md-field textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 11px 12px;
        border: 1px solid #d6e0ed;
        border-radius: 7px;
        background: #fff;
        color: #172b4d;
        font: inherit;
        font-size: 13px;
        outline: none;
    }

    .md-field select:focus,
    .md-field textarea:focus {
        border-color: #244b82;
        box-shadow: 0 0 0 3px rgba(36, 75, 130, .09);
    }

    .md-field textarea {
        min-height: 80px;
        resize: vertical;
        line-height: 1.6;
    }

    .md-button {
        display: inline-block;
        padding: 11px 18px;
        background: #193f78;
        color: #fff !important;
        border: none;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        cursor: pointer;
    }

    .md-button:hover {
        background: #102f5e;
    }

    .md-alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .md-success {
        background: #e5f6e9;
        border: 1px solid #c5e8cd;
        color: #21743a;
    }

    .md-error {
        background: #fff0f0;
        border: 1px solid #f2cccc;
        color: #a52828;
    }

    .md-empty {
        padding: 45px 20px;
        text-align: center;
        color: #718096;
    }

    .md-empty h4 {
        color: #142f59;
        font-size: 17px;
        margin: 12px 0 7px;
    }

    .md-empty p {
        font-size: 13px;
        margin: 0;
    }

    @media (max-width: 700px) {
        .md-page {
            padding: 20px 12px;
        }

        .md-summary {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .md-info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 18px 14px;
        }

        .md-card-header,
        .md-card-body {
            padding: 17px;
        }
    }

    @media (max-width: 380px) {
        .md-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="md-page">

    {{-- HEADER --}}
    <div class="md-heading">
        <h2>Dashboard Montir Lapangan</h2>
        <p>
            Halo, {{ $montir->nama }}! Berikut ringkasan tugas Home Service kamu.
        </p>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="md-alert md-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="md-alert md-error">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin:8px 0 0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- RINGKASAN --}}
    <div class="md-summary">
        <div class="md-stat">
            <div class="md-stat-label">Total Tugas</div>
            <div class="md-stat-value">{{ $totalTugas }}</div>
        </div>

        <div class="md-stat">
            <div class="md-stat-label">Tugas Aktif</div>
            <div class="md-stat-value" style="color:#2457a6;">
                {{ $tugasAktif }}
            </div>
        </div>

        <div class="md-stat">
            <div class="md-stat-label">Tugas Selesai</div>
            <div class="md-stat-value" style="color:#21743a;">
                {{ $tugasSelesai }}
            </div>
        </div>
    </div>

    {{-- DAFTAR TUGAS --}}
    <h3 class="md-section-title">Tugas Home Service Saya</h3>

    @forelse ($tugas as $item)

        @php
            $statusClass = [
                'Requested' => 'md-requested',
                'On The Way' => 'md-travel',
                'Arrived' => 'md-arrived',
                'In Service' => 'md-service',
                'Completed' => 'md-completed',
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

        <div class="md-card">

            <div class="md-card-header">
                <div>
                    <div class="md-code">
                        Kode tugas #{{ $item->id_home_service }}
                    </div>
                    <h4 class="md-card-title">Home Service</h4>
                </div>

                <span class="md-badge {{ $statusClass[$item->status] ?? 'md-requested' }}">
                    {{ $statusLabel[$item->status] ?? $item->status }}
                </span>
            </div>

            <div class="md-card-body">

                <div class="md-info-grid">

                    <div>
                        <span class="md-label">Pelanggan</span>
                        <div class="md-value">
                            {{ $item->pelanggan->nama ?? 'Data pelanggan tidak tersedia' }}
                        </div>
                    </div>

                    <div>
                        <span class="md-label">Kendaraan</span>
                        <div class="md-value">
                            {{ $item->kendaraan->merk ?? '-' }}
                            {{ $item->kendaraan->model ?? '' }}
                            <span class="md-subvalue">
                                {{ $item->kendaraan->nopol ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="md-label">Alamat Servis</span>
                        <div class="md-value">
                            {{ $item->alamat ?: '-' }}
                        </div>
                    </div>

                    <div>
                        <span class="md-label">Jadwal Servis</span>
                        <div class="md-value">
                            {{ \Illuminate\Support\Carbon::parse($item->tanggal)->format('d F Y') }}
                        </div>
                    </div>

                </div>

                <div class="md-divider"></div>

                <div class="md-note">
                    <div class="md-note-title">Catatan Pekerjaan</div>
                    <div class="md-note-text">
                        {{ $item->catatan ?: 'Belum ada catatan pekerjaan.' }}
                    </div>
                </div>

                @if ($item->status !== 'Completed')

                    <div class="md-divider"></div>

                    <h5 class="md-form-title">Perbarui Status Servis</h5>
                    <p class="md-form-desc">
                        Perbarui progres pekerjaan setelah menyelesaikan tahap saat ini.
                    </p>

                    <form
                        action="{{ route('montir-lapangan.tugas.status', $item->id_home_service) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="md-field">
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

                        <div class="md-field">
                            <label for="catatan_{{ $item->id_home_service }}">
                                Catatan Hasil Servis
                            </label>

                            <textarea
                                name="catatan"
                                id="catatan_{{ $item->id_home_service }}"
                                maxlength="5000"
                                placeholder="Catatan pengerjaan atau hasil servis..."
                            >{{ $item->catatan }}</textarea>
                        </div>

                        <button type="submit" class="md-button">
                            Simpan Perubahan
                        </button>
                    </form>

                @else

                    <div class="md-divider"></div>

                    <div class="md-alert md-success" style="margin:0;">
                        <strong>Servis selesai.</strong>
                        Catatan hasil pengerjaan sudah ditampilkan di atas.
                    </div>

                @endif

            </div>
        </div>

    @empty

        <div class="md-card">
            <div class="md-empty">
                <h4>Belum Ada Tugas Home Service</h4>
                <p>
                    Tugas akan muncul setelah admin menetapkan kamu sebagai montir.
                </p>
            </div>
        </div>

    @endforelse

</div>
@endsection
