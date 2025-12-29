<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::factory()->create();
        $status = fake()->randomElement(TicketStatus::cases());
        return [
            'id'=>Str::ulid(),
            'subject' => fake()->jobTitle(),
            'message' => fake()->sentence(10),
            'customer_id' => $customer->id,
            'status' => $status->value,
            'reply_date' => $status !== TicketStatus::NEW ? fake()->dateTime() : null,
        ];
    }
}
