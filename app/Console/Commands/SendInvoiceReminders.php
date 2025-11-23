<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\InvoiceReminderNotification;
use Illuminate\Console\Command;

class SendInvoiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for overdue invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue invoices...');

        // Get all overdue invoices
        $overdueInvoices = Invoice::where('status', 'overdue')
            ->orWhere(function($query) {
                $query->whereIn('status', ['sent', 'draft'])
                      ->where('due_date', '<', now());
            })
            ->with(['client', 'payments'])
            ->get();

        if ($overdueInvoices->isEmpty()) {
            $this->info('No overdue invoices found.');
            return 0;
        }

        $count = 0;

        foreach ($overdueInvoices as $invoice) {
            // Check if there's an outstanding balance
            $totalPaid = $invoice->payments->sum('amount');
            $outstanding = $invoice->total - $totalPaid;

            if ($outstanding > 0) {
                // Update status to overdue if not already
                if ($invoice->status !== 'overdue') {
                    $invoice->update(['status' => 'overdue']);
                }

                // Send reminder notification
                if ($invoice->client->email) {
                    $invoice->client->notify(new InvoiceReminderNotification($invoice));
                    $count++;
                    $this->info("Reminder sent for invoice: {$invoice->invoice_number}");
                }
            }
        }

        $this->info("Total reminders sent: {$count}");

        return 0;
    }
}
