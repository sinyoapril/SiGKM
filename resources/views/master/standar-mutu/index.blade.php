@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Standar Mutu Fakultas</h4>

        <a href="{{ route('standar-mutu.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Standar Mutu
        </a>
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
        <h5 class="card-header">Data Standar Mutu</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width:120px">Kode</th>
                        <th>Nama Standar</th>
                        <th>Indikator</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($standarMutu as $item)
                        <tr>
                            <td>{{ $standarMutu->firstItem() + $loop->index }}</td>
                            <td>{{ $item->kode_standar ?? '-' }}</td>
                            <td>
                                <strong>{{ $item->nama_standar }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">
                                    {{ $item->indikator_mutus_count }} indikator
                                </span>
                            </td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-label-success">Aktif</span>
                                @else
                                    <span class="badge bg-label-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('indikator-mutu.index', ['standar_mutu_id' => $item->id]) }}"
                                    class="btn btn-sm btn-icon btn-info">
                                    <i class="bx bx-list-ul"></i>
                                </a>

                                <a href="{{ route('standar-mutu.edit', $item->id) }}" class="btn btn-sm btn-icon btn-warning">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <form action="{{ route('standar-mutu.destroy', $item->id) }}" method="POST"
                                    class="d-inline" data-confirm-form data-confirm-title="Yakin ingin menghapus data ini?"
                                    data-confirm-text="Data standar mutu yang dihapus tidak dapat dikembalikan."
                                    data-confirm-button-text="Ya, hapus" data-confirm-button-color="#ff3e1d">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-icon btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Data standar mutu belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<div class="mt-3">@include('components._pagination', ['paginator' => $standarMutu])</div>
@endsection
