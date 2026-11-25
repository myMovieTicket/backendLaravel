<?php

namespace App\Http\Controllers;

use App\Models\BookingItem;
use App\Models\Seat;
use App\Models\SeatHold;
use App\Models\Showtime;
use Illuminate\Http\JsonResponse;

class SeatMapController extends Controller
{
    /**
     * Seat map for a showtime: each seat is available, held or sold.
     */
    public function show(Showtime $showtime): JsonResponse
    {
        $soldIds = BookingItem::query()
            ->where('showtime_id', $showtime->id)
            ->pluck('seat_id')
            ->all();

        $heldIds = SeatHold::query()
            ->where('showtime_id', $showtime->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->pluck('seat_id')
            ->all();

        $seats = Seat::query()
            ->where('auditorium_id', $showtime->auditorium_id)
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->get()
            ->map(fn (Seat $seat): array => [
                'id' => $seat->id,
                'row' => $seat->row_label,
                'number' => $seat->seat_number,
                'type' => $seat->seat_type,
                'price' => $showtime->base_price,
                'state' => in_array($seat->id, $soldIds, true)
                    ? 'sold'
                    : (in_array($seat->id, $heldIds, true) ? 'held' : 'available'),
            ]);

        return response()->json([
            'showtime_id' => $showtime->id,
            'seats' => $seats,
        ]);
    }
}
