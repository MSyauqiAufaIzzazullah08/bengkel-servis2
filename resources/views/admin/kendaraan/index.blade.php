
@extends('layouts.app')

@section('title', 'Manajemen Kendaraan')

@section('breadcrumb')
    <span>Data Master</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Kendaraan</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Kendaraan</h1>
        <p class="page-subtitle">Data kendaraan pelanggan yang terdaftar di database.</p>
    </div>

    <a href="{{ route('kendaraan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kendaraan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-car"></i>
            Daftar Kendaraan
            <span class="badge badge-primary">
                {{ $kendaraan->count() }} data
            </span>
        </h3>

        <div class="search-input-wrapper" style="max-width:280px">
            <i class="fas fa-search"></i>
            <input
                type="text"
                id="cariKendaraan"
                class="search-input"
                placeholder="Cari nopol, merk, pemilik..."
            >
        </div>
    </div>

    <style>
        .vehicle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .vehicle-card {
            background: #fff;
            border: 1px solid #DCE4EF;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(16,43,85,.06);
            transition: transform .2s, box-shadow .2s;
            min-width: 0;
        }

        .vehicle-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(16,43,85,.12);
        }

        .vehicle-card-photo {
            height: 190px;
            background: #E8EEF6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .vehicle-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .vehicle-card-placeholder {
            font-size: 52px;
            color: #66758C;
        }

        .vehicle-card-body {
            padding: 18px;
        }

        .vehicle-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #102B55;
            margin: 0 0 12px;
            overflow-wrap: anywhere;
        }

        .vehicle-card-plate {
            display: inline-block;
            background: #F8FAFD;
            border: 1px solid #C8D4E4;
            border-bottom: 3px solid #F5A623;
            color: #102B55;
            border-radius: 6px;
            padding: 7px 12px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .vehicle-card-detail {
            font-size: 13px;
            color: #66758C;
            line-height: 1.9;
            overflow-wrap: anywhere;
        }

        .vehicle-card-detail strong {
            color: #172033;
        }

        .vehicle-card-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #DCE4EF;
        }

        .vehicle-card-actions form {
            margin: 0;
        }

        .vehicle-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 35px 15px;
            color: #66758C;
        }
    </style>

    <div class="vehicle-grid" id="tabelKendaraan">
        @forelse($kendaraan as $item)
            <article class="vehicle-card">
                <div class="vehicle-card-photo">
                    @if (!empty($item->foto))
                        <img
                            src="{{ asset('storage/' . $item->foto) }}"
                            alt="Foto {{ $item->merk }} {{ $item->model }}"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="vehicle-card-placeholder" style="display:none;width:100%;height:100%;align-items:center;justify-content:center">
                            🚘
                        </div>
                    @else
                        <div class="vehicle-card-placeholder">🚘</div>
                    @endif
                </div>

                <div class="vehicle-card-body">
                    <h3 class="vehicle-card-title">
                        {{ $item->merk }} {{ $item->model }}
                    </h3>

                    <div class="vehicle-card-plate">
                        {{ $item->nopol }}
                    </div>

                    <div class="vehicle-card-detail">
                        <div>Pemilik:
                            <strong>{{ $item->pelanggan->nama ?? 'Pelanggan tidak ditemukan' }}</strong>
                        </div>
                        <div>Merek:
                            <strong>{{ $item->merk }}</strong>
                        </div>
                        <div>Model:
                            <strong>{{ $item->model }}</strong>
                        </div>
                        <div>Tahun:
                            <strong>{{ $item->tahun }}</strong>
                        </div>
                    </div>

                    <div class="vehicle-card-actions">
                        <a
                            href="{{ route('kendaraan.edit', $item->id_kendaraan) }}"
                            class="btn btn-sm btn-warning"
                            title="Edit"
                        >
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <form
                            action="{{ route('kendaraan.destroy', $item->id_kendaraan) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-danger"
                                title="Hapus"
                            >
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="vehicle-empty">
                <div style="font-size:42px;margin-bottom:10px">🚘</div>
                <strong>Belum ada data kendaraan.</strong>
            </div>
        @endforelse
    </div>
    <div class="card-footer">
        <span class="text-xs text-muted">
            Menampilkan {{ $kendaraan->count() }} kendaraan dari database
        </span>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('cariKendaraan').addEventListener('input', function () {
    const kata = this.value.toLowerCase();

    document.querySelectorAll('#tabelKendaraan tbody tr').forEach(function (baris) {
        baris.style.display = baris.innerText.toLowerCase().includes(kata)
            ? ''
            : 'none';
    });
});
</script>
@endsection

