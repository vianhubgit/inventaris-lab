<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->action))
            ->when($request->filled('q'), fn ($q) => $q->where('description', 'like', "%{$request->q}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $actions = ActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.activities.index', compact('logs', 'actions'));
    }
}
