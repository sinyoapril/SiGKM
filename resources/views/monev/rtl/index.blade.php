@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="fw-bold mb-0">Rencana Tindak Lanjut</h4>

        @if (auth()->user()->hasRole('dosen'))
            <a href="{{ route('rtl.create') }}" class="btn btn-primary">
                <i class="bx bx-edit"></i> Isi Tindak Lanjut
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
        <h5 class="card-header">Data Rencana Tindak Lanjut</h5>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Temuan</th>
                        <th>Semester</th>
                        <th>Indikator</th>
                        <th>RTL / Koreksi</th>
                        <th>Bukti</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th width="190">Aksi</th>
                    </tr>
                </thead>

                <tbody class="table-border-bottom-0">
                    @forelse($rtl as $item)
                        @php
                            $statusClass = match ($item->status) {
                                'belum_dikerjakan' => 'bg-label-secondary',
                                'draft' => 'bg-label-secondary',
                                'diajukan' => 'bg-label-warning',
                                'diverifikasi' => 'bg-label-success',
                                'ditolak' => 'bg-label-danger',
                                default => 'bg-label-dark',
                            };
                        @endphp

                        <tr>
                            <td>{{ $rtl->firstItem() + $loop->index }}</td>
                            <td>
                                <strong>{{ $item->temuan->kode_temuan ?? '-' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($item->temuan->pernyataan ?? '-', 55) }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $item->temuan->evaluasiIndikator->semester->nama ?? '-' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $item->temuan->evaluasiIndikator->semester->tahunAkademik->nama ?? '-' }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $item->temuan->evaluasiIndikator->sumber_kode }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($item->temuan->evaluasiIndikator->sumber_uraian, 45) }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ \Illuminate\Support\Str::limit($item->uraian_rencana_tindak_lanjut ?? '-', 60) }}</strong>
                                @if ($item->uraian_tindak_koreksi)
                                    <br>
                                    <small class="text-muted">
                                        Koreksi: {{ \Illuminate\Support\Str::limit($item->uraian_tindak_koreksi, 55) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @forelse ($item->buktiTindakLanjuts as $bukti)
                                    <a href="{{ asset('storage/' . $bukti->file_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="bx bx-file"></i> Bukti {{ $loop->iteration }}
                                    </a>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>
                            <td>
                                @if ($item->target_selesai)
                                    <span class="{{ $item->isOverdue() ? 'text-danger fw-semibold' : '' }}">
                                        {{ $item->target_selesai->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('rtl.show', $item) }}" class="btn btn-sm btn-icon btn-info"><i
                                        class="bx bx-show"></i></a>
                                @if ($item->canBeEditedBy(auth()->user()))
                                    <a href="{{ route('rtl.edit', $item->id) }}" class="btn btn-sm btn-icon btn-warning">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    <form action="{{ route('rtl.submit', $item->id) }}" method="POST" class="d-inline"
                                        data-confirm-form data-confirm-title="Ajukan RTL?"
                                        data-confirm-text="RTL akan dikirim ke GKM untuk diverifikasi."
                                        data-confirm-icon="question" data-confirm-button-text="Ya, ajukan">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bx bx-send"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('rtl.destroy', $item->id) }}" method="POST" class="d-inline"
                                        data-confirm-form data-confirm-title="Hapus RTL?"
                                        data-confirm-text="Data RTL akan dihapus permanen." data-confirm-icon="warning"
                                        data-confirm-button-text="Ya, hapus" data-confirm-button-color="#ff3e1d">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                @endif

                                @if ($item->canBeVerifiedBy(auth()->user()))
                                    <form action="{{ route('rtl.verify', $item->id) }}" method="POST" class="d-inline"
                                        data-confirm-form data-confirm-title="Verifikasi RTL?"
                                        data-confirm-text="RTL yang diverifikasi dapat dilanjutkan ke proses tindak lanjut."
                                        data-confirm-icon="question" data-confirm-button-text="Ya, verifikasi"
                                        data-confirm-button-color="#71dd37">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#rejectRtl{{ $item->id }}">
                                        <i class="bx bx-x"></i>
                                    </button>

                                    <div class="modal fade" id="rejectRtl{{ $item->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('rtl.reject', $item->id) }}" method="POST"
                                                class="modal-content">
                                                @csrf
                                                @method('PATCH')

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak RTL</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <label class="form-label">Catatan Penolakan</label>
                                                    <textarea name="catatan_verifikasi" rows="4" class="form-control" required></textarea>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak RTL</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Data RTL belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">@include('components._pagination', ['paginator' => $rtl])</div>
@endsection
