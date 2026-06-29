@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Tambah Indikator Mutu</h4>

        <a href="{{ route('indikator-mutu.index', ['standar_mutu_id' => $selectedStandarMutuId]) }}"
            class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Tambah Indikator Mutu</h5>

        <div class="card-body">
            <form action="{{ route('indikator-mutu.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Standar Mutu</label>
                    <select name="standar_mutu_id" class="form-select @error('standar_mutu_id') is-invalid @enderror">
                        <option value="">-- Pilih Standar Mutu --</option>

                        @foreach ($standarMutu as $item)
                            <option value="{{ $item->id }}"
                                {{ old('standar_mutu_id', $selectedStandarMutuId) == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_standar ? $item->kode_standar . ' - ' : '' }}
                                {{ $item->nama_standar }}
                            </option>
                        @endforeach
                    </select>

                    @error('standar_mutu_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode Indikator</label>
                    <input type="text" name="kode_indikator"
                        class="form-control @error('kode_indikator') is-invalid @enderror"
                        value="{{ old('kode_indikator') }}" placeholder="Contoh: IKU-01">

                    @error('kode_indikator')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Indikator</label>
                    <textarea name="isi_indikator" rows="4" class="form-control @error('isi_indikator') is-invalid @enderror"
                        placeholder="Masukkan isi indikator mutu">{{ old('isi_indikator') }}</textarea>

                    @error('isi_indikator')
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
