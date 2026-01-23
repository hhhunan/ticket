<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
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
        $createdDate = Carbon::parse($this->faker->dateTimeBetween('-2 years', 'now'));
        $customer = Customer::factory()->create(
            [
                'created_at'=> $createdDate
            ]
        );
        $manager = User::role('manager')->first();
        $status = fake()->randomElement(TicketStatus::cases());
        return [
            'subject' => fake()->jobTitle(),
            'message' => fake()->sentence(10),
            'customer_id' => $customer->id,
            'manager_id' => $status !== TicketStatus::NEW ? $manager?->id: null,
            'status' => $status->value,
            'created_at' => $createdDate,
            'reply_at' => $status !== TicketStatus::NEW ? $createdDate->addMinute() : null,
        ];
    }
}
