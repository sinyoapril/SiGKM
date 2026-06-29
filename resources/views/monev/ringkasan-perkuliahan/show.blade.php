@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between py-3 mb-4">
        <h4 class="fw-bold">Detail Ringkasan Perkuliahan</h4><a href="{{ route('ringkasan-perkuliahan.index') }}"
            class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <x-detail-row label="Jadwal Monev">
                {{ $ringkasanPerkuliahan->jadwalMonev?->semester?->label }} —
                {{ $ringkasanPerkuliahan->jadwalMonev?->termin?->nama_termin }}
            </x-detail-row>
            <x-detail-row label="Mata Kuliah">
                {{ $ringkasanPerkuliahan->perkuliahan?->mataKuliah?->nama_mk ?? '-' }}
            </x-detail-row>
            <x-detail-row label="Kelas">
                {{ $ringkasanPerkuliahan->perkuliahan?->kelas?->nama_kelas ?? '-' }}
            </x-detail-row>
            <x-detail-row label="Dosen">
                {{ $ringkasanPerkuliahan->perkuliahan?->pengajars?->pluck('dosen.nama_dosen')->filter()->join(', ') ?: '-' }}
            </x-detail-row>
            <x-detail-row label="Jumlah Pertemuan">
                {{ $ringkasanPerkuliahan->jumlah_pertemuan }}
            </x-detail-row>
            <x-detail-row label="Kesesuaian Materi">
                {{ str($ringkasanPerkuliahan->kesesuaian_materi)->replace('_', ' ')->title() }}
            </x-detail-row>
            <x-detail-row label="Keterangan (Temuan/Masalah)"><span
                    style="white-space:pre-line">{{ $ringkasanPerkuliahan->keterangan ?: '-' }}</span></x-detail-row>
            <x-detail-row label="Status"><span
                    class="badge bg-label-primary">{{ ucfirst($ringkasanPerkuliahan->status) }}</span></x-detail-row>
            <x-detail-row label="Penginput">{{ $ringkasanPerkuliahan->penginput?->name }}</x-detail-row>
            <x-detail-row label="Verifikator">{{ $ringkasanPerkuliahan->verifikator?->name ?? '-' }} @if ($ringkasanPerkuliahan->verified_at)
                    ({{ $ringkasanPerkuliahan->verified_at->format('d-m-Y H:i') }})
                @endif
            </x-detail-row>
            <x-detail-row label="Catatan Verifikasi">{{ $ringkasanPerkuliahan->catatan_verifikasi ?: '-' }}</x-detail-row>
        </div>
    </div>
@endsection
