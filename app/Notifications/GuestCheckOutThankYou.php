<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestCheckOutThankYou extends Notification
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
            ->subject('Thank You for Staying With Us')
            ->greeting('Dear ' . ($this->booking->guest->full_name ?? 'Guest') . ',')
            ->line('Thank you for staying with us. We hope you enjoyed your time.')
            ->line('Room: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('Check-out Date: ' . $this->booking->check_out_date)
            ->line('We look forward to welcoming you again soon.');
    }
}