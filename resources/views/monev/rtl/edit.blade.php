@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Edit RTL</h4>

        <a href="{{ route('rtl.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Edit RTL</h5>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rtl.update', $rtl->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Temuan Evaluasi</label>
                    <select name="temuan_id" class="form-select @error('temuan_id') is-invalid @enderror">
                        <option value="">-- Pilih Temuan Evaluasi --</option>
                        @foreach ($temuan as $item)
                            <option value="{{ $item->id }}"
                                {{ old('temuan_id', $rtl->temuan_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_temuan }}
                                |
                                {{ $item->evaluasiIndikator->semester->tahunAkademik->nama ?? '-' }}
                                -
                                {{ $item->evaluasiIndikator->semester->nama ?? '-' }}
                                |
                                {{ \Illuminate\Support\Str::limit($item->pernyataan, 90) }}
                                |
                                Rekomendasi: {{ \Illuminate\Support\Str::limit($item->rencana_awal ?? '-', 70) }}
                            </option>
                        @endforeach
                    </select>

                    @error('temuan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Uraian Rencana Tindak Lanjut</label>
                    <textarea name="uraian_rencana_tindak_lanjut" rows="5"
                        class="form-control @error('uraian_rencana_tindak_lanjut') is-invalid @enderror">{{ old('uraian_rencana_tindak_lanjut', $rtl->uraian_rencana_tindak_lanjut) }}</textarea>

                    @error('uraian_rencana_tindak_lanjut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Uraian Tindak Koreksi</label>
                    <textarea name="uraian_tindak_koreksi" rows="4"
                        class="form-control @error('uraian_tindak_koreksi') is-invalid @enderror"
                        placeholder="Isi jika ada koreksi atau tindakan berbeda dari rekomendasi awal">{{ old('uraian_tindak_koreksi', $rtl->uraian_tindak_koreksi) }}</textarea>

                    @error('uraian_tindak_koreksi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tambah Bukti Tindak Lanjut</label>
                    <input type="file" name="bukti[]" multiple
                        class="form-control @error('bukti') is-invalid @enderror @error('bukti.*') is-invalid @enderror">
                    @error('bukti')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('bukti.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Kosongkan jika tidak ingin menambah bukti baru.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan Bukti Baru</label>
                    <textarea name="keterangan_bukti[]" rows="3" class="form-control"
                        placeholder="Keterangan umum untuk bukti baru">{{ old('keterangan_bukti.0') }}</textarea>
                </div>

                @if ($rtl->buktiTindakLanjuts->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label">Bukti Saat Ini</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach ($rtl->buktiTindakLanjuts as $bukti)
                                <a href="{{ asset('storage/' . $bukti->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-file"></i> Bukti {{ $loop->iteration }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Target Selesai</label>
                    <input type="date" name="target_selesai"
                        class="form-control @error('target_selesai') is-invalid @enderror"
                        value="{{ old('target_selesai', $rtl->target_selesai?->format('Y-m-d')) }}">

                    @error('target_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Saat Ini</label>
                    <input type="text" class="form-control"
                        value="{{ ucwords(str_replace('_', ' ', $rtl->status)) }}" readonly>
                </div>

                @if ($rtl->catatan_verifikasi)
                    <div class="alert alert-info">
                        <strong>Catatan Verifikasi:</strong>
                        <br>
                        {{ $rtl->catatan_verifikasi }}
                    </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" name="aksi" value="draft" class="btn btn-secondary">
                        <i class="bx bx-save"></i> Simpan Draft
                    </button>

                    <button type="submit" name="aksi" value="ajukan" class="btn btn-primary">
                        <i class="bx bx-send"></i> Simpan dan Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
