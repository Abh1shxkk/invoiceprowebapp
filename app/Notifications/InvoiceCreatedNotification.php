<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $pdfService = app(PdfService::class);
        $pdf = $pdfService->generateInvoicePdf($this->invoice);

        return (new MailMessage)
            ->subject('New Invoice: ' . $this->invoice->invoice_number)
            ->greeting('Hello ' . $this->invoice->client->name . ',')
            ->line('You have received a new invoice from ' . $this->invoice->user->name . '.')
            ->line('**Invoice Number:** ' . $this->invoice->invoice_number)
            ->line('**Amount:** $' . number_format($this->invoice->total, 2))
            ->line('**Due Date:** ' . $this->invoice->due_date->format('M d, Y'))
            ->action('View Invoice', route('user.invoices.show', $this->invoice))
            ->line('Please find the invoice attached to this email.')
            ->line('Thank you for your business!')
            ->attachData($pdf->output(), $this->invoice->invoice_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->total,
        ];
    }
}
