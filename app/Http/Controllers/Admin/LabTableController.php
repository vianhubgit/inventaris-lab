<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabGroup;
use App\Models\LabTable;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabTableController extends Controller
{
    public function store(Request $request, LabGroup $group): RedirectResponse
    {
        $data = $request->validate([
            'nomor' => [
                'required', 'integer', 'min:1', 'max:100',
                Rule::unique('lab_tables', 'nomor')->where('lab_group_id', $group->id),
            ],
            'nama' => ['nullable', 'string', 'max:100'],
        ]);

        $table = $group->tables()->create($data);
        ActivityLogger::created($table, "Menambah {$table->display_name} pada {$group->display_name}.");

        return back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function update(Request $request, LabGroup $group, LabTable $table): RedirectResponse
    {
        abort_unless($table->lab_group_id === $group->id, 404);

        $data = $request->validate([
            'nomor' => [
                'required', 'integer', 'min:1', 'max:100',
                Rule::unique('lab_tables', 'nomor')->where('lab_group_id', $group->id)->ignore($table->id),
            ],
            'nama' => ['nullable', 'string', 'max:100'],
        ]);

        $table->update($data);
        ActivityLogger::updated($table, "Mengubah meja pada {$group->display_name}.");

        return back()->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(LabGroup $group, LabTable $table): RedirectResponse
    {
        abort_unless($table->lab_group_id === $group->id, 404);

        $table->delete();
        ActivityLogger::log('deleted', "Menghapus meja pada {$group->display_name}.");

        return back()->with('success', 'Meja berhasil dihapus.');
    }
}
