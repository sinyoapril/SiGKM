@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between py-3 mb-4">
        <h4 class="fw-bold">Detail Jadwal Monev</h4><a href="{{ route('jadwal-monev.index') }}"
            class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <x-detail-row label="Semester">
                {{ $jadwalMonev->semester?->label }}
            </x-detail-row>
            <x-detail-row label="Termin">
                {{ $jadwalMonev->termin?->nama_termin }}
            </x-detail-row>
            <x-detail-row label="Periode">
                {{ $jadwalMonev->tanggal_mulai?->format('d-m-Y') }} s.d.
                {{ $jadwalMonev->tanggal_selesai?->format('d-m-Y') }}
            </x-detail-row>
            <x-detail-row label="Status">
                <span class="badge bg-label-primary">
                    {{ ucfirst($jadwalMonev->status) }}
                </span>
            </x-detail-row>
            <x-detail-row label="Dibuat oleh">
                {{ $jadwalMonev->pembuat?->name }}
            </x-detail-row>
        </div>
    </div>
    <div class="card">
        <h5 class="card-header">Ringkasan Perkuliahan</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Kelas</th>
                        <th>Pertemuan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalMonev->ringkasanPerkuliahans as $item)
                        <tr>
                            <td>{{ $item->perkuliahan?->mataKuliah?->nama_mk ?? '-' }}</td>
                            <td>{{ $item->perkuliahan?->kelas?->nama_kelas ?? '-' }}</td>
                            <td>{{ $item->jumlah_pertemuan }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td><a href="{{ route('ringkasan-perkuliahan.show', $item) }}"
                                    class="btn btn-sm btn-info">Detail</a></td>
                    </tr>@empty<tr>
                            <td colspan="5" class="text-center">
                                @if (auth()->user()->hasRole('dosen'))
                                    Belum ada ringkasan perkuliahan terkait Anda.
                                @else
                                    Belum ada ringkasan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
