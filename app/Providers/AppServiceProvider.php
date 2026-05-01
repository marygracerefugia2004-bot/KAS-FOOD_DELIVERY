<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Policies\OrderPolicy;

class AppServiceProvider extends ServiceProvider {
    public function boot(): void {
        Schema::defaultStringLength(191);
        Gate::policy(Order::class, OrderPolicy::class);
    }
}