@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 py-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kinerja Program Studi {{ str(config('sigkm.program_studi'))->title() }}</h4>
            <span class="text-muted">Kelola Sasaran Strategis, IKU, IKK, dan IKKS</span>
        </div>
        <div class="d-flex gap-2" id="add-buttons">
            <a href="{{ route('kinerja-program-studi.create', 'sasaran') }}" class="btn btn-primary tab-add-btn"
                data-tab="sasaran">
                <i class="bx bx-plus"></i> Sasaran Strategis
            </a>
            <a href="{{ route('kinerja-program-studi.create', 'iku') }}" class="btn btn-primary tab-add-btn d-none"
                data-tab="iku">
                <i class="bx bx-plus"></i> IKU
            </a>
            <a href="{{ route('kinerja-program-studi.create', 'ikk') }}" class="btn btn-primary tab-add-btn d-none"
                data-tab="ikk">
                <i class="bx bx-plus"></i> IKK
            </a>
            <a href="{{ route('kinerja-program-studi.create', 'ikks') }}" class="btn btn-primary tab-add-btn d-none"
                data-tab="ikks">
                <i class="bx bx-plus"></i> IKKS
            </a>
        </div>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $color)
        @if (session($key))
            <div class="alert alert-{{ $color }} alert-dismissible">
                {{ session($key) }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <ul class="nav nav-pills mb-3" id="kinerja-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-sasaran" data-bs-toggle="tab" data-bs-target="#pane-sasaran"
                type="button" role="tab" data-tab="sasaran">
                Sasaran Strategis
                <span class="badge bg-label-primary ms-1">{{ $sasaranStrategis->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-iku" data-bs-toggle="tab" data-bs-target="#pane-iku" type="button"
                role="tab" data-tab="iku">
                IKU
                <span class="badge bg-label-warning ms-1">{{ $ikuList->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-ikk" data-bs-toggle="tab" data-bs-target="#pane-ikk" type="button"
                role="tab" data-tab="ikk">
                IKK
                <span class="badge bg-label-info ms-1">{{ $ikkList->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-ikks" data-bs-toggle="tab" data-bs-target="#pane-ikks" type="button"
                role="tab" data-tab="ikks">
                IKKS
                <span class="badge bg-label-success ms-1">{{ $ikksList->total() }}</span>
            </button>
        </li>
    </ul>

    <div class="card">
        <div class="tab-content" id="kinerja-tab-content">

            {{-- ===== TAB SASARAN STRATEGIS ===== --}}
            <div class="tab-pane fade show active" id="pane-sasaran" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px">Kode</th>
                                <th>Uraian Sasaran Strategis</th>
                                <th style="width:100px" class="text-center">Status</th>
                                <th style="width:120px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sasaranStrategis as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-label-primary">
                                            {{ $item->kode_sasaran ?: 'Sasaran' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->uraian_sasaran }}</td>
                                    <td class="text-center">
                                        @if ($item->is_active)
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('kinerja-program-studi.edit', ['jenis' => 'sasaran', 'id' => $item]) }}"
                                                class="btn btn-sm btn-icon btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <x-delete-form :action="route('kinerja-program-studi.destroy', [
                                                'jenis' => 'sasaran',
                                                'id' => $item,
                                            ])" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Belum ada sasaran strategis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($sasaranStrategis->hasPages())
                    <div class="p-3 border-top">
                        @include('components._pagination', [
                            'paginator' => $sasaranStrategis,
                            'appends' => ['tab' => 'sasaran'],
                        ])
                    </div>
                @endif
            </div>

            {{-- ===== TAB IKU ===== --}}
            <div class="tab-pane fade" id="pane-iku" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px">Kode</th>
                                <th>Uraian IKU</th>
                                <th style="width:180px">Sasaran Strategis</th>
                                <th style="width:100px" class="text-center">Status</th>
                                <th style="width:120px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ikuList as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-label-warning">
                                            {{ $item->kode_iku ?: 'IKU' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->uraian_iku }}</td>
                                    <td>
                                        <span class="badge bg-label-primary me-1">
                                            {{ $item->sasaranStrategis->kode_sasaran ?: 'SS' }}
                                        </span>
                                        <small
                                            class="text-muted">{{ Str::limit($item->sasaranStrategis->uraian_sasaran, 40) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_active)
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('kinerja-program-studi.edit', ['jenis' => 'iku', 'id' => $item]) }}"
                                                class="btn btn-sm btn-icon btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <x-delete-form :action="route('kinerja-program-studi.destroy', [
                                                'jenis' => 'iku',
                                                'id' => $item,
                                            ])" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada IKU.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($ikuList->hasPages())
                    <div class="p-3 border-top">
                        @include('components._pagination', [
                            'paginator' => $ikuList,
                            'appends' => ['tab' => 'iku'],
                        ])
                    </div>
                @endif
            </div>

            {{-- ===== TAB IKK ===== --}}
            <div class="tab-pane fade" id="pane-ikk" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px">Kode</th>
                                <th>Uraian IKK</th>
                                <th style="width:200px">IKU</th>
                                <th style="width:100px" class="text-center">Status</th>
                                <th style="width:120px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ikkList as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-label-info">
                                            {{ $item->kode_ikk ?: 'IKK' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->uraian_ikk }}</td>
                                    <td>
                                        <span class="badge bg-label-warning me-1">
                                            {{ $item->indikatorKinerjaUtama->kode_iku ?: 'IKU' }}
                                        </span>
                                        <small
                                            class="text-muted">{{ Str::limit($item->indikatorKinerjaUtama->uraian_iku, 35) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_active)
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('kinerja-program-studi.edit', ['jenis' => 'ikk', 'id' => $item]) }}"
                                                class="btn btn-sm btn-icon btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <x-delete-form :action="route('kinerja-program-studi.destroy', [
                                                'jenis' => 'ikk',
                                                'id' => $item,
                                            ])" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada IKK.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($ikkList->hasPages())
                    <div class="p-3 border-top">
                        @include('components._pagination', [
                            'paginator' => $ikkList,
                            'appends' => ['tab' => 'ikk'],
                        ])
                    </div>
                @endif
            </div>

            {{-- ===== TAB IKKS ===== --}}
            <div class="tab-pane fade" id="pane-ikks" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px">Kode</th>
                                <th>Uraian IKKS</th>
                                <th style="width:200px">IKK</th>
                                <th style="width:100px" class="text-center">Status</th>
                                <th style="width:120px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ikksList as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-label-success">
                                            {{ $item->kode_ikks ?: 'IKKS' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->uraian_ikks }}</td>
                                    <td>
                                        <span class="badge bg-label-info me-1">
                                            {{ $item->indikatorKinerjaKegiatan->kode_ikk ?: 'IKK' }}
                                        </span>
                                        <small
                                            class="text-muted">{{ Str::limit($item->indikatorKinerjaKegiatan->uraian_ikk, 35) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_active)
                                            <span class="badge bg-label-success">Aktif</span>
                                        @else
                                            <span class="badge bg-label-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('kinerja-program-studi.edit', ['jenis' => 'ikks', 'id' => $item]) }}"
                                                class="btn btn-sm btn-icon btn-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <x-delete-form :action="route('kinerja-program-studi.destroy', [
                                                'jenis' => 'ikks',
                                                'id' => $item,
                                            ])" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada IKKS.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($ikksList->hasPages())
                    <div class="p-3 border-top">
                        @include('components._pagination', [
                            'paginator' => $ikksList,
                            'appends' => ['tab' => 'ikks'],
                        ])
                    </div>
                @endif
            </div>

        </div>{{-- end tab-content --}}
    </div>
@endsection

@push('scripts')
    <script>
        // Tombol "Tambah" ganti sesuai tab aktif
        const tabButtons = document.querySelectorAll('#kinerja-tabs button[data-tab]');
        const addButtons = document.querySelectorAll('.tab-add-btn');

        tabButtons.forEach(btn => {
            btn.addEventListener('shown.bs.tab', () => {
                const activeTab = btn.getAttribute('data-tab');
                addButtons.forEach(ab => {
                    ab.classList.toggle('d-none', ab.getAttribute('data-tab') !== activeTab);
                });
            });
        });

        // Buka tab sesuai query string ?tab=xxx (berguna setelah redirect dari store/update)
        const urlTab = new URLSearchParams(window.location.search).get('tab');
        if (urlTab) {
            const target = document.querySelector(`#tab-${urlTab}`);
            if (target) bootstrap.Tab.getOrCreateInstance(target).show();
        }
    </script>
@endpush
