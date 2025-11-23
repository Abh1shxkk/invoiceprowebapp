<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;

class InvoiceService
{
    /**
     * Generate next invoice number for a user.
     */
    public function generateInvoiceNumber(User $user): string
    {
        $lastInvoice = Invoice::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($lastInvoice) {
            // Extract number from last invoice and increment
            $lastNumber = (int) filter_var($lastInvoice->invoice_number, FILTER_SANITIZE_NUMBER_INT);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate invoice totals from items.
     */
    public function calculateTotals(array $items, float $taxRate): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $quantity = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            $subtotal += $quantity * $price;
        }

        $taxAmount = ($subtotal * $taxRate) / 100;
        $total = $subtotal + $taxAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Update invoice status.
     */
    public function updateStatus(Invoice $invoice, string $status): bool
    {
        $validStatuses = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $invoice->update(['status' => $status]);

        return true;
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice): bool
    {
        return $this->updateStatus($invoice, 'paid');
    }

    /**
     * Mark invoice as sent.
     */
    public function markAsSent(Invoice $invoice): bool
    {
        return $this->updateStatus($invoice, 'sent');
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(Invoice $invoice): bool
    {
        if ($invoice->status === 'paid') {
            return false;
        }

        return $invoice->due_date->isPast();
    }

    /**
     * Get invoice statistics for a user.
     */
    public function getUserStatistics(User $user): array
    {
        $invoices = Invoice::where('user_id', $user->id);

        return [
            'total_invoices' => $invoices->count(),
            'total_revenue' => $invoices->where('status', 'paid')->sum('total'),
            'pending_amount' => $invoices->whereIn('status', ['draft', 'sent'])->sum('total'),
            'overdue_count' => $invoices->where('status', 'overdue')->count(),
        ];
    }
}
