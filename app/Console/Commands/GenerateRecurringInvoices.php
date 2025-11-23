<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Notifications\InvoiceCreatedNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring invoices based on schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for recurring invoices to generate...');

        $recurringInvoices = Invoice::where('is_recurring', true)
            ->where('recurring_start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('recurring_end_date')
                      ->orWhere('recurring_end_date', '>=', now());
            })
            ->with(['client', 'items'])
            ->get();

        if ($recurringInvoices->isEmpty()) {
            $this->info('No recurring invoices to generate.');
            return 0;
        }

        $count = 0;

        foreach ($recurringInvoices as $invoice) {
            if ($this->shouldGenerateInvoice($invoice)) {
                try {
                    $newInvoice = $this->generateInvoice($invoice);
                    $count++;
                    $this->info("Generated invoice: {$newInvoice->invoice_number}");
                } catch (\Exception $e) {
                    $this->error("Failed to generate invoice for {$invoice->invoice_number}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Total recurring invoices generated: {$count}");

        return 0;
    }

    /**
     * Check if invoice should be generated today.
     */
    protected function shouldGenerateInvoice(Invoice $invoice): bool
    {
        $lastDate = $invoice->last_recurring_date 
            ? Carbon::parse($invoice->last_recurring_date)
            : Carbon::parse($invoice->recurring_start_date)->subDay();

        $nextDate = match($invoice->recurring_frequency) {
            'weekly' => $lastDate->copy()->addWeek(),
            'monthly' => $lastDate->copy()->addMonth(),
            'quarterly' => $lastDate->copy()->addMonths(3),
            'yearly' => $lastDate->copy()->addYear(),
            default => null,
        };

        return $nextDate && $nextDate->isToday();
    }

    /**
     * Generate new invoice from recurring template.
     */
    protected function generateInvoice(Invoice $template): Invoice
    {
        DB::beginTransaction();

        try {
            // Generate new invoice number
            $lastInvoice = Invoice::where('user_id', $template->user_id)
                ->latest()
                ->first();

            $lastNumber = $lastInvoice 
                ? (int) filter_var($lastInvoice->invoice_number, FILTER_SANITIZE_NUMBER_INT)
                : 0;

            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            $invoiceNumber = 'INV-' . $newNumber;

            // Create new invoice
            $newInvoice = Invoice::create([
                'user_id' => $template->user_id,
                'client_id' => $template->client_id,
                'invoice_number' => $invoiceNumber,
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $template->subtotal,
                'tax' => $template->tax,
                'total' => $template->total,
                'status' => 'sent',
                'notes' => $template->notes,
                'is_recurring' => false,
                'parent_invoice_id' => $template->id,
            ]);

            // Copy items
            foreach ($template->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $newInvoice->id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }

            // Update last recurring date
            $template->update(['last_recurring_date' => now()]);

            // Send email notification
            if ($template->client->email) {
                $template->client->notify(new InvoiceCreatedNotification($newInvoice));
            }

            DB::commit();

            return $newInvoice;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
