<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@invoicepro.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $admin->assignRole('admin');

        // Create admin settings
        Setting::create([
            'user_id' => $admin->id,
            'company_name' => 'InvoicePro Admin',
            'address' => '123 Admin Street, Admin City, AC 12345',
            'tax_rate' => 18.00,
            'invoice_prefix' => 'ADM',
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@invoicepro.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Assign user role
        $user->assignRole('user');

        // Create user settings
        Setting::create([
            'user_id' => $user->id,
            'company_name' => 'John Doe Enterprises',
            'address' => '456 Business Ave, Business City, BC 67890',
            'tax_rate' => 15.00,
            'invoice_prefix' => 'INV',
        ]);
    }
}
