<?php

namespace App\Http\Controllers;

use App\Models\KeputusanRtm;
use App\Models\NotulenRtm;
use App\Models\RencanaTindakLanjut;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KeputusanRtmController extends Controller
{
    public function index(): View
    {
        $keputusanRtm = KeputusanRtm::with([
            'notulenRtm.jadwalRtm.semester.tahunAkademik',
            'rencanaTindakLanjut.temuan.evaluasiIndikator.semester.tahunAkademik',
            'rencanaTindakLanjut.temuan.dosen',
        ])->latest()->paginate(10)->withQueryString();

        return view('rtm.keputusan.index', compact('keputusanRtm'));
    }

    public function create(): View
    {
        return view('rtm.keputusan.create', $this->formData());
    }

    public function show(KeputusanRtm $keputusanRtm): View
    {
        $keputusanRtm->load([
            'notulenRtm.jadwalRtm.semester.tahunAkademik',
            'rencanaTindakLanjut.temuan.evaluasiIndikator.semester.tahunAkademik',
            'rencanaTindakLanjut.temuan.evaluasiIndikator.evaluatable',
            'rencanaTindakLanjut.temuan.dosen',
            'rencanaTindakLanjut.buktiTindakLanjuts',
        ]);

        return view('rtm.keputusan.show', compact('keputusanRtm'));
    }

    public function store(Request $request): RedirectResponse
    {
        KeputusanRtm::create($this->validated($request));

        return redirect()->route('keputusan-rtm.index')->with('success', 'Keputusan RTM berhasil dibuat.');
    }

    public function edit(KeputusanRtm $keputusanRtm): View
    {
        return view('rtm.keputusan.edit', array_merge(
            ['keputusanRtm' => $keputusanRtm],
            $this->formData($keputusanRtm)
        ));
    }

    public function update(Request $request, KeputusanRtm $keputusanRtm): RedirectResponse
    {
        $keputusanRtm->update($this->validated($request, $keputusanRtm));

        return redirect()->route('keputusan-rtm.index')->with('success', 'Keputusan RTM berhasil diperbarui.');
    }

    public function destroy(KeputusanRtm $keputusanRtm): RedirectResponse
    {
        $keputusanRtm->delete();

        return back()->with('success', 'Keputusan RTM berhasil dihapus.');
    }

    private function validated(Request $request, ?KeputusanRtm $keputusanRtm = null): array
    {
        $data = $request->validate([
            'notulen_rtm_id' => ['required', Rule::exists('notulen_rtms', 'id')->where('status', 'diverifikasi')],
            'rencana_tindak_lanjut_id' => ['required', 'exists:rencana_tindak_lanjuts,id'],
            'uraian_keputusan' => ['required', 'string'],
            'strategi' => ['nullable', 'string'],
            'target_selesai' => ['nullable', 'date'],
            'status' => ['required', 'in:belum_dikerjakan,proses,selesai'],
        ]);

        $eligible = $this->eligibleRtlQuery((int) $data['notulen_rtm_id'], $keputusanRtm)
            ->whereKey($data['rencana_tindak_lanjut_id'])
            ->exists();

        if (! $eligible) {
            throw ValidationException::withMessages([
                'rencana_tindak_lanjut_id' => 'RTL harus sudah diverifikasi, berasal dari semester sebelumnya, dan belum diputuskan pada RTM ini.',
            ]);
        }

        return $data;
    }

    private function formData(?KeputusanRtm $keputusanRtm = null): array
    {
        $notulenRtm = NotulenRtm::with('jadwalRtm.semester.tahunAkademik')
            ->where('status', 'diverifikasi')
            ->latest('verified_at')
            ->get();

        $rtlByNotulen = $notulenRtm->mapWithKeys(fn ($notulen) => [
            $notulen->id => $this->eligibleRtlQuery($notulen->id, $keputusanRtm)
                ->with(['temuan.evaluasiIndikator.semester.tahunAkademik', 'temuan.dosen'])
                ->get()
                ->map(fn ($rtl) => [
                    'id' => $rtl->id,
                    'label' => ($rtl->temuan?->kode_temuan ?? 'Temuan').' - '.str($rtl->uraian_rencana_tindak_lanjut)->limit(90),
                ])->values(),
        ]);

        return compact('notulenRtm', 'rtlByNotulen');
    }

    private function eligibleRtlQuery(int $notulenId, ?KeputusanRtm $current = null)
    {
        $notulen = NotulenRtm::with('jadwalRtm.semester')->find($notulenId);
        $semester = $notulen?->jadwalRtm?->semester;
        $previous = $semester
            ? Semester::where('tanggal_mulai', '<', $semester->tanggal_mulai)->latest('tanggal_mulai')->first()
            : null;

        return RencanaTindakLanjut::query()
            ->where('status', 'diverifikasi')
            ->when($previous, fn ($query) => $query->whereHas(
                'temuan.evaluasiIndikator', fn ($q) => $q->where('semester_id', $previous->id)
            ), fn ($query) => $query->whereRaw('1 = 0'))
            ->where(function ($query) use ($notulenId, $current) {
                $query->whereDoesntHave('keputusanRtms', fn ($q) => $q->where('notulen_rtm_id', $notulenId));
                if ($current && (int) $current->notulen_rtm_id === $notulenId) {
                    $query->orWhereKey($current->rencana_tindak_lanjut_id);
                }
            });
    }
}
