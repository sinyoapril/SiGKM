@extends('layouts.app')

@php
    $editing = isset($item) && $item;
    $field = match ($jenis) {
        'sasaran' => 'sasaran',
        'iku' => 'iku',
        'ikk' => 'ikk',
        'ikks' => 'ikks',
    };
    $parentField = match ($jenis) {
        'iku' => 'sasaran_strategis_id',
        'ikk' => 'indikator_kinerja_utama_id',
        'ikks' => 'indikator_kinerja_kegiatan_id',
        default => null,
    };
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">{{ $editing ? 'Edit' : 'Tambah' }} {{ $label }}</h4>
        <a href="{{ route('kinerja-program-studi.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back"></i>
            Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST"
                action="{{ $editing ? route('kinerja-program-studi.update', ['jenis' => $jenis, 'id' => $item]) : route('kinerja-program-studi.store', $jenis) }}">
                @csrf @if ($editing)
                    @method('PUT')
                @endif

                @if ($parentField)
                    <div class="mb-3">
                        <label class="form-label">Induk
                            {{ $jenis === 'iku' ? 'Sasaran Strategis' : ($jenis === 'ikk' ? 'IKU' : 'IKK') }}</label>
                        <select name="{{ $parentField }}" class="form-select @error($parentField) is-invalid @enderror">
                            <option value="">-- Pilih Induk --</option>
                            @foreach ($parents as $parent)
                                @php
                                    $parentLabel = match ($jenis) {
                                        'iku' => ($parent->kode_sasaran ?: 'Sasaran') . ' — ' . $parent->uraian_sasaran,
                                        'ikk' => ($parent->kode_iku ?: 'IKU') . ' — ' . $parent->uraian_iku,
                                        'ikks' => ($parent->kode_ikk ?: 'IKK') . ' — ' . $parent->uraian_ikk,
                                    };
                                @endphp
                                <option value="{{ $parent->id }}" @selected(old($parentField, $item?->{$parentField} ?? request('parent_id')) == $parent->id)>{{ $parentLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error($parentField)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Kode {{ strtoupper($field) }}</label>
                    <input name="kode_{{ $field }}"
                        class="form-control @error('kode_' . $field) is-invalid @enderror"
                        value="{{ old('kode_' . $field, $item?->{'kode_' . $field}) }}" maxlength="50">
                    @error('kode_' . $field)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Uraian {{ $label }}</label>
                    <textarea name="uraian_{{ $field }}" rows="5"
                        class="form-control @error('uraian_' . $field) is-invalid @enderror">{{ old('uraian_' . $field, $item?->{'uraian_' . $field}) }}</textarea>
                    @error('uraian_' . $field)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active"
                        @checked(old('is_active', $item?->is_active ?? true))>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>
                <button class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
            </form>
        </div>
    </div>
@endsection
