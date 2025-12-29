<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatisticsRequest;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly StatisticsService $statisticsService
    ) {}

    public function __invoke(StatisticsRequest $request): JsonResponse
    {
        $request->validate([
            'period' => 'nullable|in:day,week,month,extended',
        ]);

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
