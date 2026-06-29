@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between py-3 mb-4">
        <h4 class="fw-bold">Detail Rencana Tindak Lanjut</h4><a href="{{ route('rtl.index') }}"
            class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <x-detail-row label="Temuan">
                <a href="{{ route('temuan-evaluasi.show', $rtl->temuan) }}">
                    {{ $rtl->temuan?->kode_temuan }}
                </a> — {{ $rtl->temuan?->pernyataan }}
            </x-detail-row>
            <x-detail-row label="Semester">
                {{ $rtl->temuan?->evaluasiIndikator?->semester?->label }}
            </x-detail-row>
            <x-detail-row label="Indikator">
                {{ $rtl->temuan?->evaluasiIndikator?->sumber_kode }} —
                {{ $rtl->temuan?->evaluasiIndikator?->sumber_uraian }}
            </x-detail-row>
            <x-detail-row label="Dosen">
                <span>
                    {{ $rtl->temuan?->dosen?->nama_dosen }}
                </span>
            </x-detail-row>
            <x-detail-row label="Uraian RTL">
                <span style="white-space:pre-line">
                    {{ $rtl->uraian_rencana_tindak_lanjut }}</span>
            </x-detail-row>
            <x-detail-row label="Tindak Koreksi">
                <span style="white-space:pre-line">
                    {{ $rtl->uraian_tindak_koreksi ?: '-' }}
                </span>
            </x-detail-row>
            <x-detail-row label="Target Selesai">
                {{ $rtl->target_selesai?->format('d-m-Y') ?? '-' }}
            </x-detail-row>
            <x-detail-row label="Status">
                <span class="badge bg-label-primary">{{ ucfirst($rtl->status) }}
                </span>
            </x-detail-row>
            <x-detail-row label="Verifikator">
                {{ $rtl->verifikator?->name ?? '-' }} @if ($rtl->verified_at)
                    ({{ $rtl->verified_at->format('d-m-Y H:i') }})
                @endif
            </x-detail-row>
            <x-detail-row label="Catatan Verifikasi">
                <span style="white-space:pre-line">
                    {{ $rtl->catatan_verifikasi ?: '-' }}
                </span>
            </x-detail-row>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Bukti RTL</h5>
                <div class="card-body">
                    @forelse($rtl->buktiTindakLanjuts as $bukti)
                        <a href="{{ asset('storage/' . $bukti->file_path) }}" target="_blank"
                        class="btn btn-outline-primary mb-2">{{ $bukti->keterangan ?: 'Bukti ' . $loop->iteration }}</a>@empty<span
                            class="text-muted">Belum ada bukti.</span>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Keputusan RTM</h5>
                <div class="list-group list-group-flush">
                    @forelse($rtl->keputusanRtms as $keputusan)
                        <a href="{{ route('keputusan-rtm.show', $keputusan) }}"
                        class="list-group-item list-group-item-action">{{ Str::limit($keputusan->uraian_keputusan, 120) }}</a>@empty
                        <div class="p-3 text-muted">Belum dibahas pada RTM.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
