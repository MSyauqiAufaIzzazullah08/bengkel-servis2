@extends('layouts.app')

@section('title', 'Edit Teknisi')

@section('breadcrumb')
    <span>Manajemen SDM</span>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <a href="/teknisi">Teknisi</a>
    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
    <span class="breadcrumb-current">Edit</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Teknisi</h1>
            <p class="page-subtitle">Ubah data teknisi: {{ $teknisi->nama }}</p>
        </div>
        <a href="/teknisi" class="btn btn-secondary">
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
                <i class="fas fa-tools" style="color:var(--color-accent);margin-right:6px"></i>
                Form Edit Teknisi
            </h3>
        </div>
        <div class="card-body">
            <form action="/teknisi/{{ $teknisi->id_teknisi }}" method="POST">
                @csrf
                @method('PUT')

                @if (session('role_id') == 1)
    <div class="form-group">
        <label class="form-label" for="cabang_id">
            Cabang Teknisi <span style="color:red">*</span>
        </label>

        <select id="cabang_id" name="cabang_id" class="form-control" required>
            <option value="">-- Pilih Cabang --</option>

            @foreach ($cabang as $item)
                <option value="{{ $item->id_cabang }}"
                    {{ old('cabang_id', $teknisi->user->cabang_id ?? '') == $item->id_cabang ? 'selected' : '' }}>
                    {{ $item->nama_cabang }}
                </option>
            @endforeach
        </select>
    </div>
@endif

                <div class="form-group">
                    <label class="form-label" for="user_id">User Akun <span style="color:red">*</span></label>
                    <select id="user_id" name="user_id" class="form-control" required>
                        <option value="">-- Pilih User Teknisi --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id_user }}"
                                {{ old('user_id', $teknisi->user_id) == $user->id_user ? 'selected' : '' }}>
                                {{ $user->nama }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama">Nama Teknisi <span style="color:red">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control"
                           value="{{ old('nama', $teknisi->nama) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="no_hp">No HP <span style="color:red">*</span></label>
                    <input type="text" id="no_hp" name="no_hp" class="form-control"
                           value="{{ old('no_hp', $teknisi->no_hp) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="keahlian">Keahlian <span style="color:red">*</span></label>
                    <input type="text" id="keahlian" name="keahlian" class="form-control"
                           value="{{ old('keahlian', $teknisi->keahlian) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status <span style="color:red">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="aktif"
                            {{ old('status', $teknisi->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak aktif"
                            {{ old('status', $teknisi->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="/teknisi" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
