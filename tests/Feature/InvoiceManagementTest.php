<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
        $this->client = Client::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function user_can_create_invoice_with_items()
    {
        $this->actingAs($this->user);

        $invoiceData = [
            'client_id' => $this->client->id,
            'invoice_number' => 'INV-001',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'tax' => 10,
            'status' => 'draft',
            'notes' => 'Test notes',
            'items' => [
                [
                    'description' => 'Item 1',
                    'quantity' => 2,
                    'price' => 100,
                ],
                [
                    'description' => 'Item 2',
                    'quantity' => 1,
                    'price' => 50,
                ],
            ],
        ];

        $response = $this->post(route('user.invoices.store'), $invoiceData);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'invoice_number' => 'INV-001',
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
        ]);

        $invoice = Invoice::where('invoice_number', 'INV-001')->first();
        $this->assertEquals(2, $invoice->items()->count());
        $this->assertEquals(250, $invoice->subtotal); // 2*100 + 1*50
        $this->assertEquals(275, $invoice->total); // 250 + 10% tax
    }

    /** @test */
    public function invoice_requires_at_least_one_item()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('user.invoices.store'), [
            'client_id' => $this->client->id,
            'invoice_number' => 'INV-002',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'tax' => 10,
            'status' => 'draft',
            'items' => [],
        ]);

        $response->assertSessionHasErrors('items');
    }

    /** @test */
    public function due_date_must_be_after_or_equal_to_issue_date()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('user.invoices.store'), [
            'client_id' => $this->client->id,
            'invoice_number' => 'INV-003',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->subDays(1)->format('Y-m-d'),
            'tax' => 10,
            'status' => 'draft',
            'items' => [
                ['description' => 'Test', 'quantity' => 1, 'price' => 100],
            ],
        ]);

        $response->assertSessionHasErrors('due_date');
    }

    /** @test */
    public function user_can_view_their_own_invoice()
    {
        $this->actingAs($this->user);

        $invoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->get(route('user.invoices.show', $invoice));

        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
    }

    /** @test */
    public function user_cannot_view_another_users_invoice()
    {
        $this->actingAs($this->user);

        $otherUser = User::factory()->create(['role' => 'user']);
        $otherClient = Client::factory()->create(['user_id' => $otherUser->id]);
        $invoice = Invoice::factory()->create([
            'user_id' => $otherUser->id,
            'client_id' => $otherClient->id,
        ]);

        $response = $this->get(route('user.invoices.show', $invoice));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_their_own_invoice()
    {
        $this->actingAs($this->user);

        $invoice = Invoice::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->delete(route('user.invoices.destroy', $invoice));

        $response->assertRedirect();
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    /** @test */
    public function user_can_filter_invoices_by_status()
    {
        $this->actingAs($this->user);

        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
            'status' => 'paid',
        ]);

        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
            'status' => 'draft',
        ]);

        $response = $this->get(route('user.invoices.index', ['status' => 'paid']));

        $response->assertStatus(200);
    }

    /** @test */
    public function invoice_number_must_be_unique()
    {
        $this->actingAs($this->user);

        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
            'invoice_number' => 'INV-UNIQUE',
        ]);

        $response = $this->post(route('user.invoices.store'), [
            'client_id' => $this->client->id,
            'invoice_number' => 'INV-UNIQUE',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'tax' => 10,
            'status' => 'draft',
            'items' => [
                ['description' => 'Test', 'quantity' => 1, 'price' => 100],
            ],
        ]);

        $response->assertSessionHasErrors('invoice_number');
    }
}
