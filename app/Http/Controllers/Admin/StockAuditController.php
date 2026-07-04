<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockAudit;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockAuditController extends Controller
{
    public function index(Request $request): View
    {
        $audits = StockAudit::query()
            ->with(['item', 'user'])
            ->when($request->filled('item_id'), fn ($q) => $q->where('item_id', $request->item_id))
            ->latest('tanggal')
            ->paginate(12)
            ->withQueryString();

        return view('admin.audits.index', [
            'audits' => $audits,
            'items' => Item::orderBy('nama')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.audits.create', [
            'items' => Item::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'jumlah_fisik' => ['required', 'integer', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'tanggal' => ['required', 'date'],
            'sinkronkan' => ['sometimes', 'boolean'],
        ]);

        $item = Item::findOrFail($data['item_id']);
        $tercatat = $item->jumlah_total;
        $fisik = (int) $data['jumlah_fisik'];

        $audit = StockAudit::create([
            'item_id' => $item->id,
            'user_id' => $request->user()->id,
            'jumlah_tercatat' => $tercatat,
            'jumlah_fisik' => $fisik,
            'selisih' => $fisik - $tercatat,
            'keterangan' => $data['keterangan'] ?? null,
            'tanggal' => $data['tanggal'],
        ]);

        ActivityLogger::created($audit, "Audit stok \"{$item->nama}\": tercatat {$tercatat}, fisik {$fisik}.");

        // Opsional: sinkronkan jumlah tercatat ke hasil fisik.
        if ($request->boolean('sinkronkan') && $fisik !== $tercatat) {
            $item->update(['jumlah_total' => $fisik]);
        }

        return redirect()->route('admin.audits.index')
            ->with('success', 'Audit inventaris berhasil dicatat.');
    }

    public function destroy(StockAudit $audit): RedirectResponse
    {
        $audit->delete();

        return redirect()->route('admin.audits.index')
            ->with('success', 'Data audit berhasil dihapus.');
    }
}
