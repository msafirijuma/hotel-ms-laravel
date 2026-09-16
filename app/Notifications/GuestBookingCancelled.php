<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestBookingCancelled extends Notification
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Cancellation Notice')
            ->greeting('Dear ' . ($this->booking->guest->full_name ?? 'Guest') . ',')
            ->line('Your booking has been cancelled.')
            ->line('Booking Code: ' . ($this->booking->booking_code ?? 'N/A'))
            ->line('Room: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('If you have any questions, please contact us.');
    }
}