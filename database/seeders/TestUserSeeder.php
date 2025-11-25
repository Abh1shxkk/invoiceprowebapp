<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Client;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Create a complete test user with demo data for all modules.
     */
    public function run(): void
    {
        // Create Test User
        $testUser = User::firstOrCreate(
            ['email' => 'demo@invoicepro.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Assign user role
        if (!$testUser->hasRole('user')) {
            $testUser->assignRole('user');
        }

        // Create user settings
        Setting::updateOrCreate(
            ['user_id' => $testUser->id],
            [
                'company_name' => 'Demo Enterprises Pvt Ltd',
                'address' => 'Plot No. 123, Sector 18, Noida, UP 201301',
                'phone' => '+91-9876543210',
                'email' => 'contact@demoenterprises.com',
                'website' => 'www.demoenterprises.com',
                'tax_id' => 'GSTIN29ABCDE1234F1Z5',
                'default_tax_rate' => 18.00,
                'invoice_prefix' => 'DEMO',
                'invoice_start_number' => 1001,
                'payment_terms' => 'Payment due within 30 days',
                'invoice_footer' => 'Thank you for your business! | Bank: HDFC Bank | A/C: 1234567890 | IFSC: HDFC0001234',
            ]
        );

        $this->command->info('✅ Test user created: demo@invoicepro.com / demo123');

        // Create Categories (matching actual table schema)
        $categories = [
            ['name' => 'Electronics', 'type' => 'income'],
            ['name' => 'Software', 'type' => 'income'],
            ['name' => 'Consulting', 'type' => 'income'],
            ['name' => 'Hardware', 'type' => 'income'],
            ['name' => 'Services', 'type' => 'income'],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                [
                    'user_id' => $testUser->id,
                    'name' => $categoryData['name']
                ],
                [
                    'type' => $categoryData['type']
                ]
            );
            $createdCategories[] = $category;
        }

        $this->command->info('✅ Created ' . count($categories) . ' categories');

        // Create Clients
        $clients = [
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh@techcorp.in',
                'phone' => '+91-9876543211',
                'address' => 'A-45, Connaught Place, New Delhi, DL 110001',
                'company' => 'Tech Corp India Pvt Ltd',
                'tax_number' => 'GSTIN07ABCDE1234F1Z5',
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya@innovate.in',
                'phone' => '+91-9876543212',
                'address' => 'B-23, MG Road, Bangalore, KA 560001',
                'company' => 'Innovate Solutions',
                'tax_number' => 'GSTIN29FGHIJ5678K2L6',
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit@digitech.in',
                'phone' => '+91-9876543213',
                'address' => 'C-78, Bandra West, Mumbai, MH 400050',
                'company' => 'DigiTech Services',
                'tax_number' => 'GSTIN27KLMNO9012M3N7',
            ],
            [
                'name' => 'Sneha Reddy',
                'email' => 'sneha@cloudify.in',
                'phone' => '+91-9876543214',
                'address' => 'D-12, Hitech City, Hyderabad, TG 500081',
                'company' => 'Cloudify India',
                'tax_number' => 'GSTIN36PQRST3456O4P8',
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram@webpro.in',
                'phone' => '+91-9876543215',
                'address' => 'E-56, Park Street, Kolkata, WB 700016',
                'company' => 'WebPro Solutions',
                'tax_number' => 'GSTIN19UVWXY7890Q5R9',
            ],
        ];

        $createdClients = [];
        foreach ($clients as $clientData) {
            $client = Client::firstOrCreate(
                [
                    'user_id' => $testUser->id,
                    'email' => $clientData['email']
                ],
                $clientData
            );
            $createdClients[] = $client;
        }

        $this->command->info('✅ Created ' . count($clients) . ' clients');

        // Create Invoices with Items
        $invoiceData = [
            [
                'client' => $createdClients[0],
                'status' => 'paid',
                'items' => [
                    ['name' => 'Laptop Dell Inspiron 15', 'quantity' => 2, 'price' => 45000, 'category' => $createdCategories[0]],
                    ['name' => 'Wireless Mouse', 'quantity' => 5, 'price' => 500, 'category' => $createdCategories[3]],
                ],
            ],
            [
                'client' => $createdClients[1],
                'status' => 'pending',
                'items' => [
                    ['name' => 'Microsoft Office 365 License', 'quantity' => 10, 'price' => 3500, 'category' => $createdCategories[1]],
                    ['name' => 'Antivirus Software', 'quantity' => 10, 'price' => 1200, 'category' => $createdCategories[1]],
                ],
            ],
            [
                'client' => $createdClients[2],
                'status' => 'paid',
                'items' => [
                    ['name' => 'Web Development Services', 'quantity' => 1, 'price' => 75000, 'category' => $createdCategories[4]],
                    ['name' => 'SEO Optimization', 'quantity' => 1, 'price' => 25000, 'category' => $createdCategories[4]],
                ],
            ],
            [
                'client' => $createdClients[3],
                'status' => 'overdue',
                'items' => [
                    ['name' => 'Cloud Hosting - Annual', 'quantity' => 1, 'price' => 50000, 'category' => $createdCategories[4]],
                    ['name' => 'SSL Certificate', 'quantity' => 2, 'price' => 2500, 'category' => $createdCategories[4]],
                ],
            ],
            [
                'client' => $createdClients[4],
                'status' => 'draft',
                'items' => [
                    ['name' => 'IT Consulting - Monthly', 'quantity' => 3, 'price' => 30000, 'category' => $createdCategories[2]],
                    ['name' => 'System Maintenance', 'quantity' => 1, 'price' => 15000, 'category' => $createdCategories[4]],
                ],
            ],
        ];

        $invoiceNumber = 1001;
        foreach ($invoiceData as $data) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }
            
            $taxAmount = $subtotal * 0.18; // 18% GST
            $total = $subtotal + $taxAmount;

            // Set dates based on status
            $issueDate = now()->subDays(rand(1, 30));
            $dueDate = $issueDate->copy()->addDays(30);
            
            if ($data['status'] === 'overdue') {
                $dueDate = now()->subDays(rand(1, 15));
            }

            $invoice = Invoice::create([
                'user_id' => $testUser->id,
                'client_id' => $data['client']->id,
                'invoice_number' => 'DEMO-' . $invoiceNumber++,
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => $data['status'],
                'notes' => 'Thank you for your business!',
            ]);

            // Create invoice items
            foreach ($data['items'] as $itemData) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'category_id' => $itemData['category']->id,
                    'description' => $itemData['name'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['price'],
                    'total' => $itemData['quantity'] * $itemData['price'],
                ]);
            }
        }

        $this->command->info('✅ Created ' . count($invoiceData) . ' invoices with items');

        // Summary
        $this->command->info('');
        $this->command->info('🎉 Test user setup complete!');
        $this->command->info('');
        $this->command->info('📊 Demo Data Summary:');
        $this->command->info('  • User: demo@invoicepro.com / demo123');
        $this->command->info('  • Categories: ' . count($categories));
        $this->command->info('  • Clients: ' . count($clients));
        $this->command->info('  • Invoices: ' . count($invoiceData));
        $this->command->info('  • Status: 2 Paid, 1 Pending, 1 Overdue, 1 Draft');
        $this->command->info('');
        $this->command->info('✨ Login and explore all modules with demo data!');
    }
}
