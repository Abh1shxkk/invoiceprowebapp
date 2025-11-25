<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the database for production environment.
     * Creates admin and user accounts with secure credentials.
     */
    public function run(): void
    {
        // First, seed roles
        $this->call(RoleSeeder::class);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@yourdomain.com'], // Change this to your actual domain
            [
                'name' => 'System Administrator',
                'password' => Hash::make('YourSecurePassword123!'), // CHANGE THIS PASSWORD!
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create or update admin settings
        Setting::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'company_name' => 'Your Company Name', // Change this
                'address' => 'Your Company Address', // Change this
                'default_tax_rate' => 18.00,
                'invoice_prefix' => 'INV',
            ]
        );

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@yourdomain.com'], // Change this to your actual domain
            [
                'name' => 'Regular User',
                'password' => Hash::make('UserPassword123!'), // CHANGE THIS PASSWORD!
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Assign user role
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        // Create or update user settings
        Setting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => 'User Company',
                'address' => 'User Address',
                'default_tax_rate' => 15.00,
                'invoice_prefix' => 'USR',
            ]
        );

        $this->command->info('✅ Production data seeded successfully!');
        $this->command->info('');
        $this->command->info('🔐 IMPORTANT: Change these default credentials immediately!');
        $this->command->info('');
        $this->command->info('Admin Account:');
        $this->command->info('  Email: admin@yourdomain.com');
        $this->command->info('  Password: YourSecurePassword123!');
        $this->command->info('');
        $this->command->info('User Account:');
        $this->command->info('  Email: user@yourdomain.com');
        $this->command->info('  Password: UserPassword123!');
        $this->command->info('');
        $this->command->warn('⚠️  Remember to change passwords after first login!');
    }
}
