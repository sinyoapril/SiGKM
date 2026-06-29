@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between py-3 mb-4">
        <h4 class="fw-bold">Detail Keputusan RTM</h4><a href="{{ route('keputusan-rtm.index') }}"
            class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <x-detail-row label="RTM">
                <a href="{{ route('notulen-rtm.show', $keputusanRtm->notulenRtm) }}">
                    {{ $keputusanRtm->notulenRtm?->jadwalRtm?->judul }}
                </a>
            </x-detail-row>
            <x-detail-row label="Semester RTM">
                {{ $keputusanRtm->notulenRtm?->jadwalRtm?->semester?->label }}
            </x-detail-row>
            <x-detail-row label="Uraian Keputusan">
                <span style="white-space:pre-line">
                    {{ $keputusanRtm->uraian_keputusan }}
                </span>
            </x-detail-row>
            <x-detail-row label="Strategi">
                <span style="white-space:pre-line">
                    {{ $keputusanRtm->strategi ?: '-' }}
                </span>
            </x-detail-row>
            <x-detail-row label="Target Selesai">
                {{ $keputusanRtm->target_selesai?->format('d-m-Y') ?? '-' }}
            </x-detail-row>
            <x-detail-row label="Status">
                <span class="badge bg-label-primary">
                    {{ str($keputusanRtm->status)->replace('_', ' ')->title() }}
                </span>
            </x-detail-row>
        </div>
    </div>
    @php($rtl = $keputusanRtm->rencanaTindakLanjut)<div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>RTL yang Ditinjau</h5>
            @if ($rtl)
                <a href="{{ route('rtl.show', $rtl) }}" class="btn btn-sm btn-info">Detail RTL</a>
            @endif
        </div>
        <div class="card-body">
            <x-detail-row label="Kode Temuan">
                {{ $rtl?->temuan?->kode_temuan }}
            </x-detail-row>
            <x-detail-row label="Semester RTL">
                {{ $rtl?->temuan?->evaluasiIndikator?->semester?->label }}
            </x-detail-row>
            <x-detail-row label="Indikator">
                {{ $rtl?->temuan?->evaluasiIndikator?->sumber_uraian }}
            </x-detail-row>
            <x-detail-row label="Dosen">
                {{ $rtl?->temuan?->dosen?->nama_dosen }}
            </x-detail-row>
            <x-detail-row label="Uraian RTL">
                <span style="white-space:pre-line">
                    {{ $rtl?->uraian_rencana_tindak_lanjut }}
                </span>
            </x-detail-row>
        </div>
    </div>
@endsection
