<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'event_date' => now()->addDays(10),
            'location' => fake()->city,
            'price' => fake()->randomFloat(2, 0, 200),
            'attendee_limit' => 100,
            'reservation_deadline' => now()->addDays(5),
        ];
    }
}
