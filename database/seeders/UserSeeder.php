<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensuring roles exist
        $this->call(RolePermissionSeeder::class);

        // Admin 1
        $admin1 = User::firstOrCreate(
            ['email' => 'admin1@hotel.com'],
            [
                'name' => 'Msafiri Juma',
                'password' => Hash::make('password'),
            ]
        );
        $admin1->assignRole('admin');

        // Admin 2
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@hotel.com'],
            [
                'name' => 'Noel Faraja',
                'password' => Hash::make('password'),
            ]
        );
        $admin2->assignRole('admin');

        // Receptionist 1
        $reception1 = User::firstOrCreate(
            ['email' => 'reception1@hotel.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
            ]
        );
        $reception1->assignRole('receptionist');

        // Receptionist 2
        $reception2= User::firstOrCreate(
            ['email' => 'reception2@hotel.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
            ]
        );
        $reception2->assignRole('receptionist');

        // Receptionist 3
        $reception3 = User::firstOrCreate(
            ['email' => 'reception3@hotel.com'],
            [
                'name' => 'Sandra Brown',
                'password' => Hash::make('password'),
            ]
        );
        $reception3->assignRole('receptionist');

        // Housekeeper 1
        $housekeeper1 = User::firstOrCreate(
            ['email' => 'housekeeper1@hotel.com'],
            [
                'name' => 'Mahmoud Mazruy',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper1->assignRole('housekeeper');

        // Housekeeper 2
        $housekeeper2 = User::firstOrCreate(
            ['email' => 'housekeeper2@hotel.com'],
            [
                'name' => 'Aisha Mahfoudh',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper2->assignRole('housekeeper');

        // Housekeeper 3
        $housekeeper3 = User::firstOrCreate(
            ['email' => 'housekeeper3@hotel.com'],
            [
                'name' => 'Mary Johnson',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper3->assignRole('housekeeper');

        // Housekeeper 4
        $housekeeper4 = User::firstOrCreate(
            ['email' => 'housekeeper4@hotel.com'],
            [
                'name' => 'Hamissa Zuberi',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper4->assignRole('housekeeper');

        // Housekeeper 5
        $housekeeper5 = User::firstOrCreate(
            ['email' => 'housekeeper5@hotel.com'],
            [
                'name' => 'Omary Hafidh',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper5->assignRole('housekeeper');

        // Housekeeper 6
        $housekeeper6 = User::firstOrCreate(
            ['email' => 'housekeeper6@hotel.com'],
            [
                'name' => 'Awena Uwesu',
                'password' => Hash::make('password'),
            ]
        );
        $housekeeper6->assignRole('housekeeper');

        // Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@hotel.com'],
            [
                'name' => 'Manager Alex',
                'password' => Hash::make('password'),
            ]
        );
        $manager->assignRole('manager');

        $this->command->info('✅ Test Users created successfully!');
        $this->command->info('Login Credentials:');
        $this->command->info('admin@hotel.com / password');
        $this->command->info('reception@hotel.com / password');
        $this->command->info('housekeeper@hotel.com / password');
    }
}