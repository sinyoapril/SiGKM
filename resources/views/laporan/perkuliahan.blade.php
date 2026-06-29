@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 py-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Pelaksanaan Perkuliahan</h4>
            <span class="text-muted">Pilih data laporan, lalu unduh menggunakan format Excel resmi.</span>
        </div>
        <button type="submit" form="laporan-perkuliahan-filter" formaction="{{ route('laporan.perkuliahan.excel') }}"
            class="btn btn-success">
            <i class="bx bx-spreadsheet me-1"></i> Unduh Excel
        </button>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form id="laporan-perkuliahan-filter" method="GET" action="{{ route('laporan.perkuliahan') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select id="semester_id" name="semester_id" class="form-select" onchange="this.form.submit()">
                            @forelse ($semesters as $semester)
                                <option value="{{ $semester->id }}" @selected($selectedSemester?->id === $semester->id)>
                                    {{ $semester->label }}{{ $semester->is_active ? ' (Aktif)' : '' }}
                                </option>
                            @empty
                                <option value="">Belum ada semester</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <label for="jadwal_monev_id" class="form-label">Pelaksanaan Monev</label>
                        <select id="jadwal_monev_id" name="jadwal_monev_id" class="form-select">
                            @forelse ($jadwalMonevs as $jadwal)
                                <option value="{{ $jadwal->id }}" @selected($selectedJadwalMonev?->id === $jadwal->id)>
                                    {{ $jadwal->termin?->nama_termin ?? 'Tanpa termin' }}
                                    ({{ $jadwal->tanggal_mulai->translatedFormat('d M Y') }})
                                </option>
                            @empty
                                <option value="">Belum ada jadwal monev</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 d-grid">
                        <button type="submit" class="btn btn-primary" title="Terapkan filter">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-1">Pratinjau Data Excel</h5>
                <small class="text-muted">
                    {{ $selectedSemester?->label ?? 'Semester belum tersedia' }}
                    @if ($selectedJadwalMonev)
                        · {{ $selectedJadwalMonev->termin?->nama_termin ?? 'Tanpa termin' }}
                    @endif
                </small>
            </div>
            <span class="badge bg-label-primary">{{ $ringkasanPerkuliahan->count() }} data</span>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">Kelas</th>
                        <th>Dosen MK</th>
                        <th class="text-center">Pertemuan</th>
                        <th class="text-center">Kesesuaian</th>
                        <th>Keterangan (Temuan/Masalah)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ringkasanPerkuliahan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->perkuliahan?->mataKuliah?->nama_mk ?? '-' }}</strong>
                                <small
                                    class="d-block text-muted">{{ $item->perkuliahan?->mataKuliah?->kode_mk ?? '-' }}</small>
                            </td>
                            <td class="text-center">{{ $item->perkuliahan?->kelas?->nama_kelas ?? '-' }}</td>
                            <td>{{ $item->perkuliahan?->pengajars?->pluck('dosen.nama_dosen')->filter()->join(', ') ?: '-' }}
                            </td>
                            <td class="text-center">{{ $item->jumlah_pertemuan }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $item->kesesuaian_materi === 'sesuai' ? 'bg-label-success' : 'bg-label-warning' }}">
                                    {{ str($item->kesesuaian_materi)->replace('_', ' ')->title() }}
                                </span>
                            </td>
                            <td>{{ $item->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Belum ada ringkasan perkuliahan yang diverifikasi pada pelaksanaan monev ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
