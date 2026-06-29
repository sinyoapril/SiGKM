@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Tambah Termin</h4>

        <a href="{{ route('termin.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Tambah Termin</h5>

        <div class="card-body">
            <form action="{{ route('termin.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Termin</label>
                    <input type="text" name="nama_termin" class="form-control @error('nama_termin') is-invalid @enderror"
                        value="{{ old('nama_termin') }}" placeholder="Contoh: Awal Semester">

                    @error('nama_termin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                        placeholder="Masukkan keterangan jika ada">{{ old('keterangan') }}</textarea>

                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
