<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookingCreated extends Notification
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
            'title' => 'New Booking Created',
            'message' => "Booking {$this->booking->booking_code} has been created for {$this->booking->guest->full_name}",
            'type' => 'booking',
            'booking_id' => $this->booking->id,
            'url' => route('bookings.show', $this->booking->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Booking - ' . ($this->booking->booking_code ?? 'N/A'))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new booking has been created.')
            ->line('Guest: ' . ($this->booking->guest->full_name ?? 'N/A'))
            ->line('Room: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('Check-in: ' . $this->booking->check_in_date)
            ->action('View Booking', route('bookings.show', $this->booking->id))
            ->line('Thank you for choosing our hotel.');
    }
}