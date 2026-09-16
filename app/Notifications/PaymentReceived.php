<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceived extends Notification
{
    use Queueable;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Payment Received',
            'message' => "Payment of TZS " . number_format($this->payment->amount_paid ?? $this->payment->amount) . " received for booking {$this->payment->booking->booking_code}",
            'type' => 'payment',
            'payment_id' => $this->payment->id,
            'url' => route('bookings.show', $this->payment->booking_id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Received')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new payment has been recorded.')
            ->line('Amount: TZS ' . number_format($this->payment->amount_paid ?? $this->payment->amount))
            ->line('Booking: ' . ($this->payment->booking->booking_code ?? 'N/A'))
            ->action('View Booking', route('bookings.show', $this->payment->booking_id));
    }
}