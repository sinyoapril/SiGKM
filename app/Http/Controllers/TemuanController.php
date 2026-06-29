<?php

namespace App\Http\Controllers;

use App\Helpers\CodeGenerator;
use App\Models\Dosen;
use App\Models\EvaluasiIndikator;
use App\Models\Temuan;
use App\Models\TingkatRisiko;
use App\Support\WorkflowStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TemuanController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $temuanEvaluasi = Temuan::with([
            'evaluasiIndikator.semester.tahunAkademik',
            'evaluasiIndikator.evaluatable',
            'risikoTemuans.tingkatRisiko',
            'dosen',
            'pembuat',
        ])
            ->when($user->hasRole('anggota-gkm'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->when($user->hasRole('ketua-gkm'), function ($query) {
                $query->whereIn('status', [WorkflowStatus::TERBUKA, WorkflowStatus::DITUTUP]);
            })
            ->when($user->hasRole('dosen'), function ($query) use ($user) {
                $query->where('dosen_id', $user->dosen_id)
                    ->whereIn('status', [WorkflowStatus::TERBUKA, WorkflowStatus::DITUTUP]);
            })
            ->when($user->hasRole('koordinator-prodi'), function ($query) {
                $query->whereIn('status', [WorkflowStatus::TERBUKA, WorkflowStatus::DITUTUP]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('monev.temuan.index', compact('temuanEvaluasi'));
    }

    public function show(Temuan $temuan): View
    {
        $temuan->load([
            'evaluasiIndikator.semester.tahunAkademik',
            'evaluasiIndikator.evaluatable',
            'risikoTemuans.tingkatRisiko',
            'dosen',
            'pembuat',
            'rencanaTindakLanjuts.buktiTindakLanjuts.pengunggah',
            'rencanaTindakLanjuts.verifikator',
        ]);

        $user = auth()->user();
        $isPublished = $temuan->status !== WorkflowStatus::DRAFT;
        $visible = ($user->hasAnyRole(['ketua-gkm', 'koordinator-prodi']) && $isPublished)
            || ($user->hasRole('anggota-gkm') && $temuan->created_by === $user->id)
            || ($user->hasRole('dosen') && $isPublished && $temuan->dosen_id === $user->dosen_id);
        abort_unless($visible, 403);

        return view('monev.temuan.show', compact('temuan'));
    }

    public function create(): View
    {
        if (! auth()->user()->hasRole('anggota-gkm')) {
            abort(403, 'Hanya Anggota GKM yang dapat membuat temuan.');
        }

        return view('monev.temuan.create', $this->formData() + [
            'kodeTemuan' => CodeGenerator::kodeTemuan(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->hasRole('anggota-gkm')) {
            abort(403, 'Hanya Anggota GKM yang dapat membuat temuan.');
        }

        $validated = $this->validatedData($request);
        $risikoData = $this->validatedRisikoData($request);

        DB::transaction(function () use ($validated, $risikoData) {
            $validated['status'] = request('aksi') === 'terbuka'
                ? WorkflowStatus::TERBUKA
                : WorkflowStatus::DRAFT;
            $validated['created_by'] = auth()->id();

            $temuan = Temuan::create($validated);

            if ($risikoData['tingkat_risiko_id'] && $risikoData['deskripsi_risiko']) {
                $temuan->risikoTemuans()->create($risikoData);
            }
        });

        return redirect()
            ->route('temuan-evaluasi.index')
            ->with('success', 'Temuan evaluasi berhasil disimpan.');
    }

    public function edit(Temuan $temuan): View
    {
        if (! $temuan->canBeEditedBy(auth()->user())) {
            abort(403, 'Temuan ini tidak dapat diedit.');
        }

        $temuan->load('risikoTemuans.tingkatRisiko');

        return view('monev.temuan.edit', array_merge(
            ['temuanEvaluasi' => $temuan],
            $this->formData($temuan)
        ));
    }

    public function update(Request $request, Temuan $temuan): RedirectResponse
    {
        if (! $temuan->canBeEditedBy(auth()->user())) {
            abort(403, 'Temuan ini tidak dapat diedit.');
        }

        $validated = $this->validatedData($request, $temuan);
        $risikoData = $this->validatedRisikoData($request);

        DB::transaction(function () use ($temuan, $validated, $risikoData) {
            $validated['status'] = request('aksi') === 'terbuka'
                ? WorkflowStatus::TERBUKA
                : WorkflowStatus::DRAFT;

            $temuan->update($validated);

            $risiko = $temuan->risikoTemuans()->first();

            if ($risikoData['tingkat_risiko_id'] && $risikoData['deskripsi_risiko']) {
                $risiko
                    ? $risiko->update($risikoData)
                    : $temuan->risikoTemuans()->create($risikoData);

                return;
            }

            $risiko?->delete();
        });

        return redirect()
            ->route('temuan-evaluasi.index')
            ->with('success', 'Temuan evaluasi berhasil diperbarui.');
    }

    public function destroy(Temuan $temuan): RedirectResponse
    {
        if (! $temuan->canBeEditedBy(auth()->user())) {
            abort(403, 'Temuan ini tidak dapat dihapus.');
        }

        if ($temuan->rencanaTindakLanjuts()->exists()) {
            return back()->with('error', 'Temuan tidak dapat dihapus karena sudah digunakan pada RTL.');
        }

        $temuan->delete();

        return redirect()
            ->route('temuan-evaluasi.index')
            ->with('success', 'Temuan evaluasi berhasil dihapus.');
    }

    private function formData(?Temuan $temuan = null): array
    {
        $evaluasiIndikator = EvaluasiIndikator::with([
            'semester.tahunAkademik',
            'evaluatable',
        ])
            ->whereIn('status_capaian', ['hampir_tercapai', 'belum_tercapai'])
            ->when($temuan, function ($query) use ($temuan) {
                $query->orWhereKey($temuan->evaluasi_indikator_id);
            })
            ->latest()
            ->get();

        $tingkatRisiko = TingkatRisiko::orderBy('nama_tingkat')->get();
        $dosen = Dosen::orderBy('nama_dosen')->get();

        return compact('evaluasiIndikator', 'tingkatRisiko', 'dosen');
    }

    private function validatedData(Request $request, ?Temuan $temuan = null): array
    {
        return $request->validate([
            'evaluasi_indikator_id' => ['required', 'exists:evaluasi_indikators,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'kode_temuan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('temuans', 'kode_temuan')->ignore($temuan),
            ],
            'pernyataan' => ['required', 'string'],
            'rencana_awal' => ['nullable', 'string'],
            'target_selesai' => ['nullable', 'date'],
        ], [
            'evaluasi_indikator_id.required' => 'Evaluasi indikator wajib dipilih.',
            'dosen_id.required' => 'Dosen penanggung jawab wajib dipilih.',
            'kode_temuan.required' => 'Kode temuan wajib diisi.',
            'kode_temuan.unique' => 'Kode temuan sudah digunakan.',
            'pernyataan.required' => 'Pernyataan temuan wajib diisi.',
        ]);
    }

    private function validatedRisikoData(Request $request): array
    {
        return $request->validate([
            'tingkat_risiko_id' => ['nullable', 'required_with:deskripsi_risiko', 'exists:tingkat_risikos,id'],
            'deskripsi_risiko' => ['nullable', 'required_with:tingkat_risiko_id', 'string'],
            'dampak_risiko' => ['nullable', 'string'],
        ], [
            'tingkat_risiko_id.required_with' => 'Tingkat risiko wajib dipilih jika deskripsi risiko diisi.',
            'deskripsi_risiko.required_with' => 'Deskripsi risiko wajib diisi jika tingkat risiko dipilih.',
        ]);
    }
}
