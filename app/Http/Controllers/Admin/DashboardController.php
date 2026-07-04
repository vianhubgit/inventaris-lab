<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard)
    {
    }

    public function index(): View
    {
        return view('admin.dashboard.index', [
            'stats' => $this->dashboard->adminStats(),
            'itemsPerCategory' => $this->dashboard->itemsPerCategory(),
            'itemsPerLab' => $this->dashboard->itemsPerLab(),
            'reportTrend' => $this->dashboard->reportTrend(),
            'mostRusak' => $this->dashboard->mostReported(ReportType::RUSAK),
            'mostHilang' => $this->dashboard->mostReported(ReportType::HILANG),
            'latestProcurements' => $this->dashboard->latestProcurements(),
            'latestActivities' => $this->dashboard->latestActivities(),
        ]);
    }
}
