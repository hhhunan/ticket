<?php

namespace App\Observers;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Services\StatisticsService;

readonly class TicketObserver
{
    public function __construct(
        private StatisticsService $statisticsService
    ) {}

    public function created(Ticket $ticket): void
    {
        $this->statisticsService->incrementCounter('new');
        $this->statisticsService->invalidateStatisticsCache();
    }

    public function updated(Ticket $ticket): void
    {
        if ($ticket->isDirty('status')) {
            $oldStatus = $ticket->getOriginal('status');
            $newStatus = $ticket->status;

            if ($newStatus === TicketStatus::PROCESSED->value && $oldStatus !== TicketStatus::IN_PROGRESS->value) {
                $this->statisticsService->incrementCounter('processed');
            }

            $this->statisticsService->invalidateStatisticsCache();
        }
    }

    public function deleted(Ticket $ticket): void
    {
        $this->statisticsService->invalidateStatisticsCache();
    }

    public function restored(Ticket $ticket): void
    {
        $this->statisticsService->invalidateStatisticsCache();
    }

    public function forceDeleted(Ticket $ticket): void
    {
        $this->statisticsService->invalidateStatisticsCache();
    }
}
