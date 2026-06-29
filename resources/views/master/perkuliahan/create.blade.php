@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Tambah Perkuliahan</h4>

        <a href="{{ route('perkuliahan.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Tambah Perkuliahan</h5>

        <div class="card-body">
            <form action="{{ route('perkuliahan.store') }}" method="POST">
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
                    <label class="form-label">Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="form-select @error('mata_kuliah_id') is-invalid @enderror">
                        <option value="">-- Pilih Mata Kuliah --</option>

                        @foreach ($mataKuliah as $item)
                            <option value="{{ $item->id }}" {{ old('mata_kuliah_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_mk }} - {{ $item->nama_mk }} ({{ $item->sks }} SKS)
                            </option>
                        @endforeach
                    </select>

                    @error('mata_kuliah_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror">
                        <option value="">-- Pilih Kelas --</option>

                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dosen Pengajar</label>
                    <select name="dosen_ids[]" class="form-select @error('dosen_ids') is-invalid @enderror @error('dosen_ids.*') is-invalid @enderror">
                        <option value="">-- Pilih Dosen Pengajar --</option>

                        @foreach ($dosen as $item)
                            <option value="{{ $item->id }}"
                                {{ old('dosen_ids.0') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_dosen }}
                            </option>
                        @endforeach
                    </select>

                    @error('dosen_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @error('dosen_ids.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>

                    @error('status')
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
