<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestBookingConfirmation extends Notification
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
            ->subject('Booking Confirmation - ' . ($this->booking->booking_code ?? 'N/A'))
            ->greeting('Dear ' . ($this->booking->guest->full_name ?? 'Guest') . ',')
            ->line('Your booking has been confirmed successfully.')
            ->line('Booking Code: ' . ($this->booking->booking_code ?? 'N/A'))
            ->line('Room: ' . ($this->booking->room->room_number ?? 'N/A'))
            ->line('Check-in: ' . $this->booking->check_in_date)
            ->line('Check-out: ' . $this->booking->check_out_date)
            ->line('Total Amount: TZS ' . number_format($this->booking->total_amount))
            ->line('Thank you for choosing us. We look forward to hosting you.');
    }
}