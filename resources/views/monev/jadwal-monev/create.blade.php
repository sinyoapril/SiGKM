@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Tambah Jadwal Monev</h4>

        <a href="{{ route('jadwal-monev.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Tambah Jadwal Monev</h5>

        <div class="card-body">
            <form action="{{ route('jadwal-monev.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select @error('semester_id') is-invalid @enderror">
                        <option value="">-- Pilih Semester --</option>

                        @foreach ($semester as $item)
                            <option value="{{ $item->id }}" {{ old('semester_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->tahunAkademik->nama ?? '-' }}
                                -
                                {{ ucfirst($item->nama ?? '-') }}
                                {{ $item->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>

                    @error('semester_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Termin</label>
                    <select name="termin_id" class="form-select @error('termin_id') is-invalid @enderror">
                        <option value="">-- Pilih Termin --</option>

                        @foreach ($termin as $item)
                            <option value="{{ $item->id }}" {{ old('termin_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_termin }}
                            </option>
                        @endforeach
                    </select>

                    @error('termin_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai"
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai') }}">

                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai') }}">

                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="selesai" {{ old('status') === 'selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        Jika status dibuat aktif, jadwal monev lain pada semester yang sama akan otomatis menjadi selesai.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
