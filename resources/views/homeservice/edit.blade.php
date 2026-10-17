
@extends('layouts.app')

@section('title', 'Edit Home Servis')

@section('breadcrumb')
    <span>Transaksi</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <a href="/homeservice" style="color:inherit;text-decoration:none">Home Servis</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Edit</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Home Servis</h1>
            <p class="page-subtitle">
                Ubah data home servis #{{ $homeServis->id_home_service }}
            </p>
        </div>

        <a href="/homeservice" class="btn btn-accent">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

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
                <i class="fas fa-edit"
                   style="color:var(--color-accent);margin-right:6px"></i>
                Form Edit Home Servis
            </h3>
        </div>

        <div style="padding:24px">
            <form action="/homeservice/{{ $homeServis->id_home_service }}"
                  method="POST">
                @csrf
                @method('PUT')

                {{-- PELANGGAN --}}
                <div class="form-group">
                    <label class="form-label" for="id_pelanggan">
                        Pelanggan <span style="color:red">*</span>
                    </label>

                    <select name="id_pelanggan" id="id_pelanggan"
                            class="form-control" required>
                        <option value="">-- Pilih Pelanggan --</option>

                        @foreach ($pelanggan as $item)
                            <option value="{{ $item->id_pelanggan }}"
                                {{ old('id_pelanggan', $homeServis->id_pelanggan) == $item->id_pelanggan ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- KENDARAAN --}}
                <div class="form-group">
                    <label class="form-label" for="id_kendaraan">
                        Kendaraan <span style="color:red">*</span>
                    </label>

                    <select name="id_kendaraan" id="id_kendaraan"
                            class="form-control" required>
                        <option value="">-- Pilih Kendaraan --</option>

                        @foreach ($kendaraan as $item)
                            <option value="{{ $item->id_kendaraan }}"
                                {{ old('id_kendaraan', $homeServis->id_kendaraan) == $item->id_kendaraan ? 'selected' : '' }}>
                                {{ $item->merk }} {{ $item->model }}
                                - {{ $item->nopol }}
                                ({{ $item->pelanggan->nama ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CABANG: KHUSUS SUPER ADMIN --}}
                @if ((int) session('role_id') === 1)
                    <div class="form-group">
                        <label class="form-label" for="id_cabang">
                            Cabang <span style="color:red">*</span>
                        </label>

                        <select name="id_cabang" id="id_cabang"
                                class="form-control" required>
                            <option value="">-- Pilih Cabang --</option>

                            @foreach ($cabang as $item)
                                <option value="{{ $item->id_cabang }}"
                                    {{ old('id_cabang', $homeServis->id_cabang) == $item->id_cabang ? 'selected' : '' }}>
                                    {{ $item->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- MONTIR LAPANGAN --}}
                <div class="form-group">
                    <label class="form-label" for="id_montir">
                        Montir Lapangan
                        @if ((int) session('role_id') === 2)
                            <span style="color:red">*</span>
                        @endif
                    </label>

                    <select name="id_montir" id="id_montir"
                            class="form-control"
                            {{ (int) session('role_id') === 2 ? 'required' : '' }}>
                        <option value="">-- Belum Ditugaskan --</option>

                        @foreach ($montir as $item)
                            <option value="{{ $item->id_montir }}"
                                {{ old('id_montir', $homeServis->id_montir) == $item->id_montir ? 'selected' : '' }}>
                                {{ $item->nama }} - {{ $item->area_tugas ?? '-' }}
                            </option>
                        @endforeach
                    </select>

                    @if ((int) session('role_id') === 2)
                        <small class="text-muted">
                            Pilih montir aktif dari cabang kamu.
                        </small>
                    @endif
                </div>

                {{-- ALAMAT --}}
                <div class="form-group">
                    <label class="form-label" for="alamat">
                        Alamat <span style="color:red">*</span>
                    </label>

                    <textarea name="alamat" id="alamat"
                              class="form-control" rows="3"
                              required>{{ old('alamat', $homeServis->alamat) }}</textarea>
                </div>

                {{-- TANGGAL --}}
                <div class="form-group">
                    <label class="form-label" for="tanggal">
                        Tanggal <span style="color:red">*</span>
                    </label>

                    <input type="date" name="tanggal" id="tanggal"
                           class="form-control"
                           value="{{ old('tanggal', $homeServis->tanggal) }}"
                           required>
                </div>

                {{-- STATUS --}}
                <div class="form-group">
                    <label class="form-label" for="status">
                        Status <span style="color:red">*</span>
                    </label>

                    <select name="status" id="status"
                            class="form-control" required>
                        @foreach ([
                            'Requested' => 'Requested - Menunggu penugasan',
                            'On The Way' => 'On The Way - Montir menuju lokasi',
                            'Arrived' => 'Arrived - Montir tiba di lokasi',
                            'In Service' => 'In Service - Servis berlangsung',
                            'Completed' => 'Completed - Servis selesai'
                        ] as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('status', $homeServis->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CATATAN --}}
                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan</label>

                    <textarea name="catatan" id="catatan"
                              class="form-control" rows="3">{{ old('catatan', $homeServis->catatan) }}</textarea>
                </div>

                {{-- TOMBOL --}}
                <div style="display:flex;gap:12px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>

                    <a href="/homeservice" class="btn btn-accent">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
