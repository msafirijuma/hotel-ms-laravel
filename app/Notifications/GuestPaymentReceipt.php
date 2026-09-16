<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestPaymentReceipt extends Notification
{
    use Queueable;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $amount = $this->payment->amount_paid ?? $this->payment->amount;

        return (new MailMessage)
            ->subject('Payment Receipt')
            ->greeting('Dear ' . ($this->payment->booking->guest->full_name ?? 'Guest') . ',')
            ->line('We have received your payment. Thank you!')
            ->line('Amount Paid: TZS ' . number_format($amount))
            ->line('Booking Code: ' . ($this->payment->booking->booking_code ?? 'N/A'))
            ->line('Payment Method: ' . ucfirst($this->payment->payment_method ?? 'N/A'))
            ->line('Date: ' . $this->payment->created_at->format('d M Y, H:i'))
            ->line('Thank you for your payment.');
    }
}