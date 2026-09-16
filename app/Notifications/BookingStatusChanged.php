<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingStatusChanged extends Notification
{
    use Queueable;

    public $booking;
    public $oldStatus;
    public $newStatus;

    public function __construct(Booking $booking, $oldStatus, $newStatus)
    {
        $this->booking = $booking;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Booking Status Updated',
            'message' => "Booking {$this->booking->booking_code} changed from " . ucfirst($this->oldStatus) . " to " . ucfirst(str_replace('_', ' ', $this->newStatus)),
            'type' => 'booking_status',
            'booking_id' => $this->booking->id,
            'url' => route('bookings.show', $this->booking->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Status Changed - ' . $this->booking->booking_code)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Booking status has been updated.")
            ->line("Booking Code: {$this->booking->booking_code}")
            ->line("From: " . ucfirst($this->oldStatus))
            ->line("To: " . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->action('View Booking', route('bookings.show', $this->booking->id));
    }
}