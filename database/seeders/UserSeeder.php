<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@ticket.com',
        ])->assignRole('admin');
        User::factory()->create([
            'name' => 'Manager Tickets',
            'email' => 'manager@ticket.com',
        ])->assignRole('manager');
    }
}
