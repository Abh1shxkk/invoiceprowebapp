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
        // Create roles only if they don't exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // You can add permissions here if needed in future
        // Example:
        // Permission::firstOrCreate(['name' => 'manage users']);
        // Permission::firstOrCreate(['name' => 'manage invoices']);
    }
}
