<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketStatisticsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_ticket_statistics(): void
    {
        $response = $this->getJson('/api/tickets/statistics?period=day');
        $response
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => ['period','total', 'new', 'in_progress', 'processed'],
            ]);
    }
}