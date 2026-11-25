<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\JsonResponse;

class ShowtimeController extends Controller
{
    public function index(): JsonResponse
    {
        $showtimes = Showtime::query()
            ->with('movie')
            ->where('status', 'scheduled')
            ->orderBy('starts_at')
            ->get()
            ->map(fn (Showtime $showtime): array => [
                'id' => $showtime->id,
                'movieId' => $showtime->movie_id,
                'movieTitle' => $showtime->movie?->title ?? 'Unknown film',
                'startsAt' => $showtime->starts_at->format('D, M j · g:i A'),
                'price' => $showtime->base_price,
                'url' => route('showtime.seat', $showtime),
            ]);

        return response()->json(['showtimes' => $showtimes->all()]);
    }
}
