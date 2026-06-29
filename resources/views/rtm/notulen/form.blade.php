@php($current = $notulenRtm ?? null)
<div class="mb-3">
    <label class="form-label">Jadwal RTM</label>
    <select name="jadwal_rtm_id" class="form-select @error('jadwal_rtm_id') is-invalid @enderror" required>
        <option value="">-- Pilih Jadwal --</option>
        @foreach($jadwalRtm as $item)
            <option value="{{ $item->id }}" @selected(old('jadwal_rtm_id', $current?->jadwal_rtm_id) == $item->id)>{{ $item->judul }} — {{ $item->semester?->label }}</option>
        @endforeach
    </select>
    @error('jadwal_rtm_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3"><label class="form-label">Isi Notulen</label><textarea name="isi_notulen" rows="12" class="form-control @error('isi_notulen') is-invalid @enderror" required>{{ old('isi_notulen', $current?->isi_notulen) }}</textarea>@error('isi_notulen')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
