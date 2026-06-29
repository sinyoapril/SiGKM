<?php

namespace App\Http\Controllers;

use App\Models\JadwalMonev;
use App\Models\Semester;
use App\Models\Termin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JadwalMonevController extends Controller
{
    public function index(): View
    {
        $jadwalMonev = JadwalMonev::with([
            'semester.tahunAkademik',
            'termin',
            'pembuat',
        ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('monev.jadwal-monev.index', compact('jadwalMonev'));
    }

    public function create(): View
    {
        $semester = Semester::with('tahunAkademik')
            ->orderByDesc('is_active')
            ->latest()
            ->get();

        $termin = Termin::query()
            ->orderBy('nama_termin')
            ->get();

        return view('monev.jadwal-monev.create', compact('semester', 'termin'));
    }

    public function show(JadwalMonev $jadwalMonev): View
    {
        $user = auth()->user();

        $jadwalMonev->load([
            'semester.tahunAkademik',
            'termin',
            'pembuat',
            'ringkasanPerkuliahans' => function ($query) use ($user) {
                $query
                    ->when($user?->hasRole('dosen'), function ($q) use ($user) {
                        $q->whereHas('perkuliahan.pengajars', fn ($pengajar) => $pengajar->where('dosen_id', $user->dosen_id));
                    })
                    ->with([
                        'perkuliahan.mataKuliah',
                        'perkuliahan.kelas',
                    ]);
            },
            'ringkasanPerkuliahans.penginput',
        ]);

        return view('monev.jadwal-monev.show', compact('jadwalMonev'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'semester_id' => ['required', 'exists:semesters,id'],
            'termin_id' => ['required', 'exists:termins,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', 'in:draft,aktif,selesai'],
        ], [
            'semester_id.required' => 'Semester wajib dipilih.',
            'termin_id.required' => 'Termin wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $isDuplicate = JadwalMonev::query()
            ->where('semester_id', $validated['semester_id'])
            ->where('termin_id', $validated['termin_id'])
            ->exists();

        if ($isDuplicate) {
            throw ValidationException::withMessages([
                'termin_id' => 'Jadwal monev untuk semester dan termin tersebut sudah tersedia.',
            ]);
        }

        $validated['created_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            if ($validated['status'] === 'aktif') {
                JadwalMonev::query()
                    ->where('semester_id', $validated['semester_id'])
                    ->update(['status' => 'selesai']);
            }

            JadwalMonev::create($validated);
        });

        return redirect()
            ->route('jadwal-monev.index')
            ->with('success', 'Jadwal monev berhasil ditambahkan.');
    }

    public function edit(JadwalMonev $jadwalMonev): View
    {
        $semester = Semester::with('tahunAkademik')
            ->orderByDesc('is_active')
            ->latest()
            ->get();

        $termin = Termin::query()
            ->orderBy('nama_termin')
            ->get();

        return view('monev.jadwal-monev.edit', compact(
            'jadwalMonev',
            'semester',
            'termin'
        ));
    }

    public function update(Request $request, JadwalMonev $jadwalMonev): RedirectResponse
    {
        $validated = $request->validate([
            'semester_id' => ['required', 'exists:semesters,id'],
            'termin_id' => ['required', 'exists:termins,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', 'in:draft,aktif,selesai'],
        ], [
            'semester_id.required' => 'Semester wajib dipilih.',
            'termin_id.required' => 'Termin wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        $isDuplicate = JadwalMonev::query()
            ->where('semester_id', $validated['semester_id'])
            ->where('termin_id', $validated['termin_id'])
            ->whereKeyNot($jadwalMonev->id)
            ->exists();

        if ($isDuplicate) {
            throw ValidationException::withMessages([
                'termin_id' => 'Jadwal monev untuk semester dan termin tersebut sudah tersedia.',
            ]);
        }

        DB::transaction(function () use ($validated, $jadwalMonev) {
            if ($validated['status'] === 'aktif') {
                JadwalMonev::query()
                    ->where('semester_id', $validated['semester_id'])
                    ->whereKeyNot($jadwalMonev->id)
                    ->update(['status' => 'selesai']);
            }

            $jadwalMonev->update($validated);
        });

        return redirect()
            ->route('jadwal-monev.index')
            ->with('success', 'Jadwal monev berhasil diperbarui.');
    }

    public function destroy(JadwalMonev $jadwalMonev): RedirectResponse
    {
        if ($jadwalMonev->ringkasanPerkuliahans()->exists()) {
            return back()->with('error', 'Jadwal monev tidak dapat dihapus karena sudah digunakan pada ringkasan perkuliahan.');
        }

        $jadwalMonev->delete();

        return redirect()
            ->route('jadwal-monev.index')
            ->with('success', 'Jadwal monev berhasil dihapus.');
    }

    public function setActive(JadwalMonev $jadwalMonev): RedirectResponse
    {
        DB::transaction(function () use ($jadwalMonev) {
            JadwalMonev::query()
                ->where('semester_id', $jadwalMonev->semester_id)
                ->whereKeyNot($jadwalMonev->id)
                ->update(['status' => 'selesai']);

            $jadwalMonev->update([
                'status' => 'aktif',
            ]);
        });

        return back()->with('success', 'Jadwal monev berhasil diaktifkan.');
    }

    public function finish(JadwalMonev $jadwalMonev): RedirectResponse
    {
        $jadwalMonev->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Jadwal monev berhasil diselesaikan.');
    }
}
