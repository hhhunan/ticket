<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StatisticsRequest;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StatisticsController extends ApiController
{
    public function __construct(
        private readonly StatisticsService $statisticsService
    ) {}
    #[OA\Get(
        path: '/api/tickets/statistics',
        operationId: 'getStatistics',
        description: 'Returns ticket statistics by period: day, week, month or extended',
        summary: 'Get ticket statistics',
        tags: ['Tickets'],
        parameters: [
            new OA\Parameter(
                name: 'period',
                description: 'Statistics period',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    default: 'day',
                    enum: ['day', 'week', 'month']
                ),
                example: 'day'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistics retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),

                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'period', type: 'string', example: 'day'),
                                new OA\Property(property: 'total', type: 'integer', example: 13),
                                new OA\Property(property: 'new', type: 'integer', example: 5),
                                new OA\Property(property: 'in_progress', type: 'integer', example: 6),
                                new OA\Property(property: 'processed', type: 'integer', example: 2),
                                new OA\Property(
                                    property: 'start_date',
                                    type: 'string',
                                    format: 'date-time',
                                    example: '2026-01-23 00:00:00'
                                ),
                                new OA\Property(
                                    property: 'end_date',
                                    type: 'string',
                                    format: 'date-time',
                                    example: '2026-01-23 14:26:49'
                                ),
                            ],
                            type: 'object'
                        ),

                        new OA\Property(property: 'cached', type: 'boolean', example: true),
                    ]
                )
            ),

            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),

            new OA\Response(
                response: 500,
                description: 'Internal server error'
            )
        ]
    )]
    public function __invoke(StatisticsRequest $request): JsonResponse
    {
        $period = $request->get('period', 'day');

        $stats = match($period) {
            'extended' => $this->statisticsService->getExtendedStatistics(),
            default => $this->statisticsService->getStatistics($period),
        };

        return response()->json([
            'success' => true,
            'data' => $stats,
            'cached' => true,
        ]);
    }
}
