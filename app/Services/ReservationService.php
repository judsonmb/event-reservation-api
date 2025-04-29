<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationService
{
    public function reserve(Event $event): Reservation
    {
        if ($event->reservations()->count() >= $event->attendee_limit) {
            abort(400, 'Attendee limit reached');
        }

        if (now()->greaterThan($event->reservation_deadline)) {
            abort(400, 'Reservation deadline has passed');
        }

        if (Reservation::where('event_id', $event->id)->where('user_id', Auth::id())->exists()) {
            abort(400, 'You already reserved this event');
        }

        return Reservation::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
        ]);
    }
}
