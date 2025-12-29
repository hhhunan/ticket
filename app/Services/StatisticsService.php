<?php

namespace App\Services;

use App\Models\Ticket;
use App\Contracts\Repository\TicketRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class StatisticsService
{
    private const CACHE_PREFIX = 'stats:';
    private const CACHE_TTL = 300;

    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository
    ) {}

    /**
     * Получить статистику за период с кэшированием в Redis
     */
    public function getStatistics(string $period): array
    {
        $cacheKey = $this->getCacheKey($period);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($period) {
            return $this->ticketRepository->getStatistics($period);
        });
    }

    /**
     * Получить расширенную статистику с детализацией
     */
    public function getExtendedStatistics(): array
    {
        $cacheKey = $this->getCacheKey('extended');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return [
                'today' => $this->getStatistics('day'),
                'week' => $this->getStatistics('week'),
                'month' => $this->getStatistics('month'),
                'total' => $this->getTotalStatistics(),
                'daily' => $this->getDailyStatistics(7),
            ];
        });
    }

    /**
     * Общая статистика за все время
     */
    public function getTotalStatistics(): array
    {
        $cacheKey = $this->getCacheKey('total');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $total = Ticket::count();
            $new = Ticket::new()->count();
            $inProgress = Ticket::inProgress()->count();
            $processed = Ticket::processed()->count();

            return [
                'total' => $total,
                'new' => $new,
                'in_progress' => $inProgress,
                'processed' => $processed,
                'processed_percentage' => $total > 0 ? round(($processed / $total) * 100, 2) : 0,
            ];
        });
    }

    /**
     * Почасовая статистика за сегодня
     */
    public function getHourlyStatistics(): array
    {
        $cacheKey = $this->getCacheKey('hourly:' . now()->format('Y-m-d'));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            $hours = range(0, 23);
            $statistics = [];

            $startOfDay = now()->startOfDay();
            $endOfDay = now()->endOfDay();

            foreach ($hours as $hour) {
                $hourStart = $startOfDay->copy()->addHours($hour);
                $hourEnd = $hourStart->copy()->addHour();

                $count = Ticket::whereBetween('created_at', [$hourStart, $hourEnd])->count();

                $statistics[] = [
                    'hour' => $hour,
                    'label' => sprintf('%02d:00', $hour),
                    'count' => $count,
                ];
            }

            return $statistics;
        });
    }

    public function getDailyStatistics(int $days = 7): array
    {
        $cacheKey = $this->getCacheKey('daily:' . $days);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($days) {
            $statistics = [];
            $today = now()->startOfDay();

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                $nextDate = $date->copy()->addDay();

                $count = Ticket::whereBetween('created_at', [$date, $nextDate])->count();

                $statistics[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d.m.Y'),
                    'count' => $count,
                ];
            }

            return $statistics;
        });
    }

    public function invalidateStatisticsCache(): void
    {
        $pattern = self::CACHE_PREFIX . '*';

        $keys = Redis::keys($pattern);

        if (!empty($keys)) {
            Redis::del(...$keys);
        }
    }
    public function incrementCounter(string $type): void
    {
        $todayKey = 'counters:' . now()->format('Y-m-d') . ':' . $type;
        $totalKey = 'counters:total:' . $type;

        Redis::incr($todayKey);
        Redis::incr($totalKey);

        Redis::expire($todayKey, 172800);
    }

    public function getRealtimeCounters(): array
    {
        $today = now()->format('Y-m-d');

        return [
            'today' => [
                'new' => (int) Redis::get('counters:' . $today . ':new') ?? 0,
                'processed' => (int) Redis::get('counters:' . $today . ':processed') ?? 0,
            ],
            'total' => [
                'new' => (int) Redis::get('counters:total:new') ?? 0,
                'processed' => (int) Redis::get('counters:total:processed') ?? 0,
            ],
        ];
    }

    private function getCacheKey(string $suffix): string
    {
        return self::CACHE_PREFIX . $suffix;
    }
}
