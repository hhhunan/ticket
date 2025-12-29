<?php

namespace App\Services;

use App\DTO\TicketData;
use App\Exceptions\TicketRateLimitException;
use App\Models\Ticket;
use App\Contracts\Repository\TicketRepositoryInterface;

class TicketService
{
    public function __construct(
        private readonly TicketRepositoryInterface $repository
    ) {}

    /**
     * @throws TicketRateLimitException
     */
    public function createTicket(TicketData $data): Ticket
    {
        $this->validateRateLimit($data->phone, $data->email);

        return $this->repository->create($data);
    }

    public function getTicket(int $id): ?Ticket
    {
        return $this->repository->find($id);
    }

    public function getTickets(array $filters = [])
    {
        if (empty($filters)) {
            return $this->repository->paginate();
        }

        return $this->repository->filter($filters);
    }

    public function getStatistics(string $period): array
    {
        return $this->repository->getStatistics($period);
    }

    public function updateStatus(Ticket $ticket, string $status): bool
    {
        return $this->repository->updateStatus($ticket, $status);
    }

    /**
     * @throws TicketRateLimitException
     */
    private function validateRateLimit(string $phone, ?string $email): void
    {
        $query = Ticket::whereHas('customer', function ($q) use ($phone, $email) {
            $q->where('phone', $phone);
            if ($email) {
                $q->orWhere('email', $email);
            }
        })->where('created_at', '>=', now()->subDay());

        if ($query->exists()) {
            throw new TicketRateLimitException();
        }
    }
}
