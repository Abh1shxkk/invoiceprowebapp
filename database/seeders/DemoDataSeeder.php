<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        // Create demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@invoicepro.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Create categories
        $expenseCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Office Supplies',
            'type' => 'expense',
        ]);

        Category::create([
            'user_id' => $user->id,
            'name' => 'Travel',
            'type' => 'expense',
        ]);

        Category::create([
            'user_id' => $user->id,
            'name' => 'Utilities',
            'type' => 'expense',
        ]);

        // Create 5 clients
        $clients = [];
        $clientNames = [
            ['name' => 'Acme Corporation', 'company' => 'Acme Corp', 'email' => 'contact@acme.com'],
            ['name' => 'Tech Solutions Inc', 'company' => 'Tech Solutions', 'email' => 'info@techsolutions.com'],
            ['name' => 'Global Enterprises', 'company' => 'Global Ent', 'email' => 'hello@globalent.com'],
            ['name' => 'Startup Innovations', 'company' => 'Startup Inn', 'email' => 'team@startupinn.com'],
            ['name' => 'Creative Agency', 'company' => 'Creative Co', 'email' => 'contact@creative.com'],
        ];

        foreach ($clientNames as $clientData) {
            $clients[] = Client::create([
                'user_id' => $user->id,
                'name' => $clientData['name'],
                'company' => $clientData['company'],
                'email' => $clientData['email'],
                'phone' => '+1 ' . rand(200, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'address' => rand(100, 999) . ' Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
            ]);
        }

        // Create 15 invoices with various statuses
        $statuses = ['draft', 'sent', 'paid', 'overdue'];
        $invoiceNumber = 1;

        foreach ($clients as $client) {
            for ($i = 0; $i < 3; $i++) {
                $status = $statuses[array_rand($statuses)];
                $subtotal = rand(500, 5000);
                $tax = 10;
                $taxAmount = ($subtotal * $tax) / 100;
                $total = $subtotal + $taxAmount;

                $invoice = Invoice::create([
                    'user_id' => $user->id,
                    'client_id' => $client->id,
                    'invoice_number' => 'INV-' . str_pad($invoiceNumber++, 5, '0', STR_PAD_LEFT),
                    'issue_date' => now()->subDays(rand(1, 60)),
                    'due_date' => now()->addDays(rand(1, 30)),
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'status' => $status,
                    'notes' => 'Thank you for your business!',
                ]);

                // Add 2-4 items to each invoice
                $itemCount = rand(2, 4);
                for ($j = 0; $j < $itemCount; $j++) {
                    $quantity = rand(1, 10);
                    $price = rand(50, 500);

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => [
                            'Web Development Services',
                            'Design Consultation',
                            'SEO Optimization',
                            'Content Writing',
                            'Social Media Management',
                            'Logo Design',
                            'Mobile App Development',
                            'Database Setup',
                        ][rand(0, 7)],
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $quantity * $price,
                    ]);
                }

                // Add payments to paid invoices
                if ($status === 'paid') {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $total,
                        'payment_date' => now()->subDays(rand(1, 30)),
                        'payment_method' => ['cash', 'bank_transfer', 'credit_card', 'upi'][rand(0, 3)],
                        'notes' => 'Payment received',
                    ]);
                }

                // Add partial payments to some sent invoices
                if ($status === 'sent' && rand(0, 1)) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $total / 2,
                        'payment_date' => now()->subDays(rand(1, 20)),
                        'payment_method' => ['cash', 'bank_transfer', 'credit_card', 'upi'][rand(0, 3)],
                        'notes' => 'Partial payment',
                    ]);
                }
            }
        }

        // Create 10 expenses
        $expenseDescriptions = [
            'Office supplies purchase',
            'Printer ink and paper',
            'Software subscription',
            'Internet bill',
            'Electricity bill',
            'Client meeting lunch',
            'Conference tickets',
            'Office furniture',
            'Marketing materials',
            'Professional development course',
        ];

        foreach ($expenseDescriptions as $description) {
            Expense::create([
                'user_id' => $user->id,
                'category_id' => $expenseCategory->id,
                'amount' => rand(50, 500),
                'date' => now()->subDays(rand(1, 60)),
                'description' => $description,
            ]);
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Email: demo@invoicepro.com');
        $this->command->info('Password: password');
    }
}
