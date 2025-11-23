<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the regular user (not admin)
        $user = User::where('email', 'user@invoicepro.com')->first();

        if ($user) {
            // Create Client 1
            Client::create([
                'user_id' => $user->id,
                'name' => 'Acme Corporation',
                'email' => 'contact@acmecorp.com',
                'phone' => '+1-555-0100',
                'address' => '789 Corporate Blvd, Suite 100, Business City, BC 11111',
                'company' => 'Acme Corporation',
                'tax_number' => 'TAX123456789',
            ]);

            // Create Client 2
            Client::create([
                'user_id' => $user->id,
                'name' => 'Tech Solutions Inc',
                'email' => 'info@techsolutions.com',
                'phone' => '+1-555-0200',
                'address' => '321 Innovation Drive, Tech Park, TP 22222',
                'company' => 'Tech Solutions Inc',
                'tax_number' => 'TAX987654321',
            ]);
        }
    }
}
