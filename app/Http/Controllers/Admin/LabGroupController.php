<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabGroup;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabGroupController extends Controller
{
    public function store(Request $request, Lab $lab): RedirectResponse
    {
        $data = $request->validate([
            'nomor' => [
                'required', 'integer', 'min:1', 'max:100',
                Rule::unique('lab_groups', 'nomor')->where('lab_id', $lab->id),
            ],
            'nama' => ['nullable', 'string', 'max:100'],
        ]);

        $group = $lab->groups()->create($data);
        ActivityLogger::created($group, "Menambah {$group->display_name} di {$lab->nama}.");

        return back()->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function update(Request $request, Lab $lab, LabGroup $group): RedirectResponse
    {
        abort_unless($group->lab_id === $lab->id, 404);

        $data = $request->validate([
            'nomor' => [
                'required', 'integer', 'min:1', 'max:100',
                Rule::unique('lab_groups', 'nomor')->where('lab_id', $lab->id)->ignore($group->id),
            ],
            'nama' => ['nullable', 'string', 'max:100'],
        ]);

        $group->update($data);
        ActivityLogger::updated($group, "Mengubah kelompok di {$lab->nama}.");

        return back()->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Lab $lab, LabGroup $group): RedirectResponse
    {
        abort_unless($group->lab_id === $lab->id, 404);

        $group->delete();
        ActivityLogger::log('deleted', "Menghapus kelompok di {$lab->nama}.");

        return back()->with('success', 'Kelompok berhasil dihapus.');
    }
}
