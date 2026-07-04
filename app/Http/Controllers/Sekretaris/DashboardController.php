<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\Report;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard)
    {
    }

    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        return view('sekretaris.dashboard.index', [
            'stats' => $this->dashboard->sekretarisStats($userId),
            'recentReports' => Report::with(['item', 'lab'])
                ->where('user_id', $userId)
                ->latest('reported_at')
                ->take(5)
                ->get(),
            'recentProcurements' => Procurement::with(['item', 'category'])
                ->where('user_id', $userId)
                ->latest('requested_at')
                ->take(5)
                ->get(),
        ]);
    }
}
