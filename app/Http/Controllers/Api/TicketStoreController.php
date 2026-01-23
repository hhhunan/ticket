<?php

namespace App\Http\Controllers\Api;

use App\DTO\TicketData;
use App\Enums\TicketStatus;
use App\Exceptions\TicketRateLimitException;
use App\Http\Requests\TicketStoreRequest;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class TicketStoreController extends ApiController
{
    public function __construct(
        private readonly TicketService $service
    ) {}
    #[OA\Post(
        path: '/api/tickets',
        summary: 'Create ticket',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType('multipart/form-data',
                new OA\Schema(
                    properties:[
                        new OA\Property(property: 'name', type: 'string', example: "Иванов, Александр Викторович"),
                        new OA\Property(property: 'phone', type: 'string', format: 'E.164', example: "+79991234567"),
                        new OA\Property(property: 'email', type: 'string', maxLength: 255, example: "test@gmail.com"),
                        new OA\Property(property: 'subject', type: 'string', maxLength: 255, example: "API"),
                        new OA\Property(property: 'message', type: 'string', maxLength: 2000, example: "Сообщение ..."),
                        new OA\Property(property: 'attachments', type: 'array', items: new OA\Items(
                            type: "object",
                            format: "binary"
                        ), maxItems: 5),
                    ]
                )
            )
        ),
        tags: ['Tickets'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully created.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property:"success", type:"boolean", example:true),
                        new OA\Property(property:"message", type:"string", example:"Ticket created successfully"),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(
                                    property: 'ticket_id',
                                    type: 'integer',
                                    example: 14
                                ),
                                new OA\Property(
                                    property: 'status',
                                    type: 'string',
                                    example: 'new'
                                ),
                            ],
                            type: 'object'
                        ),
                    ],
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: false
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'The given data was invalid.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Rate limit exceeded',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: false
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Too many requests'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: false
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Something went wrong'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function __invoke(TicketStoreRequest $request): JsonResponse
    {
        try {
            $data = TicketData::fromArray($request->validated());

            $ticket = $this->service->createTicket($data);

            return response()->json([
                'success' => true,
                'message' => __('widget.message.created'),
                'data' => [
                    'ticket_id' => $ticket->id,
                    'status' => TicketStatus::NEW,
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
        catch (TicketRateLimitException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Something went wrong'),
            ], 500);
        }
    }
}
