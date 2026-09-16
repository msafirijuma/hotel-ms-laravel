<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestCheckOut extends Notification
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Guest Checked Out',
            'message' => "{$this->booking->guest->full_name} has checked out from Room {$this->booking->room->room_number}",
            'type' => 'checkout',
            'booking_id' => $this->booking->id,
            'guest_id' => $this->booking->guest_id,
            'url' => route('bookings.show', $this->booking->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Guest Checked Out - ' . $this->booking->guest->full_name)
            ->line("{$this->booking->guest->full_name} has checked out.")
            ->line('Room: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('Check-out Date: ' . $this->booking->check_out_date)
            ->action('View Booking', route('bookings.show', $this->booking->id));
    }
}