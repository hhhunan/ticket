<?php

namespace App\Contracts\Repository;

use App\DTO\TicketData;
use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    public function create(TicketData $data): Ticket;
    public function find(int $id): ?Ticket;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function filter(array $filters): LengthAwarePaginator;
    public function getStatistics(string $period): array;
    public function updateStatus(Ticket $ticket, string $status): bool;
}
