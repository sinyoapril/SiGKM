<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function index(): View
    {
        $semester = Semester::with('tahunAkademik')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.semester.index', compact('semester'));
    }

    public function create(): View
    {
        $tahunAkademik = TahunAkademik::orderByDesc('nama')
            ->get();

        return view('master.semester.create', compact('tahunAkademik'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_akademik_id' => ['required', 'exists:tahun_akademiks,id'],
            'nama' => [
                'required',
                'in:ganjil,genap',
                'unique:semesters,nama,NULL,id,tahun_akademik_id,'.$request->tahun_akademik_id,
            ],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'tahun_akademik_id.required' => 'Tahun akademik wajib dipilih.',
            'tahun_akademik_id.exists' => 'Tahun akademik tidak valid.',
            'nama.required' => 'Semester wajib dipilih.',
            'nama.in' => 'Semester tidak valid.',
            'nama.unique' => 'Semester ini sudah tersedia pada tahun akademik yang dipilih.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            Semester::query()->update(['is_active' => false]);
        }

        Semester::create($validated);

        return redirect()
            ->route('semester.index')
            ->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester): View
    {
        $tahunAkademik = TahunAkademik::orderByDesc('nama')
            ->get();

        return view('master.semester.edit', compact('semester', 'tahunAkademik'));
    }

    public function update(Request $request, Semester $semester): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_akademik_id' => ['required', 'exists:tahun_akademiks,id'],
            'nama' => [
                'required',
                'in:ganjil,genap',
                'unique:semesters,nama,'.$semester->id.',id,tahun_akademik_id,'.$request->tahun_akademik_id,
            ],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'tahun_akademik_id.required' => 'Tahun akademik wajib dipilih.',
            'tahun_akademik_id.exists' => 'Tahun akademik tidak valid.',
            'nama.required' => 'Semester wajib dipilih.',
            'nama.in' => 'Semester tidak valid.',
            'nama.unique' => 'Semester ini sudah tersedia pada tahun akademik yang dipilih.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            Semester::query()
                ->whereKeyNot($semester->id)
                ->update(['is_active' => false]);
        }

        $semester->update($validated);

        return redirect()
            ->route('semester.index')
            ->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        if ($semester->perkuliahans()->exists()) {
            return back()->with('error', 'Semester tidak dapat dihapus karena sudah digunakan pada data perkuliahan.');
        }

        if ($semester->jadwalMonevs()->exists()) {
            return back()->with('error', 'Semester tidak dapat dihapus karena sudah digunakan pada jadwal monev.');
        }

        $semester->delete();

        return redirect()
            ->route('semester.index')
            ->with('success', 'Semester berhasil dihapus.');
    }

    public function setActive(Semester $semester): RedirectResponse
    {
        Semester::query()->update(['is_active' => false]);

        $semester->update(['is_active' => true]);

        return back()->with('success', 'Semester aktif berhasil diperbarui.');
    }
}
