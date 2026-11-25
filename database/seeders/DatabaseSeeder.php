<?php

namespace Database\Seeders;

use App\Models\Auditorium;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\SeatHold;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->seedMovies();
        $this->seedAuditoriumWithSeats();
        $this->seedShowtimes();
        $this->seedSeatStates();
    }

    private function seedUsers(): void
    {
        foreach ([
            ['name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Checker', 'email' => 'checker@example.com', 'role' => 'checker'],
            ['name' => 'Customer', 'email' => 'customer@example.com', 'role' => 'customer'],
        ] as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [...$account, 'password' => 'password123'],
            );
        }
    }

    private function seedMovies(): void
    {
        foreach ([
            ['Inception', 'Sci-Fi', 'PG-13', 120],
            ['The Dark Knight', 'Action', 'PG-13', 120],
            ['Interstellar', 'Sci-Fi', 'PG-13', 125],
            ['Spirited Away', 'Animation', 'PG', 110],
            ['Parasite', 'Thriller', 'R', 115],
        ] as [$title, $genre, $rating, $durationMinutes]) {
            Movie::firstOrCreate(
                ['title' => $title],
                [
                    'description' => 'Sample description for '.$title,
                    'genre' => $genre,
                    'rating' => $rating,
                    'duration_minutes' => $durationMinutes,
                    'status' => 'active',
                ],
            );
        }
    }

    private function seedAuditoriumWithSeats(): void
    {
        $auditorium = Auditorium::firstOrCreate(
            ['name' => 'Screen 1'],
            ['capacity' => 40],
        );

        foreach (range('A', 'E') as $row) {
            foreach (range(1, 8) as $number) {
                Seat::firstOrCreate([
                    'auditorium_id' => $auditorium->id,
                    'row_label' => $row,
                    'seat_number' => $number,
                ], ['seat_type' => 'standard']);
            }
        }
    }

    private function seedShowtimes(): void
    {
        $movies = Movie::query()->where('status', 'active')->orderBy('id')->get();
        $auditorium = Auditorium::where('name', 'Screen 1')->firstOrFail();

        foreach (range(1, 3) as $day) {
            foreach (['10:00', '14:00', '18:00', '22:00'] as $index => $time) {
                $movie = $movies[($day + $index) % $movies->count()];
                $startsAt = now()->addDays($day)->setTimeFromTimeString($time);

                Showtime::firstOrCreate([
                    'movie_id' => $movie->id,
                    'auditorium_id' => $auditorium->id,
                    'starts_at' => $startsAt,
                ], [
                    'ends_at' => $startsAt->copy()->addMinutes($movie->duration_minutes),
                    'base_price' => 5.00,
                    'status' => 'scheduled',
                ]);
            }
        }
    }

    private function seedSeatStates(): void
    {
        $showtime = Showtime::query()->orderBy('starts_at')->firstOrFail();
        $customer = User::where('email', 'customer@example.com')->firstOrFail();
        $seats = Seat::query()
            ->where('auditorium_id', $showtime->auditorium_id)
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->limit(2)
            ->get();

        SeatHold::updateOrCreate([
            'showtime_id' => $showtime->id,
            'seat_id' => $seats->get(0)?->id,
        ], [
            'user_id' => $customer->id,
            'status' => 'active',
            'expires_at' => now()->addMinutes(15),
        ]);

        $booking = Booking::firstOrCreate([
            'user_id' => $customer->id,
            'showtime_id' => $showtime->id,
            'status' => 'confirmed',
        ], ['total_amount' => $showtime->base_price]);

        BookingItem::firstOrCreate([
            'booking_id' => $booking->id,
            'seat_id' => $seats->get(1)?->id,
        ], [
            'showtime_id' => $showtime->id,
            'price' => $showtime->base_price,
        ]);
    }
}
