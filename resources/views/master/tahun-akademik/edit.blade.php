@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Edit Tahun Akademik</h4>

        <a href="{{ route('tahun-akademik.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Edit Tahun Akademik</h5>

        <div class="card-body">
            <form action="{{ route('tahun-akademik.update', $tahunAkademik->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tahun Akademik</label>
                    <input type="text" name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $tahunAkademik->nama) }}"
                        placeholder="Contoh: 2025/2026">

                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $tahunAkademik->is_active) ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Jadikan tahun akademik aktif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
