<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceReminderNotification extends Notification implements ShouldQueue
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
        $daysOverdue = now()->diffInDays($this->invoice->due_date, false);
        $totalPaid = $this->invoice->payments->sum('amount');
        $outstanding = $this->invoice->total - $totalPaid;

        $pdfService = app(PdfService::class);
        $pdf = $pdfService->generateInvoicePdf($this->invoice);

        return (new MailMessage)
            ->subject('Payment Reminder - Invoice ' . $this->invoice->invoice_number . ' Overdue')
            ->greeting('Hello ' . $this->invoice->client->name . ',')
            ->line('This is a friendly reminder that your invoice is overdue.')
            ->line('**Invoice Number:** ' . $this->invoice->invoice_number)
            ->line('**Due Date:** ' . $this->invoice->due_date->format('M d, Y'))
            ->line('**Days Overdue:** ' . abs($daysOverdue) . ' days')
            ->line('**Outstanding Amount:** $' . number_format($outstanding, 2))
            ->action('View & Pay Invoice', route('user.invoices.show', $this->invoice))
            ->line('Please arrange payment at your earliest convenience.')
            ->line('If you have already made the payment, please disregard this reminder.')
            ->line('Thank you for your prompt attention to this matter.')
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
            'days_overdue' => now()->diffInDays($this->invoice->due_date, false),
        ];
    }
}
