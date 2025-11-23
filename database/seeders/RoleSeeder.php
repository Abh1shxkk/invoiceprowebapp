<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // You can add permissions here if needed in future
        // Example:
        // Permission::create(['name' => 'manage users']);
        // Permission::create(['name' => 'manage invoices']);
    }
}
