<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create();

        $event->reservations()->create([
            'user_id' => $user->id,
        ]);

        $data = [
            'rating' => 4,
            'comment' => 'Great event, I loved it!',
        ];

        $response = $this->postJson("/api/events/{$event->id}/reviews", $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'rating' => 4,
            'comment' => 'Great event, I loved it!',
        ]);
    }

    public function test_user_cannot_create_review_for_event_they_did_not_attend()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create();

        $data = [
            'rating' => 4,
            'comment' => 'Great event!',
        ];

        $response = $this->postJson("/api/events/{$event->id}/reviews", $data);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'You did not attend this event',
        ]);
    }

    public function test_user_cannot_create_multiple_reviews_for_same_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create();

        $event->reservations()->create([
            'user_id' => $user->id,
        ]);

        $event->reviews()->create([
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Great event!',
        ]);

        $data = [
            'rating' => 5,
            'comment' => 'Even better this time!',
        ];

        $response = $this->postJson("/api/events/{$event->id}/reviews", $data);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'You already reviewed this event',
        ]);
    }

    public function test_user_can_view_reviews_for_event()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $event = Event::factory()->create();

        $event->reviews()->createMany([
            ['user_id' => $user->id, 'rating' => 4, 'comment' => 'Good event.'],
            ['user_id' => $anotherUser->id, 'rating' => 5, 'comment' => 'Excellent event!'],
        ]);

        $response = $this->getJson("/api/events/{$event->id}/reviews");

        $response->assertStatus(200);
        $response->assertJsonFragment(['rating' => 4]);
        $response->assertJsonFragment(['rating' => 5]);
        $response->assertJsonFragment(['average_rating' => 4.5]);
    }
}
