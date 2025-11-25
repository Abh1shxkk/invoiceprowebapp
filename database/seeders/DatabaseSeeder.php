<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in order: Roles -> Users -> Test User -> Categories -> Clients -> Invoices
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TestUserSeeder::class,  // Complete demo user with all data
            CategorySeeder::class,
            ClientSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
