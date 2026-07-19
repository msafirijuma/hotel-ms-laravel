<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        $roomTypes = [
            [
                'name' => 'Standard Room',
                'description' => 'Standar room with 1 or 2 beds, Tv, WiFi and Bath.',
                'price_per_night' => 240000,
                'max_occupancy' => 2,
                'image' => null,
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Room with master bedroom, sofa, mini frideg and balcony.',
                'price_per_night' => 360000,
                'max_occupancy' => 3,
                'image' => null,
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Suite with 2 dinning, 2 beds and beautiful oceanic view.',
                'price_per_night' => 700000,
                'max_occupancy' => 4,
                'image' => null,
            ],
            [
                'name' => 'Family Room',
                'description' => 'Large room for family members with 2 to 3 beds.',
                'price_per_night' => 440000,
                'max_occupancy' => 5,
                'image' => null,
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'Luxury suite with two master bedrooms, jacuzzi na special accomodation services.',
                'price_per_night' => 1600000,
                'max_occupancy' => 6,
                'image' => null,
            ],
            [
                'name' => 'Single Room',
                'description' => 'Small room for single person with basic accomodation',
                'price_per_night' => 160000,
                'max_occupancy' => 1,
                'image' => null,
            ],
        ];

        foreach ($roomTypes as $type) {
            RoomType::firstOrCreate(['name' => $type['name']], $type);
        }

        $this->command->info('✅ Room Types seeded successfully!');
    }
}