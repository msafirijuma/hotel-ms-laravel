<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        // Tafuta mtumiaji wa kwanza aliyepo kwenye mfumo
        $user = User::first();
        $userId = $user ? $user->id : null;

        DB::table('audit_logs')->insert([
            [
                'user_id'     => $userId,
                'activity'    => 'Login',
                'description' => 'Has logged in successfully.',
                'ip_address'  => '192.168.1.50',
                'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0',
                'created_at'  => now()->subMinutes(45),
                'updated_at'  => now()->subMinutes(45),
            ],
            [
                'user_id'     => $userId,
                'activity'    => 'Create Booking',
                'description' => 'Has created a new booking with code BK7E3A1A for guest Juma Hamisi',
                'ip_address'  => '192.168.1.50',
                'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0',
                'created_at'  => now()->subMinutes(30),
                'updated_at'  => now()->subMinutes(30),
            ],
            [
                'user_id'     => $userId,
                'activity'    => 'Update Settings',
                'description' => "Has updated hotel's settings and logo.",
                'ip_address'  => '192.168.1.55',
                'user_agent'  => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'created_at'  => now()->subMinutes(15),
                'updated_at'  => now()->subMinutes(15),
            ],
            [
                'user_id'     => $userId,
                'activity'    => 'Check-Out',
                'description' => 'Has checked out Asha Omary on Room No. 104. Room is now Dirty.',
                'ip_address'  => '192.168.1.50',
                'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0',
                'created_at'  => now()->subMinutes(5),
                'updated_at'  => now()->subMinutes(5),
            ],
            [
                'user_id'     => $userId,
                'activity'    => 'Delete Booking',
                'description' => 'Has deleted booking for postponed guest.',
                'ip_address'  => '192.168.1.50',
                'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
