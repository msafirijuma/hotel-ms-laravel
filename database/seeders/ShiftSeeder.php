<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shifts')->insert([
            [
                'name'       => 'Morning',
                'start_time' => '06:00:00', 
                'end_time'   => '14:00:00', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Afternoon',
                'start_time' => '14:00:00', 
                'end_time'   => '22:00:00', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Night',
                'start_time' => '22:00:00', 
                'end_time'   => '06:00:00', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
