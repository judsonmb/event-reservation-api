<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reserve_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create([
            'attendee_limit' => 200,
            'reservation_deadline' => now()->addDays(5),
        ]);

        $response = $this->postJson("/api/events/{$event->id}/reserve");

        $response->assertStatus(201);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_user_cannot_reserve_event_when_attendee_limit_is_reached()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create([
            'attendee_limit' => 1,
            'reservation_deadline' => now()->addDays(5),
        ]);

        $event->reservations()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson("/api/events/{$event->id}/reserve");

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Attendee limit reached',
        ]);
    }

    public function test_user_cannot_reserve_event_after_reservation_deadline()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create([
            'attendee_limit' => 200,
            'reservation_deadline' => now()->subDays(1),
        ]);

        $response = $this->postJson("/api/events/{$event->id}/reserve");

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Reservation deadline has passed',
        ]);
    }

    public function test_user_cannot_reserve_event_multiple_times()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create([
            'attendee_limit' => 200,
            'reservation_deadline' => now()->addDays(5),
        ]);

        $event->reservations()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson("/api/events/{$event->id}/reserve");

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'You already reserved this event',
        ]);
    }
}
