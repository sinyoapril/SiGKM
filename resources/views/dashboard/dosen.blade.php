@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Dashboard Dosen</h4>
            <p class="text-muted mb-0">Perkuliahan, temuan mutu, dan tindak lanjut yang menjadi tanggung jawab Anda.</p>
        </div>
    </div>
    <div class="row">
        @foreach ($stats as $item)
            <x-dashboard-stat :item="$item" />
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <h5 class="card-header">Temuan Terbaru</h5>
                <div class="list-group list-group-flush">
                    @forelse($temuanTerbaru as $item)
                        <a href="{{ route('temuan-evaluasi.show', $item) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between"><strong>{{ $item->kode_temuan }}</strong><span
                                    class="badge bg-label-{{ $item->status === 'ditutup' ? 'success' : 'warning' }}">{{ ucfirst($item->status) }}</span>
                            </div>
                            {{ Str::limit($item->pernyataan, 100) }}<br><small>{{ $item->evaluasiIndikator?->semester?->label }}</small>
                    </a>@empty<div class="p-3 text-muted">Tidak ada temuan.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <h5 class="card-header">RTL Terbaru</h5>
                <div class="list-group list-group-flush">
                    @forelse($rtlTerbaru as $item)
                        <a href="{{ route('rtl.show', $item) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $item->temuan?->kode_temuan }}</strong><span
                                    class="badge bg-label-primary">{{ ucfirst($item->status) }}</span>
                            </div>
                            {{ Str::limit($item->uraian_rencana_tindak_lanjut, 100) }}<br><small>{{ $item->buktiTindakLanjuts->count() }}
                                bukti</small>
                    </a>@empty<div class="p-3 text-muted">Belum ada RTL.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <h5 class="card-header">Perkuliahan Aktif</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perkuliahanAktif as $item)
                        <tr>
                            <td><strong>{{ $item->mataKuliah?->kode_mk }}</strong> — {{ $item->mataKuliah?->nama_mk }}
                            </td>
                            <td>{{ $item->kelas?->nama_kelas }}</td>
                            <td>{{ $item->semester?->label }}</td>
                            <td><span class="badge bg-label-success">Aktif</span></td>
                    </tr>@empty<tr>
                            <td colspan="4" class="text-center">Tidak ada perkuliahan aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
