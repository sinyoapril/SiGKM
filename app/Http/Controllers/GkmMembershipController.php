<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\GkmMembership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GkmMembershipController extends Controller
{
    public function index(): View
    {
        $gkmMembership = GkmMembership::with('dosen')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master.gkm-membership.index', compact('gkmMembership'));
    }

    public function create(): View
    {
        $dosen = Dosen::query()
            ->orderBy('nama_dosen')
            ->get();

        return view('master.gkm-membership.create', compact('dosen'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dosen_id' => ['required', 'exists:dosens,id'],
            'peran' => ['required', 'in:ketua,anggota'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'dosen_id.required' => 'Dosen wajib dipilih.',
            'peran.required' => 'Peran wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active'] && $validated['peran'] === 'ketua') {
            GkmMembership::query()
                ->where('peran', 'ketua')
                ->update(['is_active' => false]);
        }

        if ($validated['is_active']) {
            $sameActiveMembership = GkmMembership::query()
                ->where('dosen_id', $validated['dosen_id'])
                ->where('peran', $validated['peran'])
                ->where('is_active', true)
                ->exists();

            if ($sameActiveMembership) {
                return back()
                    ->withInput()
                    ->with('error', 'Dosen ini sudah aktif dengan peran tersebut.');
            }
        }

        GkmMembership::create($validated);

        return redirect()
            ->route('gkm-membership.index')
            ->with('success', 'Keanggotaan GKM berhasil ditambahkan.');
    }

    public function edit(GkmMembership $gkmMembership): View
    {
        $dosen = Dosen::query()
            ->orderBy('nama_dosen')
            ->get();

        return view('master.gkm-membership.edit', compact('gkmMembership', 'dosen'));
    }

    public function update(Request $request, GkmMembership $gkmMembership): RedirectResponse
    {
        $validated = $request->validate([
            'dosen_id' => ['required', 'exists:dosens,id'],
            'peran' => ['required', 'in:ketua,anggota'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active'] && $validated['peran'] === 'ketua') {
            GkmMembership::query()
                ->where('peran', 'ketua')
                ->whereKeyNot($gkmMembership->id)
                ->update(['is_active' => false]);
        }

        $gkmMembership->update($validated);

        return redirect()
            ->route('gkm-membership.index')
            ->with('success', 'Keanggotaan GKM berhasil diperbarui.');
    }

    public function destroy(GkmMembership $gkmMembership): RedirectResponse
    {
        $gkmMembership->delete();

        return redirect()
            ->route('gkm-membership.index')
            ->with('success', 'Keanggotaan GKM berhasil dihapus.');
    }
}
