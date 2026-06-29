@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Tambah Keanggotaan GKM</h4>

        <a href="{{ route('gkm-membership.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Tambah Keanggotaan GKM</h5>

        <div class="card-body">
            <form action="{{ route('gkm-membership.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Dosen</label>
                    <select name="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror">
                        <option value="">-- Pilih Dosen --</option>

                        @foreach ($dosen as $item)
                            <option value="{{ $item->id }}" {{ old('dosen_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_dosen }}
                            </option>
                        @endforeach
                    </select>

                    @error('dosen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Peran</label>
                    <select name="peran" class="form-select @error('peran') is-invalid @enderror">
                        <option value="">-- Pilih Peran --</option>
                        <option value="ketua" {{ old('peran') == 'ketua' ? 'selected' : '' }}>
                            Ketua GKM
                        </option>
                        <option value="anggota" {{ old('peran') == 'anggota' ? 'selected' : '' }}>
                            Anggota GKM
                        </option>
                    </select>

                    @error('peran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai"
                        class="form-control @error('tanggal_mulai') is-invalid @enderror"
                        value="{{ old('tanggal_mulai') }}">

                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai"
                        class="form-control @error('tanggal_selesai') is-invalid @enderror"
                        value="{{ old('tanggal_selesai') }}">

                    @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', true) ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
