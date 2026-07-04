<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ItemsExport;
use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Report;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    /* ===================== BARANG / ITEMS ===================== */

    public function itemsExcel(Request $request): BinaryFileResponse
    {
        ActivityLogger::log('export', 'Export inventaris ke Excel.');

        return Excel::download(
            new ItemsExport($request->only(['category_id', 'lab_id', 'status', 'q'])),
            'inventaris-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function itemsPdf(Request $request): Response
    {
        $items = Item::query()
            ->with(['category', 'lab', 'labTable.group'])
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('lab_id'), fn ($q) => $q->where('lab_id', $request->lab_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->search($request->q))
            ->orderBy('nama')
            ->get();

        ActivityLogger::log('export', 'Export inventaris ke PDF.');

        $pdf = Pdf::loadView('pdf.items', [
            'items' => $items,
            'tanggal' => now()->translatedFormat('d F Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('inventaris-'.now()->format('Ymd-His').'.pdf');
    }

    /* ===================== LAPORAN / REPORTS ===================== */

    public function reportsExcel(Request $request): BinaryFileResponse
    {
        ActivityLogger::log('export', 'Export laporan ke Excel.');

        return Excel::download(
            new ReportsExport($request->only(['type', 'status', 'lab_id'])),
            'laporan-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function reportsPdf(Request $request): Response
    {
        $reports = Report::query()
            ->with(['user', 'lab', 'group', 'labTable', 'item'])
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('lab_id'), fn ($q) => $q->where('lab_id', $request->lab_id))
            ->latest('reported_at')
            ->get();

        ActivityLogger::log('export', 'Export laporan ke PDF.');

        $pdf = Pdf::loadView('pdf.reports', [
            'reports' => $reports,
            'tanggal' => now()->translatedFormat('d F Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-'.now()->format('Ymd-His').'.pdf');
    }
}
