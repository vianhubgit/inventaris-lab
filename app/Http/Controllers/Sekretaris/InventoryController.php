<?php

namespace App\Http\Controllers\Sekretaris;

use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /** Daftar inventaris (read-only untuk sekretaris). */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Item::class);

        $items = Item::query()
            ->with(['category', 'lab', 'labTable.group'])
            ->search($request->q)
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('lab_id'), fn ($q) => $q->where('lab_id', $request->lab_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        return view('sekretaris.inventory.index', [
            'items' => $items,
            'categories' => Category::orderBy('nama')->get(),
            'labs' => Lab::orderBy('nama')->get(),
            'statuses' => ItemStatus::options(),
        ]);
    }

    public function show(Item $item): View
    {
        $this->authorize('view', $item);

        $item->load(['category', 'lab', 'labTable.group']);

        return view('sekretaris.inventory.show', compact('item'));
    }
}
