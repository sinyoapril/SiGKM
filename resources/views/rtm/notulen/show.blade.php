@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between py-3 mb-4">
        <h4 class="fw-bold">Detail Notulen RTM</h4><a href="{{ route('notulen-rtm.index') }}"
            class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <x-detail-row label="Jadwal RTM">
                <a href="{{ route('jadwal-rtm.show', $notulenRtm->jadwalRtm) }}">
                    {{ $notulenRtm->jadwalRtm?->judul }}
                </a>
            </x-detail-row>
            <x-detail-row label="Semester">
                {{ $notulenRtm->jadwalRtm?->semester?->label }}
            </x-detail-row>
            <x-detail-row label="Isi Notulen">
                <span style="white-space:pre-line">
                    {{ $notulenRtm->isi_notulen }}
                </span>
            </x-detail-row>
            <x-detail-row label="Status">
                <span class="badge bg-label-primary">
                    {{ ucfirst($notulenRtm->status) }}
                </span>
            </x-detail-row>
            <x-detail-row label="Penginput">
                {{ $notulenRtm->penginput?->name ?? '-' }}
            </x-detail-row>
            <x-detail-row label="Verifikator">
                {{ $notulenRtm->verifikator?->name ?? '-' }} @if ($notulenRtm->verified_at)
                    ({{ $notulenRtm->verified_at->format('d-m-Y H:i') }})
                @endif
            </x-detail-row>
            <x-detail-row label="Catatan Verifikasi">
                {{ $notulenRtm->catatan_verifikasi ?: '-' }}
            </x-detail-row>
        </div>
    </div>
    <div class="card">
        <h5 class="card-header">Keputusan RTM</h5>
        <div class="list-group list-group-flush">
            @forelse($notulenRtm->keputusanRtms as $keputusan)
                <a href="{{ route('keputusan-rtm.show', $keputusan) }}"
                    class="list-group-item list-group-item-action"><strong>{{ $keputusan->rencanaTindakLanjut?->temuan?->kode_temuan }}</strong>
                — {{ Str::limit($keputusan->uraian_keputusan, 140) }}</a>@empty<div class="p-3 text-muted">Belum ada
                    keputusan.</div>
            @endforelse
        </div>
    </div>
@endsection
