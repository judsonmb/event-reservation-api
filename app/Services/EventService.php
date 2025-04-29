<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function getAvailable()
    {
        return Event::where('reservation_deadline', '>', now())
            ->withCount('reservations')
            ->withAvg('reviews', 'rating')
            ->get()
            ->filter(fn ($event) => $event->reservations_count < $event->attendee_limit)
            ->values();
    }
}
