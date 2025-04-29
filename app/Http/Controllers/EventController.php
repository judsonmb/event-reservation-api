<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(private EventService $eventService) {}

    public function index()
    {
        $events = $this->eventService->getAvailable();

        return response()->json($events);
    }

    public function store(StoreEventRequest $request)
    {
        $event = $this->eventService->create($request->validated());

        return response()->json($event, 201);
    }
}
