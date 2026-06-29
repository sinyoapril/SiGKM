<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahunAkademikController extends Controller
{
    public function index(): View
    {
        $tahunAkademik = TahunAkademik::query()
            ->orderByDesc('nama')
            ->paginate(10)
            ->withQueryString();

        return view('master.tahun-akademik.index', compact('tahunAkademik'));
    }

    public function create(): View
    {
        return view('master.tahun-akademik.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:tahun_akademiks,nama'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            TahunAkademik::query()->update(['is_active' => false]);
        }

        TahunAkademik::create($validated);

        return redirect()
            ->route('tahun-akademik.index')
            ->with('success', 'Tahun akademik berhasil ditambahkan.');
    }

    public function show(TahunAkademik $tahunAkademik): View
    {
        $tahunAkademik->load('semesters');

        return view('master.tahun-akademik.show', compact('tahunAkademik'));
    }

    public function edit(TahunAkademik $tahunAkademik): View
    {
        return view('master.tahun-akademik.edit', compact('tahunAkademik'));
    }

    public function update(Request $request, TahunAkademik $tahunAkademik): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:tahun_akademiks,nama,'.$tahunAkademik->id],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            TahunAkademik::query()
                ->whereKeyNot($tahunAkademik->id)
                ->update(['is_active' => false]);
        }

        $tahunAkademik->update($validated);

        return redirect()
            ->route('tahun-akademik.index')
            ->with('success', 'Tahun akademik berhasil diperbarui.');
    }

    public function destroy(TahunAkademik $tahunAkademik): RedirectResponse
    {
        if ($tahunAkademik->semesters()->exists()) {
            return back()->with('error', 'Tahun akademik tidak dapat dihapus karena sudah memiliki semester.');
        }

        $tahunAkademik->delete();

        return redirect()
            ->route('tahun-akademik.index')
            ->with('success', 'Tahun akademik berhasil dihapus.');
    }

    public function setActive(TahunAkademik $tahunAkademik): RedirectResponse
    {
        TahunAkademik::query()->update(['is_active' => false]);

        $tahunAkademik->update(['is_active' => true]);

        return back()->with('success', 'Tahun akademik aktif berhasil diperbarui.');
    }
}
