<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = Report::query()
            ->with(['user', 'lab', 'group', 'labTable', 'item'])
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('lab_id'), fn ($q) => $q->where('lab_id', $request->lab_id))
            ->latest('reported_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'labs' => Lab::orderBy('nama')->get(),
            'types' => ReportType::options(),
            'statuses' => ReportStatus::options(),
        ]);
    }

    public function show(Report $report): View
    {
        $report->load(['user', 'lab', 'group', 'labTable', 'item', 'repairs.user']);

        return view('admin.reports.show', [
            'report' => $report,
            'statuses' => ReportStatus::options(),
        ]);
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(ReportStatus::class)],
        ]);

        $report->update($data);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->forcedelete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
