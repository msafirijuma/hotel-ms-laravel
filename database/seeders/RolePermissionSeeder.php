<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view dashboard',
            'manage rooms',
            'manage bookings',
            'manage guests',
            'manage housekeeping',
            'manage payments',
            'manage reports',
            'manage users',
            'manage settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $receptionist = Role::create(['name' => 'receptionist']);
        $housekeeper = Role::create(['name' => 'housekeeper']);
        $manager = Role::create(['name' => 'manager']);

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::all());

        $receptionist->givePermissionTo([
            'view dashboard', 'manage rooms', 'manage bookings', 
            'manage guests', 'manage payments'
        ]);

        $housekeeper->givePermissionTo([
            'view dashboard', 'manage housekeeping'
        ]);

        $manager->givePermissionTo(Permission::all());
    }
}