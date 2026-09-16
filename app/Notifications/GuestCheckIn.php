<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestCheckIn extends Notification
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
            'title' => 'Guest Checked In',
            'message' => "{$this->booking->guest->full_name} has checked into Room {$this->booking->room->room_number}",
            'type' => 'checkin',
            'booking_id' => $this->booking->id,
            'guest_id' => $this->booking->guest_id,
            'url' => route('bookings.show', $this->booking->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Guest Checked In - ' . $this->booking->guest->full_name)
            ->line("{$this->booking->guest->full_name} has successfully checked in.")
            ->line('Room: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('Check-in Date: ' . $this->booking->check_in_date)
            ->action('View Booking', route('bookings.show', $this->booking->id));
    }
}