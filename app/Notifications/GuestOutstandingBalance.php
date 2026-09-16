<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestOutstandingBalance extends Notification
{
    use Queueable;

    public $booking;
    public $balance;

    public function __construct(Booking $booking, $balance)
    {
        $this->booking = $booking;
        $this->balance = $balance;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Outstanding Balance Reminder')
            ->greeting('Dear ' . ($this->booking->guest->full_name ?? 'Guest') . ',')
            ->line('This is a friendly reminder that you have an outstanding balance.')
            ->line('Amount Due: TZS ' . number_format($this->balance))
            ->line('Booking Code: ' . ($this->booking->booking_code ?? 'N/A'))
            ->line('Please settle the remaining amount at your earliest convenience.');
    }
}