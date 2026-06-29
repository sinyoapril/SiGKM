<?php

namespace App\Http\Controllers;

use App\Models\Termin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TerminController extends Controller
{
    public function index(): View
    {
        $termin = Termin::query()
            ->orderBy('nama_termin')
            ->paginate(10)
            ->withQueryString();

        return view('master.termin.index', compact('termin'));
    }

    public function create(): View
    {
        return view('master.termin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_termin' => ['required', 'string', 'max:255', 'unique:termins,nama_termin'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'nama_termin.required' => 'Nama termin wajib diisi.',
            'nama_termin.unique' => 'Nama termin sudah digunakan.',
        ]);

        Termin::create($validated);

        return redirect()
            ->route('termin.index')
            ->with('success', 'Termin berhasil ditambahkan.');
    }

    public function edit(Termin $termin): View
    {
        return view('master.termin.edit', compact('termin'));
    }

    public function update(Request $request, Termin $termin): RedirectResponse
    {
        $validated = $request->validate([
            'nama_termin' => ['required', 'string', 'max:255', 'unique:termins,nama_termin,'.$termin->id],
            'keterangan' => ['nullable', 'string'],
        ], [
            'nama_termin.required' => 'Nama termin wajib diisi.',
            'nama_termin.unique' => 'Nama termin sudah digunakan.',
        ]);

        $termin->update($validated);

        return redirect()
            ->route('termin.index')
            ->with('success', 'Termin berhasil diperbarui.');
    }

    public function destroy(Termin $termin): RedirectResponse
    {
        if ($termin->jadwalMonevs()->exists()) {
            return back()->with('error', 'Termin tidak dapat dihapus karena sudah digunakan pada jadwal monev.');
        }

        $termin->delete();

        return redirect()
            ->route('termin.index')
            ->with('success', 'Termin berhasil dihapus.');
    }
}
