<?php

namespace Tests\Feature\API;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_can_be_created_with_attachments(): void
    {
        $attachments = [
            UploadedFile::fake()->image('photo.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
        ];

        $response = $this->post('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john2@example.com',
            'phone' => '+37499123400',
            'subject' => 'Order issue',
            'message' => 'I have a problem with my order',
            'attachments' => $attachments,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'ticket_id',
                    'status',
                ],
            ]);
        $ticket = Ticket::first();
        $media = $ticket->getMedia('attachments');
        $this->assertCount(2, $media);
        foreach ($media as $file) {
            Storage::disk('ticket')->assertExists($file->id . '/' . $file->file_name);
        }
    }

    public function test_ticket_can_be_created_without_attachments(): void
    {
        $response = $this->post('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john1@example.com',
            'phone' => '+37499123401',
            'subject' => 'Order issue',
            'message' => 'I have a problem with my order',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'ticket_id',
                    'status',
                ],
            ]);
    }

    public function test_ticket_can_be_check_rate_limit(): void
    {
        $this->post('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+37499123401',
            'subject' => 'test',
            'message' => 'issue message ...',
        ]);
        $response = $this->post('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+37499123456',
            'subject' => 'Order issue',
            'message' => 'I have a problem with my order',
        ]);

        $response->assertStatus(429);
    }
}
