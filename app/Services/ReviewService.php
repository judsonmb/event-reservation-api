<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    public function create(Event $event, array $data): Review
    {
        $userId = Auth::id();

        $attended = $event->reservations()->where('user_id', $userId)->exists();

        if (! $attended) {
            abort(400, 'You did not attend this event');
        }

        if (Review::where('event_id', $event->id)->where('user_id', $userId)->exists()) {
            abort(400, 'You already reviewed this event');
        }

        return Review::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function getReviewsWithAverage(Event $event)
    {
        $reviews = $event->reviews()->latest()->get();
        $average = $reviews->avg('rating');

        return [
            'reviews' => $reviews,
            'average_rating' => round($average, 2),
        ];
    }
}
