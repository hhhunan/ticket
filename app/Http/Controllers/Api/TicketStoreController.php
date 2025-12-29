<?php

namespace App\Http\Controllers\Api;

use App\DTO\TicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketStoreRequest;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketStoreController extends Controller
{
    public function __construct(
        private readonly TicketService $service
    ) {}

    public function __invoke(TicketStoreRequest $request): JsonResponse
    {
        try {
            $data = TicketData::fromArray($request->validated());
            $ticket = $this->service->createTicket($data);

            return response()->json([
                'success' => true,
                'message' => 'Заявка успешно создана',
                'data' => [
                    'ticket_id' => $ticket->id,
                    'status' => $ticket->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
