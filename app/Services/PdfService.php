<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Generate PDF for an invoice.
     */
    public function generateInvoicePdf(Invoice $invoice)
    {
        // Load invoice with relationships
        $invoice->load('client', 'items', 'user.settings');

        // Get user settings for company info
        $settings = $invoice->user->settings;

        // Generate PDF
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'settings' => $settings,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Download invoice PDF.
     */
    public function downloadInvoicePdf(Invoice $invoice)
    {
        $pdf = $this->generateInvoicePdf($invoice);
        
        $filename = 'invoice-' . $invoice->invoice_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Stream invoice PDF (view in browser).
     */
    public function streamInvoicePdf(Invoice $invoice)
    {
        $pdf = $this->generateInvoicePdf($invoice);
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Save invoice PDF to storage.
     */
    public function saveInvoicePdf(Invoice $invoice, string $path = null): string
    {
        $pdf = $this->generateInvoicePdf($invoice);
        
        if (!$path) {
            $path = 'invoices/' . $invoice->invoice_number . '.pdf';
        }

        $pdf->save(storage_path('app/public/' . $path));

        return $path;
    }

    /**
     * Get PDF as string (for email attachment).
     */
    public function getInvoicePdfOutput(Invoice $invoice): string
    {
        $pdf = $this->generateInvoicePdf($invoice);
        
        return $pdf->output();
    }
}
