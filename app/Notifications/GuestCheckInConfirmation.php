<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestCheckInConfirmation extends Notification
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
            ->subject('Check-in Confirmation')
            ->greeting('Dear ' . ($this->booking->guest->full_name ?? 'Guest') . ',')
            ->line('You have successfully checked in.')
            ->line('Room Number: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('Check-in Date: ' . $this->booking->check_in_date)
            ->line('We hope you enjoy your stay with us.');
    }
}