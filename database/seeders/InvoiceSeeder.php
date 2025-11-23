<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the regular user and clients
        $user = User::where('email', 'user@invoicepro.com')->first();
        $clients = Client::where('user_id', $user->id)->get();

        if ($user && $clients->count() >= 2) {
            // Invoice 1 - Draft
            $invoice1 = Invoice::create([
                'invoice_number' => 'INV-2024-001',
                'client_id' => $clients[0]->id,
                'user_id' => $user->id,
                'issue_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(25),
                'subtotal' => 1000.00,
                'tax' => 150.00,
                'total' => 1150.00,
                'status' => 'draft',
                'notes' => 'Thank you for your business!',
            ]);

            // Invoice 1 Items
            InvoiceItem::create([
                'invoice_id' => $invoice1->id,
                'description' => 'Web Development Services',
                'quantity' => 20,
                'price' => 50.00,
                'total' => 1000.00,
            ]);

            // Invoice 2 - Sent
            $invoice2 = Invoice::create([
                'invoice_number' => 'INV-2024-002',
                'client_id' => $clients[1]->id,
                'user_id' => $user->id,
                'issue_date' => Carbon::now()->subDays(10),
                'due_date' => Carbon::now()->addDays(20),
                'subtotal' => 2500.00,
                'tax' => 375.00,
                'total' => 2875.00,
                'status' => 'sent',
                'notes' => 'Payment due within 30 days.',
            ]);

            // Invoice 2 Items
            InvoiceItem::create([
                'invoice_id' => $invoice2->id,
                'description' => 'UI/UX Design Services',
                'quantity' => 40,
                'price' => 50.00,
                'total' => 2000.00,
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice2->id,
                'description' => 'Logo Design',
                'quantity' => 1,
                'price' => 500.00,
                'total' => 500.00,
            ]);

            // Invoice 3 - Paid
            $invoice3 = Invoice::create([
                'invoice_number' => 'INV-2024-003',
                'client_id' => $clients[0]->id,
                'user_id' => $user->id,
                'issue_date' => Carbon::now()->subDays(45),
                'due_date' => Carbon::now()->subDays(15),
                'subtotal' => 1500.00,
                'tax' => 225.00,
                'total' => 1725.00,
                'status' => 'paid',
                'notes' => 'Paid in full. Thank you!',
            ]);

            // Invoice 3 Items
            InvoiceItem::create([
                'invoice_id' => $invoice3->id,
                'description' => 'Mobile App Development',
                'quantity' => 30,
                'price' => 50.00,
                'total' => 1500.00,
            ]);
        }
    }
}
