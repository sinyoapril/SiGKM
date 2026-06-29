@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Perkuliahan</h4>

        <a href="{{ route('perkuliahan.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Perkuliahan
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
        <h5 class="card-header">Data Perkuliahan</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Akademik</th>
                        <th>Semester</th>
                        <th>Mata Kuliah</th>
                        <th>Kelas</th>
                        <th>Dosen Pengajar</th>
                        <th>Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($perkuliahan as $item)
                        <tr>
                            <td>{{ $perkuliahan->firstItem() + $loop->index }}</td>
                            <td>
                                {{ $item->semester->tahunAkademik->nama ?? '-' }}
                            </td>
                            <td>
                                {{ ucfirst($item->semester->nama ?? '-') }}
                            </td>
                            <td>
                                <strong>
                                    {{ $item->mataKuliah->kode_mk ?? '-' }}
                                    -
                                    {{ $item->mataKuliah->nama_mk ?? '-' }}
                                </strong>
                            </td>
                            <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                            <td>
                                {{ $item->pengajars->pluck('dosen.nama_dosen')->filter()->join(', ') ?: '-' }}
                            </td>
                            <td>
                                @if ($item->status === 'aktif')
                                    <span class="badge bg-label-success">Aktif</span>
                                @else
                                    <span class="badge bg-label-secondary">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('perkuliahan.edit', $item->id) }}" class="btn btn-sm btn-icon btn-warning">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <form action="{{ route('perkuliahan.destroy', $item->id) }}" method="POST"
                                    class="d-inline" data-confirm-form data-confirm-title="Yakin ingin menghapus data ini?"
                                    data-confirm-text="Data perkuliahan yang dihapus tidak dapat dikembalikan."
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
                            <td colspan="8" class="text-center">
                                Data perkuliahan belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<div class="mt-3">@include('components._pagination', ['paginator' => $perkuliahan])</div>
@endsection
