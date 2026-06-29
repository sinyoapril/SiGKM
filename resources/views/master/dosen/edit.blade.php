@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Edit Dosen</h4>

        <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Form Edit Dosen</h5>

        <div class="card-body">
            <form action="{{ route('dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Dosen</label>
                    <input type="text" name="nama_dosen" class="form-control @error('nama_dosen') is-invalid @enderror"
                        value="{{ old('nama_dosen', $dosen->nama_dosen) }}" placeholder="Masukkan nama dosen">

                    @error('nama_dosen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                        value="{{ old('nip', $dosen->nip) }}" placeholder="Masukkan NIP">

                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">NIDN</label>
                    <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror"
                        value="{{ old('nidn', $dosen->nidn) }}" placeholder="Masukkan NIDN">

                    @error('nidn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $dosen->email) }}" placeholder="Masukkan email dosen">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                {{-- <div class="mb-3">
                    <label class="form-label">File Penelitian</label>
                    <input type="file" name="file_penelitian"
                        class="form-control @error('file_penelitian') is-invalid @enderror">

                    @if ($dosen->file_penelitian)
                        <small class="d-block mt-2">
                            File saat ini:
                            <a href="{{ asset('storage/' . $dosen->file_penelitian) }}" target="_blank">
                                Lihat file
                            </a>
                        </small>
                    @endif

                    @error('file_penelitian')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
