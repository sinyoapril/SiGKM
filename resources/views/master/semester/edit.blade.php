@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Edit Semester</h4>

        <a href="{{ route('semester.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Edit Semester</h5>

        <div class="card-body">
            <form action="{{ route('semester.update', $semester->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tahun Akademik</label>
                    <select name="tahun_akademik_id" class="form-select @error('tahun_akademik_id') is-invalid @enderror">
                        <option value="">-- Pilih Tahun Akademik --</option>

                        @foreach ($tahunAkademik as $item)
                            <option value="{{ $item->id }}"
                                {{ old('tahun_akademik_id', $semester->tahun_akademik_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('tahun_akademik_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <select name="nama" class="form-select @error('nama') is-invalid @enderror">
                        <option value="">-- Pilih Semester --</option>
                        <option value="ganjil" {{ old('nama', $semester->nama) == 'ganjil' ? 'selected' : '' }}>
                            Ganjil
                        </option>
                        <option value="genap" {{ old('nama', $semester->nama) == 'genap' ? 'selected' : '' }}>
                            Genap
                        </option>
                    </select>

                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Jadikan semester aktif
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
