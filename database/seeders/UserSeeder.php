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
        // Create or find Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@invoicepro.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role if not already assigned
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create or update admin settings
        Setting::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'company_name' => 'InvoicePro Admin',
                'address' => '123 Admin Street, Admin City, AC 12345',
                'default_tax_rate' => 18.00,
                'invoice_prefix' => 'ADM',
            ]
        );

        // Create or find Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@invoicepro.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Assign user role if not already assigned
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        // Create or update user settings
        Setting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => 'John Doe Enterprises',
                'address' => '456 Business Ave, Business City, BC 67890',
                'default_tax_rate' => 15.00,
                'invoice_prefix' => 'INV',
            ]
        );

        // Create or find Test User (your test account)
        $testUser = User::firstOrCreate(
            ['email' => 'test@invoicepro.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('test123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Assign user role if not already assigned
        if (!$testUser->hasRole('user')) {
            $testUser->assignRole('user');
        }

        // Create or update test user settings
        Setting::updateOrCreate(
            ['user_id' => $testUser->id],
            [
                'company_name' => 'Test Company',
                'address' => 'Test Address, Test City, TC 12345',
                'default_tax_rate' => 18.00,
                'invoice_prefix' => 'TEST',
            ]
        );

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('Admin: admin@invoicepro.com / password');
        $this->command->info('User: user@invoicepro.com / password');
        $this->command->info('Test: test@invoicepro.com / test123');
    }
}
