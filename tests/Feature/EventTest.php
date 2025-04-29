<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_event()
    {
         $user = User::factory()->create();

         Sanctum::actingAs($user, ['*']);
 
         $data = [
             'title' => 'Laravel Conference',
             'description' => 'A great event for Laravel developers.',
             'event_date' => now()->addDays(10)->toDateTimeString(),
             'location' => 'São Paulo',
             'price' => 99.99,
             'attendee_limit' => 200,
             'reservation_deadline' => now()->addDays(5)->toDateTimeString(),
         ];
 
         $response = $this->postJson('/api/events', $data);
 
         $response->assertStatus(201);

         $this->assertDatabaseHas('events', [
             'title' => 'Laravel Conference',
             'user_id' => $user->id,
         ]);
    }

    public function test_user_can_list_available_events()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $availableEvent = Event::factory()->create([
            'title' => 'Available Event',
            'event_date' => now()->addDays(10),
            'reservation_deadline' => now()->addDays(5),
            'attendee_limit' => 200,
        ]);
        
        $reservedEvent = Event::factory()->create([
            'title' => 'Reserved Event',
            'event_date' => now()->addDays(10),
            'reservation_deadline' => now()->addDays(5),
            'attendee_limit' => 200,
        ]);
        
        $reservedEvent->reservations()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/events');
        $response->assertStatus(200);
        
        $response->assertJsonCount(2);
    }
}
