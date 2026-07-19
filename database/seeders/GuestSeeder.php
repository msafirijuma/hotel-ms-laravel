<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;

class GuestSeeder extends Seeder
{
    public function run()
    {
        $guests = [
            [
                'full_name' => 'John Adams',
                'email' => 'johnadams@gmail.com',
                'phone' => '0712345678',
                'id_number' => '12345678',
                'address' => 'Dodoma, Tanzania',
                'country' => 'Tanzania',
            ],
            [
                'full_name' => 'Aisha Mohammed',
                'email' => 'aishamohd@gmail.com',
                'phone' => '0777123456',
                'id_number' => '87654321',
                'address' => 'Mwanza, Tanzania',
                'country' => 'Tanzania',
            ],
            [
                'full_name' => 'David Kimani',
                'email' => 'davidk@gmail.com',
                'phone' => '0721987654',
                'id_number' => '33445566',
                'address' => 'Arusha, Tanzania',
                'country' => 'Tanzania',
            ],
            [
                'full_name' => 'Fatuma Ali',
                'email' => 'fatumaali@yahoo.com',
                'phone' => '0745123789',
                'id_number' => '99887766',
                'address' => 'DSM, Tanzania',
                'country' => 'Tanzania',
            ],
        ];

        foreach ($guests as $guest) {
            Guest::firstOrCreate(['phone' => $guest['phone']], $guest);
        }

        $this->command->info('✅ Sample Guests seeded successfully!');
    }
}