<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreReviewRequest;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function store(StoreReviewRequest $request, Event $event)
    {
        $review = $this->reviewService->create($event, $request->validated());

        return response()->json($review, 201);
    }

    public function index(Event $event)
    {
        $data = $this->reviewService->getReviewsWithAverage($event);

        return response()->json($data);
    }
}
