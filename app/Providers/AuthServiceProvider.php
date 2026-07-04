<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Procurement;
use App\Models\Report;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\ItemPolicy;
use App\Policies\ProcurementPolicy;
use App\Policies\ReportPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    private array $policies = [
        User::class => UserPolicy::class,
        Item::class => ItemPolicy::class,
        Category::class => CategoryPolicy::class,
        Report::class => ReportPolicy::class,
        Procurement::class => ProcurementPolicy::class,
    ];

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Admin selalu diizinkan (super-access) untuk semua ability.
        Gate::before(function (User $user, string $ability) {
            return $user->isAdmin() ? true : null;
        });
    }
}
