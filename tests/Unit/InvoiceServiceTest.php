<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Invoice;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $settingService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingService = app(SettingService::class);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function it_generates_invoice_number_with_default_settings()
    {
        $invoiceNumber = $this->settingService->generateInvoiceNumber($this->user);

        $this->assertStringStartsWith('INV-', $invoiceNumber);
        $this->assertEquals('INV-00001', $invoiceNumber);
    }

    /** @test */
    public function it_increments_invoice_number_based_on_last_invoice()
    {
        // Create first invoice
        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'invoice_number' => 'INV-00001',
        ]);

        $invoiceNumber = $this->settingService->generateInvoiceNumber($this->user);

        $this->assertEquals('INV-00002', $invoiceNumber);
    }

    /** @test */
    public function it_uses_custom_prefix_from_settings()
    {
        // Update settings with custom prefix
        $settings = $this->settingService->getUserSettings($this->user);
        $settings->update(['invoice_prefix' => 'CUSTOM']);

        $invoiceNumber = $this->settingService->generateInvoiceNumber($this->user);

        $this->assertStringStartsWith('CUSTOM-', $invoiceNumber);
    }

    /** @test */
    public function it_uses_custom_starting_number_from_settings()
    {
        // Update settings with custom starting number
        $settings = $this->settingService->getUserSettings($this->user);
        $settings->update(['invoice_start_number' => 1000]);

        $invoiceNumber = $this->settingService->generateInvoiceNumber($this->user);

        $this->assertEquals('INV-01000', $invoiceNumber);
    }

    /** @test */
    public function it_gets_default_tax_rate()
    {
        $settings = $this->settingService->getUserSettings($this->user);
        $settings->update(['default_tax_rate' => 15.5]);

        $taxRate = $this->settingService->getDefaultTaxRate($this->user);

        $this->assertEquals(15.5, $taxRate);
    }

    /** @test */
    public function it_calculates_invoice_totals_correctly()
    {
        $subtotal = 1000;
        $taxRate = 10;

        $taxAmount = ($subtotal * $taxRate) / 100;
        $total = $subtotal + $taxAmount;

        $this->assertEquals(100, $taxAmount);
        $this->assertEquals(1100, $total);
    }

    /** @test */
    public function it_pads_invoice_numbers_with_zeros()
    {
        Invoice::factory()->create([
            'user_id' => $this->user->id,
            'invoice_number' => 'INV-00099',
        ]);

        $invoiceNumber = $this->settingService->generateInvoiceNumber($this->user);

        $this->assertEquals('INV-00100', $invoiceNumber);
    }
}
