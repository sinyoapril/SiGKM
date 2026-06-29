<?php

namespace App\Http\Controllers;

use App\Models\RencanaTindakLanjut;
use App\Models\Temuan;
use App\Services\WorkflowNotificationService;
use App\Support\WorkflowStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RencanaTindakLanjutController extends Controller
{
    public function __construct(private WorkflowNotificationService $notifications) {}

    public function index(): View
    {
        $user = auth()->user();

        $rtl = RencanaTindakLanjut::with([
            'temuan.evaluasiIndikator.semester.tahunAkademik',
            'temuan.evaluasiIndikator.evaluatable',
            'temuan.dosen',
            'buktiTindakLanjuts.pengunggah',
            'verifikator',
        ])
            ->when($user->hasAnyRole(['ketua-gkm', 'anggota-gkm']), function ($query) {
                $query->whereIn('status', [
                    WorkflowStatus::DIAJUKAN,
                    WorkflowStatus::DIVERIFIKASI,
                    WorkflowStatus::DITOLAK,
                ]);
            })
            ->when($user->hasRole('dosen'), function ($query) use ($user) {
                $query->whereHas('temuan', fn ($q) => $q->where('dosen_id', $user->dosen_id));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('monev.rtl.index', compact('rtl'));
    }

    public function create(): View
    {
        if (! auth()->user()->hasRole('dosen')) {
            abort(403, 'Hanya Dosen penanggung jawab yang dapat membuat RTL.');
        }

        return view('monev.rtl.create', $this->formData());
    }

    public function show(RencanaTindakLanjut $rtl): View
    {
        $rtl->load([
            'temuan.evaluasiIndikator.semester.tahunAkademik',
            'temuan.evaluasiIndikator.evaluatable',
            'temuan.dosen',
            'buktiTindakLanjuts.pengunggah',
            'verifikator',
            'keputusanRtms.notulenRtm.jadwalRtm',
        ]);

        $user = auth()->user();
        $visible = ($user->hasAnyRole(['ketua-gkm', 'anggota-gkm', 'koordinator-prodi']) && $rtl->status !== WorkflowStatus::DRAFT)
            || ($user->hasRole('dosen') && $rtl->temuan->dosen_id === $user->dosen_id);
        abort_unless($visible, 403);

        return view('monev.rtl.show', compact('rtl'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->hasRole('dosen')) {
            abort(403, 'Hanya Dosen penanggung jawab yang dapat membuat RTL.');
        }

        $validated = $this->validatedData($request);
        $this->ensureEvidenceWhenSubmitting($request);

        $validated['status'] = $request->input('aksi') === 'ajukan'
            ? WorkflowStatus::DIAJUKAN
            : WorkflowStatus::DRAFT;
        $validated['submitted_at'] = $validated['status'] === WorkflowStatus::DIAJUKAN ? now() : null;

        $rtl = DB::transaction(function () use ($request, $validated) {
            $rtl = RencanaTindakLanjut::create($validated);
            $this->storeBuktiFiles($request, $rtl);

            return $rtl;
        });

        if ($rtl->isDiajukan()) {
            $this->notifyRtlSubmitted($rtl);
        }

        return redirect()
            ->route('rtl.index')
            ->with('success', 'Rencana tindak lanjut berhasil disimpan.');
    }

    public function edit(RencanaTindakLanjut $rtl): View
    {
        if (! $rtl->canBeEditedBy(auth()->user())) {
            abort(403, 'RTL ini tidak dapat diedit.');
        }

        return view('monev.rtl.edit', array_merge(
            ['rtl' => $rtl],
            $this->formData($rtl)
        ));
    }

    public function update(Request $request, RencanaTindakLanjut $rtl): RedirectResponse
    {
        if (! $rtl->canBeEditedBy(auth()->user())) {
            abort(403, 'RTL ini tidak dapat diedit.');
        }

        $validated = $this->validatedData($request, $rtl);
        $this->ensureEvidenceWhenSubmitting($request, $rtl);

        $validated['status'] = $request->input('aksi') === 'ajukan'
            ? WorkflowStatus::DIAJUKAN
            : WorkflowStatus::DRAFT;
        $validated['submitted_at'] = $validated['status'] === WorkflowStatus::DIAJUKAN ? now() : null;
        $validated['verified_by'] = null;
        $validated['verified_at'] = null;
        $validated['catatan_verifikasi'] = null;

        DB::transaction(function () use ($request, $rtl, $validated) {
            $rtl->update($validated);
            $this->storeBuktiFiles($request, $rtl);
        });

        if ($rtl->isDiajukan()) {
            $this->notifyRtlSubmitted($rtl);
        }

        return redirect()
            ->route('rtl.index')
            ->with('success', 'Rencana tindak lanjut berhasil diperbarui.');
    }

    public function destroy(RencanaTindakLanjut $rtl): RedirectResponse
    {
        if (! $rtl->canBeEditedBy(auth()->user())) {
            abort(403, 'RTL ini tidak dapat dihapus.');
        }

        if ($rtl->buktiTindakLanjuts()->exists()) {
            foreach ($rtl->buktiTindakLanjuts as $bukti) {
                Storage::disk('public')->delete($bukti->file_path);
            }
        }

        $rtl->delete();

        return redirect()
            ->route('rtl.index')
            ->with('success', 'Rencana tindak lanjut berhasil dihapus.');
    }

    public function submit(RencanaTindakLanjut $rtl): RedirectResponse
    {
        if (! $rtl->canBeEditedBy(auth()->user())) {
            abort(403, 'RTL ini tidak dapat diajukan.');
        }

        if (! $rtl->hasEvidence()) {
            throw ValidationException::withMessages([
                'bukti' => 'Bukti tindak lanjut wajib diunggah sebelum RTL diajukan.',
            ]);
        }

        $rtl->update([
            'status' => WorkflowStatus::DIAJUKAN,
            'submitted_at' => now(),
            'verified_by' => null,
            'verified_at' => null,
            'catatan_verifikasi' => null,
        ]);

        $this->notifyRtlSubmitted($rtl);

        return back()->with('success', 'RTL berhasil diajukan ke Ketua GKM.');
    }

    public function verify(RencanaTindakLanjut $rtl): RedirectResponse
    {
        if (! auth()->user()->hasRole('ketua-gkm')) {
            abort(403, 'Hanya Ketua GKM yang dapat memverifikasi RTL.');
        }

        if (! $rtl->isDiajukan()) {
            return back()->with('error', 'Hanya RTL berstatus diajukan yang dapat diverifikasi.');
        }

        DB::transaction(function () use ($rtl) {
            $rtl->update([
                'status' => WorkflowStatus::DIVERIFIKASI,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'catatan_verifikasi' => null,
            ]);

            $rtl->temuan()->update([
                'status' => WorkflowStatus::DITUTUP,
            ]);
        });

        $this->notifications->sendToUser(
            $rtl->temuan?->dosen?->akunDosen,
            'RTL Diverifikasi',
            'Rencana tindak lanjut Anda telah diverifikasi oleh Ketua GKM.',
            route('rtl.show', $rtl),
            'bx-check-circle',
            'success',
        );

        return back()->with('success', 'RTL berhasil diverifikasi.');
    }

    public function reject(Request $request, RencanaTindakLanjut $rtl): RedirectResponse
    {
        if (! auth()->user()->hasRole('ketua-gkm')) {
            abort(403, 'Hanya Ketua GKM yang dapat menolak RTL.');
        }

        if (! $rtl->isDiajukan()) {
            return back()->with('error', 'Hanya RTL berstatus diajukan yang dapat ditolak.');
        }

        $validated = $request->validate([
            'catatan_verifikasi' => ['required', 'string'],
        ], [
            'catatan_verifikasi.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $rtl->update([
            'status' => WorkflowStatus::DITOLAK,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
        ]);

        $this->notifications->sendToUser(
            $rtl->temuan?->dosen?->akunDosen,
            'RTL Ditolak',
            'Rencana tindak lanjut perlu diperbaiki: '.$validated['catatan_verifikasi'],
            route('rtl.show', $rtl),
            'bx-error-circle',
            'danger',
        );

        return back()->with('success', 'RTL berhasil ditolak.');
    }

    private function notifyRtlSubmitted(RencanaTindakLanjut $rtl): void
    {
        $this->notifications->sendToRole(
            'ketua-gkm',
            'RTL Menunggu Verifikasi',
            'RTL untuk temuan '.($rtl->temuan?->kode_temuan ?? '-').' telah diajukan oleh Dosen.',
            route('verifikasi.index'),
            'bx-task',
            'warning',
        );
    }

    private function formData(?RencanaTindakLanjut $rtl = null): array
    {
        $temuan = Temuan::with([
            'evaluasiIndikator.semester.tahunAkademik',
            'evaluasiIndikator.evaluatable',
        ])
            ->where('status', WorkflowStatus::TERBUKA)
            ->where('dosen_id', auth()->user()->dosen_id)
            ->when($rtl, function ($query) use ($rtl) {
                $query->where(function ($q) use ($rtl) {
                    $q->whereDoesntHave('rencanaTindakLanjuts')
                        ->orWhereKey($rtl->temuan_id);
                });
            }, function ($query) {
                $query->whereDoesntHave('rencanaTindakLanjuts');
            })
            ->latest()
            ->get();

        return compact('temuan');
    }

    private function validatedData(Request $request, ?RencanaTindakLanjut $rtl = null): array
    {
        return $request->validate([
            'temuan_id' => [
                'required',
                Rule::exists('temuans', 'id')
                    ->where('dosen_id', auth()->user()->dosen_id)
                    ->where('status', WorkflowStatus::TERBUKA),
                Rule::unique('rencana_tindak_lanjuts', 'temuan_id')->ignore($rtl),
            ],
            'uraian_rencana_tindak_lanjut' => ['required', 'string'],
            'uraian_tindak_koreksi' => ['nullable', 'string'],
            'target_selesai' => ['nullable', 'date'],
            'bukti.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:2048'],
            'keterangan_bukti.*' => ['nullable', 'string'],
        ], [
            'temuan_id.required' => 'Temuan wajib dipilih.',
            'temuan_id.exists' => 'Temuan tidak valid atau tidak ditugaskan kepada akun dosen Anda.',
            'temuan_id.unique' => 'Temuan ini sudah memiliki RTL.',
            'uraian_rencana_tindak_lanjut.required' => 'Uraian rencana tindak lanjut wajib diisi.',
            'bukti.*.mimes' => 'File bukti harus berupa PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'bukti.*.max' => 'Ukuran file bukti maksimal 2 MB.',
        ]);
    }

    private function storeBuktiFiles(Request $request, RencanaTindakLanjut $rtl): void
    {
        if (! $request->hasFile('bukti')) {
            return;
        }

        foreach ($request->file('bukti') as $index => $file) {
            if (! $file) {
                continue;
            }

            $rtl->buktiTindakLanjuts()->create([
                'file_path' => $file->store('bukti-tindak-lanjut', 'public'),
                'keterangan' => $request->input("keterangan_bukti.{$index}"),
                'uploaded_by' => auth()->id(),
            ]);
        }
    }

    private function ensureEvidenceWhenSubmitting(Request $request, ?RencanaTindakLanjut $rtl = null): void
    {
        if ($request->input('aksi') !== 'ajukan') {
            return;
        }

        if ($request->hasFile('bukti') || $rtl?->hasEvidence()) {
            return;
        }

        throw ValidationException::withMessages([
            'bukti' => 'Bukti tindak lanjut wajib diunggah sebelum RTL diajukan.',
        ]);
    }
}
