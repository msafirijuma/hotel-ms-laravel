<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // Check if Room Types are available
        $this->call(RoomTypeSeeder::class);

        $roomTypes = RoomType::all();

        $rooms = [
            // Standard Rooms
            ['room_number' => '101', 'room_type_id' => $roomTypes->where('name', 'Standard Room')->first()->id, 'status' => 'available', 'floor' => '1'],
            ['room_number' => '102', 'room_type_id' => $roomTypes->where('name', 'Standard Room')->first()->id, 'status' => 'available', 'floor' => '1'],
            ['room_number' => '103', 'room_type_id' => $roomTypes->where('name', 'Standard Room')->first()->id, 'status' => 'occupied', 'floor' => '1'],

            // Deluxe Rooms
            ['room_number' => '201', 'room_type_id' => $roomTypes->where('name', 'Deluxe Room')->first()->id, 'status' => 'available', 'floor' => '2'],
            ['room_number' => '202', 'room_type_id' => $roomTypes->where('name', 'Deluxe Room')->first()->id, 'status' => 'available', 'floor' => '2'],
            ['room_number' => '203', 'room_type_id' => $roomTypes->where('name', 'Deluxe Room')->first()->id, 'status' => 'maintenance', 'floor' => '2'],

            // Executive Suites
            ['room_number' => '301', 'room_type_id' => $roomTypes->where('name', 'Executive Suite')->first()->id, 'status' => 'available', 'floor' => '3'],
            ['room_number' => '302', 'room_type_id' => $roomTypes->where('name', 'Executive Suite')->first()->id, 'status' => 'occupied', 'floor' => '3'],

            // Family Rooms
            ['room_number' => '401', 'room_type_id' => $roomTypes->where('name', 'Family Room')->first()->id, 'status' => 'available', 'floor' => '4'],
            ['room_number' => '402', 'room_type_id' => $roomTypes->where('name', 'Family Room')->first()->id, 'status' => 'available', 'floor' => '4'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(
                ['room_number' => $room['room_number']],
                $room
            );
        }

        $this->command->info('✅ Sample Rooms seeded successfully!');
    }
}