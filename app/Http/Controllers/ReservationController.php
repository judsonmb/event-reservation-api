<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Services\ReservationService;

class ReservationController extends Controller
{
    public function __construct(private ReservationService $reservationService) {}

    public function store(Event $event)
    {
        try {
            $reservation = $this->reservationService->reserve($event);

            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
