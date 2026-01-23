<?php
namespace App\Repositories;

use App\Contracts\Repository\TicketRepositoryInterface;
use App\DTO\TicketData;
use App\Models\Customer;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(TicketData $data): Ticket
    {
        $customer = Customer::firstOrCreate(
            ['phone' => $data->phone],
            [
                'name' => $data->name,
                'email' => $data->email,
            ]
        );

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'subject' => $data->subject,
            'message' => $data->message,
        ]);

        foreach ($data->attachments as $file) {
            $ticket->addMedia($file)->toMediaCollection('attachments');
        }
        return $ticket->load('customer');
    }

    public function find(int $id): ?Ticket
    {
        return Ticket::with(['customer', 'media'])->find($id);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Ticket::with('customer')
            ->latest()
            ->paginate($perPage);
    }

    public function filter(array $filters): LengthAwarePaginator
    {
        $query = Ticket::with('customer');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['email'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('email', 'like', "%{$filters['email']}%");
            });
        }

        if (!empty($filters['phone'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('phone', 'like', "%{$filters['phone']}%");
            });
        }

        return $query->latest()->paginate(15);
    }

    public function getStatistics(string $period): array
    {
        $now = Carbon::now();

        switch ($period) {
            case 'day':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'week':
                $startDate = $now->copy()->startOfWeek();
                break;
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                break;
            default:
                $startDate = $now->copy()->startOfDay();
        }

        $total = Ticket::where('created_at', '>=', $startDate)->count();
        $new = Ticket::where('created_at', '>=', $startDate)->new()->count();
        $inProgress = Ticket::where('created_at', '>=', $startDate)->inProgress()->count();
        $processed = Ticket::where('created_at', '>=', $startDate)->processed()->count();

        return [
            'period' => $period,
            'total' => $total,
            'new' => $new,
            'in_progress' => $inProgress,
            'processed' => $processed,
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $now->toDateTimeString(),
        ];
    }

    public function updateStatus(Ticket $ticket, string $status): bool
    {
        return $ticket->changeStatus($status);
    }
}
