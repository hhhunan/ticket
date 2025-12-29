<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $service
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'email', 'phone']);
        $tickets = $this->service->getTickets($filters);
        $stats = [
            'total' => Ticket::count(),
            'new' => Ticket::where('status', 'new')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'processed' => Ticket::where('status', 'processed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'media']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->service->updateStatus($ticket, $request->status);

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Статус заявки успешно обновлен');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Заявка успешно удалена');
    }
}
