<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Policies\OrderPolicy;

class AppServiceProvider extends ServiceProvider {
    public function boot(): void {
        Gate::policy(Order::class, OrderPolicy::class);
    }
}