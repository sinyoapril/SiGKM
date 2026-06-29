@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Dosen</h4>

        <a href="{{ route('dosen.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Dosen
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
        <h5 class="card-header">Data Dosen</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>NIP</th>
                        <th>NIDN</th>
                        {{-- <th>Email</th> --}}
                        {{-- <th>File Penelitian</th> --}}
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($dosen as $item)
                        <tr>
                            <td>{{ $dosen->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $item->nama_dosen }}</strong></td>
                            <td>{{ $item->nip ?? '-' }}</td>
                            <td>{{ $item->nidn ?? '-' }}</td>
                            {{-- <td>{{ $item->email ?? '-' }}</td> --}}
                            {{-- <td>
                                @if ($item->file_penelitian)
                                    <a href="{{ asset('storage/' . $item->file_penelitian) }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="bx bx-file"></i> Lihat
                                    </a>
                                @else
                                    -
                                @endif
                            </td> --}}
                            <td>
                                <a href="{{ route('dosen.edit', $item->id) }}" class="btn btn-sm btn-icon btn-warning">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <form action="{{ route('dosen.destroy', $item->id) }}" method="POST" class="d-inline"
                                    data-confirm-form data-confirm-title="Yakin ingin menghapus data ini?"
                                    data-confirm-text="Data dosen yang dihapus tidak dapat dikembalikan."
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
                            <td colspan="7" class="text-center">
                                Data dosen belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<div class="mt-3">@include('components._pagination', ['paginator' => $dosen])</div>
@endsection
