<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
        $invoice = $this->payment->invoice;
        $totalPaid = $invoice->payments->sum('amount');
        $outstanding = $invoice->total - $totalPaid;

        return (new MailMessage)
            ->subject('Payment Received - ' . $invoice->invoice_number)
            ->greeting('Hello ' . $invoice->client->name . ',')
            ->line('We have received your payment. Thank you!')
            ->line('**Invoice Number:** ' . $invoice->invoice_number)
            ->line('**Payment Amount:** $' . number_format($this->payment->amount, 2))
            ->line('**Payment Date:** ' . $this->payment->payment_date->format('M d, Y'))
            ->line('**Payment Method:** ' . ucwords(str_replace('_', ' ', $this->payment->payment_method)))
            ->line('**Invoice Total:** $' . number_format($invoice->total, 2))
            ->line('**Total Paid:** $' . number_format($totalPaid, 2))
            ->line('**Outstanding Balance:** $' . number_format($outstanding, 2))
            ->action('View Invoice', route('user.invoices.show', $invoice))
            ->line('Thank you for your payment!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'invoice_id' => $this->payment->invoice_id,
            'amount' => $this->payment->amount,
        ];
    }
}
