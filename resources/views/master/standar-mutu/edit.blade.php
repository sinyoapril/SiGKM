@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Edit Standar Mutu Fakultas</h4>

        <a href="{{ route('standar-mutu.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Edit Standar Mutu</h5>

        <div class="card-body">
            <form action="{{ route('standar-mutu.update', $standarMutu->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode Standar</label>
                    <input type="text" name="kode_standar"
                        class="form-control @error('kode_standar') is-invalid @enderror"
                        value="{{ old('kode_standar', $standarMutu->kode_standar) }}" placeholder="Contoh: STD-01">

                    @error('kode_standar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Standar</label>
                    <input type="text" name="nama_standar"
                        class="form-control @error('nama_standar') is-invalid @enderror"
                        value="{{ old('nama_standar', $standarMutu->nama_standar) }}">

                    @error('nama_standar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $standarMutu->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $standarMutu->is_active) ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
