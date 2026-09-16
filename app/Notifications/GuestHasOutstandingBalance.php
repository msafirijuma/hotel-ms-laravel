<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestHasOutstandingBalance extends Notification
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
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Outstanding Balance Alert',
            'message' => "Guest {$this->booking->guest->full_name} has outstanding balance of TZS " . number_format($this->balance),
            'type' => 'balance',
            'booking_id' => $this->booking->id,
            'url' => route('bookings.show', $this->booking->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Outstanding Balance Alert')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Guest {$this->booking->guest->full_name} still has an outstanding balance.")
            ->line('Amount Due: TZS ' . number_format($this->balance))
            ->line('Booking Code: ' . ($this->booking->booking_code ?? 'N/A'))
            ->action('View Booking', route('bookings.show', $this->booking->id));
    }
}