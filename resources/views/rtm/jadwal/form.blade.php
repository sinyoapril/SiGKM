@php($current = $jadwalRtm ?? null)
<div class="mb-3">
    <label class="form-label">Semester Pelaksanaan</label>
    <select name="semester_id" class="form-select @error('semester_id') is-invalid @enderror" required>
        <option value="">-- Pilih Semester --</option>
        @foreach($semester as $item)
            <option value="{{ $item->id }}" @selected(old('semester_id', $current?->semester_id) == $item->id)>
                {{ $item->label }} {{ $item->is_active ? '(Aktif)' : '' }}
            </option>
        @endforeach
    </select>
    @error('semester_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small class="text-muted">RTM akan meninjau RTL terverifikasi dari satu semester sebelum semester ini.</small>
</div>
<div class="mb-3">
    <label class="form-label">Judul RTM</label>
    <input name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $current?->judul) }}" required>
    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Tanggal</label><input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $current?->tanggal?->format('Y-m-d')) }}" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">Waktu Mulai</label><input type="time" name="waktu_mulai" class="form-control" value="{{ old('waktu_mulai', $current?->waktu_mulai) }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Waktu Selesai</label><input type="time" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai', $current?->waktu_selesai) }}"></div>
</div>
<div class="mb-3"><label class="form-label">Lokasi</label><input name="lokasi" class="form-control" value="{{ old('lokasi', $current?->lokasi) }}"></div>
<div class="mb-3"><label class="form-label">Agenda</label><textarea name="agenda" rows="4" class="form-control">{{ old('agenda', $current?->agenda) }}</textarea></div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        @foreach(['draft' => 'Draft', 'terjadwal' => 'Terjadwal', 'selesai' => 'Selesai'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $current?->status ?? 'draft') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
