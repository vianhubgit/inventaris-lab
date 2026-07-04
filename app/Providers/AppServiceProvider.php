<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\Procurement;
use App\Models\Report;
use App\Observers\ItemObserver;
use App\Observers\ProcurementObserver;
use App\Observers\ReportObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Observer untuk activity log.
        Item::observe(ItemObserver::class);
        Report::observe(ReportObserver::class);
        Procurement::observe(ProcurementObserver::class);

        // Pagination berbasis Tailwind.
        Paginator::useTailwind();

        // Default string length untuk MariaDB versi lama (aman).
        Schema::defaultStringLength(191);
    }
}
