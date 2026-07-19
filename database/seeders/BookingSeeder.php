<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;

class BookingSeeder extends Seeder
{
    public function run()
    {
        // Check if Guest and Room data are available
        $this->call(GuestSeeder::class);
        $this->call(RoomSeeder::class);

        $guests = Guest::all();
        $rooms = Room::where('status', 'available')->get();

        if ($guests->isEmpty() || $rooms->isEmpty()) {
            $this->command->warn('No guests or available rooms found. Seeding skipped.');
            return;
        }

        $bookings = [
            [
                'guest_id' => $guests->random()->id,
                'room_id' => $rooms->random()->id,
                'booking_code' => 'BK' . strtoupper(substr(md5(time() . $rooms->random()->id), 0, 6)), // Generate a unique booking code
                'check_in_date' => now()->addDays(1)->format('Y-m-d'),
                'check_out_date' => now()->addDays(4)->format('Y-m-d'),
                'number_of_guests' => 2,
                'total_amount' => 900000,
                'status' => 'confirmed',
                'special_requests' => 'Extra bed requested',
            ],
            [
                'guest_id' => $guests->random()->id,
                'room_id' => $rooms->random()->id,
                'booking_code' => 'BK109101',
                'check_in_date' => now()->addDays(2)->format('Y-m-d'),
                'check_out_date' => now()->addDays(5)->format('Y-m-d'),
                'number_of_guests' => 3,
                'total_amount' => 1440000,
                'status' => 'pending',
                'special_requests' => 'Late check-in',
            ],
            [
                'guest_id' => $guests->random()->id,
                'room_id' => $rooms->random()->id,
                'booking_code' => 'BK904586', 
                'check_in_date' => now()->format('Y-m-d'),
                'check_out_date' => now()->addDays(3)->format('Y-m-d'),
                'number_of_guests' => 1,
                'total_amount' => 560000,
                'status' => 'checked_in',
                'special_requests' => null,
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::firstOrCreate(
                ['room_id' => $booking['room_id'], 'check_in_date' => $booking['check_in_date']],
                $booking
            );
        }

        $this->command->info('✅ Sample Bookings seeded successfully!');
    }
}