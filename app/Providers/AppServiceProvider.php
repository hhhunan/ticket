<?php

namespace App\Providers;

use App\Contracts\Repository\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use App\Repositories\TicketRepository;
use App\Services\StatisticsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->singleton(StatisticsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ticket::observe(TicketObserver::class);
    }
}
