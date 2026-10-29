@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('breadcrumb')
    <span>Keuangan</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Konfirmasi Pembayaran</span>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Konfirmasi Pembayaran</h1>
        <p class="page-subtitle">
            Pembayaran Booking Servis &amp; Home Service yang menunggu persetujuan cabang Anda
        </p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success" style="margin-bottom:20px">
        <i class="fas fa-check-circle alert-icon"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning" style="margin-bottom:20px">
        <i class="fas fa-exclamation-triangle alert-icon"></i>
        <div>{{ session('warning') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom:20px">
        <i class="fas fa-times-circle alert-icon"></i>
        <div>
            <div class="alert-title">Terjadi kesalahan:</div>
            <ul style="margin:4px 0 0 16px;padding:0">
                @foreach ($errors->all() as $error)
                    <li style="font-size:0.875rem">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-money-check-alt" style="color:var(--color-accent);margin-right:6px"></i>
            Menunggu Konfirmasi
            <span class="badge badge-warning" style="margin-left:8px;font-size:0.75rem">
                {{ $semuaPembayaran->count() }} transaksi
            </span>
        </h3>

        <div class="search-input-wrapper" style="max-width:280px">
            <i class="fas fa-search"></i>
            <input type="text" id="cariKonfirmasi" class="search-input"
                   placeholder="Cari nama pelanggan, jenis...">
        </div>
    </div>

    <div class="table-container">
        <table class="table" id="tabelKonfirmasi">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jenis</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Kendaraan</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Bukti</th>
                    <th style="min-width:180px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($semuaPembayaran as $index => $item)
                <tr>
                    <td>
                        <span style="color:var(--color-text-muted);font-size:0.8rem">
                            {{ $index + 1 }}
                        </span>
                    </td>

                    <td>
                        @if ($item->jenis === 'Booking Servis')
                            <span class="badge badge-primary">Booking</span>
                        @else
                            <span class="badge badge-info">Home Service</span>
                        @endif
                    </td>

                    <td style="font-size:0.85rem">
                        @if ($item->jenis === 'Booking Servis')
                            {{ $item->bookingServis->pelanggan->nama ?? '-' }}
                            <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                Booking #{{ $item->id_booking }}
                            </div>
                        @else
                            {{ $item->homeServis->pelanggan->nama ?? '-' }}
                            <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                Home Service #{{ $item->id_home_service }}
                            </div>
                        @endif
                    </td>

                    <td style="font-size:0.84rem">
                        @if ($item->jenis === 'Booking Servis')
                            {{ $item->bookingServis->layananServis->nama_layanan ?? '-' }}
                        @else
                            {{ $item->homeServis->layananServis->nama_layanan ?? '-' }}
                        @endif
                    </td>

                    <td style="font-size:0.82rem">
                        @if ($item->jenis === 'Booking Servis')
                            @php $k = $item->bookingServis->kendaraan ?? null; @endphp
                        @else
                            @php $k = $item->homeServis->kendaraan ?? null; @endphp
                        @endif
                        @if ($k)
                            {{ $k->merk }} {{ $k->model }}
                            <div style="font-size:0.72rem;color:var(--color-text-muted)">
                                {{ $k->nopol }}
                            </div>
                        @else
                            <span style="color:var(--color-text-muted)">-</span>
                        @endif
                    </td>

                    <td style="font-size:0.84rem">
                        {{ $item->metodePembayaran->nama_metode ?? '-' }}
                    </td>

                    <td style="font-size:0.85rem;font-weight:600">
                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>

                    <td style="font-size:0.82rem">
                        @if ($item->bukti_pembayaran)
                            <a href="{{ $item->bukti_pembayaran }}" target="_blank"
                               class="btn btn-accent btn-sm" title="Lihat Bukti">
                                <i class="fas fa-image"></i>
                            </a>
                        @else
                            <span style="color:var(--color-text-muted)">–</span>
                        @endif
                    </td>

                    <td>
                        <div class="actions-cell" style="gap:6px">
                            {{-- Tombol ACC --}}
                            <form action="{{ route('konfirmasi-pembayaran.acc', $item->id_pembayaran) }}"
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('ACC pembayaran ini? Status akan diubah menjadi Lunas.')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                        title="ACC – Setujui Pembayaran">
                                    <i class="fas fa-check"></i> ACC
                                </button>
                            </form>

                            {{-- Tombol Tolak --}}
                            <button type="button" class="btn btn-danger btn-sm"
                                    onclick="openTolakModal({{ $item->id_pembayaran }})"
                                    title="Tolak Pembayaran">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="table-empty">
                            <i class="fas fa-check-double"></i>
                            <p>Tidak ada pembayaran yang menunggu konfirmasi.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <span class="text-xs text-muted">
            Menampilkan {{ $semuaPembayaran->count() }} transaksi menunggu konfirmasi
        </span>
    </div>
</div>

{{-- Modal Tolak --}}
<div id="modalTolak" style="display:none;position:fixed;inset:0;z-index:9999;
     background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;
                max-width:460px;box-shadow:0 8px 30px rgba(0,0,0,.18);">
        <h3 style="margin:0 0 12px;font-size:1.05rem;color:#172033">
            <i class="fas fa-times-circle" style="color:#ef4444"></i>
            Tolak Pembayaran
        </h3>
        <p style="font-size:.875rem;color:#66758c;margin-bottom:16px">
            Pembayaran akan ditolak dan statusnya diubah menjadi <strong>Gagal</strong>.
        </p>

        <form id="formTolak" method="POST">
            @csrf
            <div style="margin-bottom:14px">
                <label style="display:block;font-size:.85rem;font-weight:600;
                              margin-bottom:6px;color:#172033">
                    Alasan Penolakan <span style="color:#ef4444">*</span>
                </label>
                <textarea name="alasan_tolak" id="alasan_tolak_input" rows="3"
                          class="form-control"
                          placeholder="Contoh: bukti pembayaran tidak sesuai, jumlah tidak cocok..."
                          style="resize:vertical"
                          required
                          minlength="5"
                          maxlength="500"></textarea>
                <p style="font-size:.75rem;color:#66758c;margin-top:4px">
                    Wajib diisi, minimal 5 karakter. Alasan ini akan disimpan sebagai catatan penolakan.
                </p>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="closeTolakModal()"
                        class="btn btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Filter tabel
const inputCari = document.getElementById('cariKonfirmasi');
if (inputCari) {
    inputCari.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#tabelKonfirmasi tbody tr').forEach(function (tr) {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
}

// Modal Tolak
function openTolakModal(idPembayaran) {
    const form  = document.getElementById('formTolak');
    const modal = document.getElementById('modalTolak');
    form.action = '/konfirmasi-pembayaran/' + idPembayaran + '/tolak';
    modal.style.display = 'flex';
    // Reset textarea dan hilangkan error lama
    const ta = form.querySelector('textarea[name=alasan_tolak]');
    ta.value = '';
    ta.style.borderColor = '';
    const errEl = document.getElementById('err_alasan');
    if (errEl) errEl.remove();
}

function closeTolakModal() {
    document.getElementById('modalTolak').style.display = 'none';
}

// Validasi frontend sebelum submit
document.getElementById('formTolak').addEventListener('submit', function (e) {
    const ta = this.querySelector('textarea[name=alasan_tolak]');
    const val = ta.value.trim();
    // Hapus error lama
    const old = document.getElementById('err_alasan');
    if (old) old.remove();

    if (val.length < 5) {
        e.preventDefault();
        ta.style.borderColor = '#ef4444';
        const errDiv = document.createElement('p');
        errDiv.id = 'err_alasan';
        errDiv.style.cssText = 'color:#ef4444;font-size:.78rem;margin-top:4px';
        errDiv.textContent = 'Alasan penolakan wajib diisi (minimal 5 karakter).';
        ta.insertAdjacentElement('afterend', errDiv);
        ta.focus();
    } else {
        ta.style.borderColor = '';
    }
});

// Tutup modal klik di luar
document.getElementById('modalTolak').addEventListener('click', function (e) {
    if (e.target === this) closeTolakModal();
});
</script>
@endsection
