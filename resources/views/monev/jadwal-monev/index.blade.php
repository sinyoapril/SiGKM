@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Jadwal Monev</h4>

        @if (auth()->user()->hasAnyRole(['ketua-gkm', 'anggota-gkm']))
            <a href="{{ route('jadwal-monev.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Jadwal Monev
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">Data Jadwal Monev</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Akademik</th>
                        <th>Semester</th>
                        <th>Termin</th>
                        <th>Periode</th>
                        <th>Dibuat Oleh</th>
                        <th>Status</th>
                        <th width="240">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($jadwalMonev as $item)
                        <tr>
                            <td>{{ $jadwalMonev->firstItem() + $loop->index }}</td>
                            <td>
                                {{ $item->semester->tahunAkademik->nama ?? '-' }}
                            </td>
                            <td>
                                {{ ucfirst($item->semester->nama ?? '-') }}
                            </td>
                            <td>
                                <strong>{{ $item->termin->nama_termin ?? '-' }}</strong>
                            </td>
                            <td>
                                {{ $item->tanggal_mulai?->format('d-m-Y') }}
                                s/d
                                {{ $item->tanggal_selesai?->format('d-m-Y') }}
                            </td>
                            <td>{{ $item->pembuat->name ?? '-' }}</td>
                            <td>
                                @if ($item->status === 'aktif')
                                    <span class="badge bg-label-success">Aktif</span>
                                @elseif ($item->status === 'selesai')
                                    <span class="badge bg-label-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-label-warning">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('jadwal-monev.show', $item) }}" class="btn btn-sm btn-icon btn-info"><i class="bx bx-show"></i></a>

                                @if (auth()->user()->hasAnyRole(['ketua-gkm', 'anggota-gkm']))
                                    @if ($item->status !== 'aktif')
                                        <form action="{{ route('jadwal-monev.set-active', $item->id) }}" method="POST"
                                            class="d-inline" data-confirm-form
                                            data-confirm-title="Aktifkan jadwal monev?"
                                            data-confirm-text="Jadwal lain pada semester yang sama akan menjadi selesai."
                                            data-confirm-icon="question" data-confirm-button-text="Ya, aktifkan"
                                            data-confirm-button-color="#71dd37">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bx bx-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($item->status === 'aktif')
                                        <form action="{{ route('jadwal-monev.finish', $item->id) }}" method="POST"
                                            class="d-inline" data-confirm-form
                                            data-confirm-title="Selesaikan jadwal monev?"
                                            data-confirm-text="Jadwal monev akan ditandai selesai."
                                            data-confirm-icon="question" data-confirm-button-text="Ya, selesaikan">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-icon btn-secondary">
                                                <i class="bx bx-check-double"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('jadwal-monev.edit', $item->id) }}" class="btn btn-sm btn-icon btn-warning">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <form action="{{ route('jadwal-monev.destroy', $item->id) }}" method="POST"
                                        class="d-inline" data-confirm-form
                                        data-confirm-title="Hapus jadwal monev?"
                                        data-confirm-text="Data jadwal monev akan dihapus permanen."
                                        data-confirm-icon="warning" data-confirm-button-text="Ya, hapus"
                                        data-confirm-button-color="#ff3e1d">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-icon btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Data jadwal monev belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<div class="mt-3">@include('components._pagination', ['paginator' => $jadwalMonev])</div>
@endsection
