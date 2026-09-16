<?php

namespace App\Notifications;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestCreated extends Notification
{
    use Queueable;

    public $guest;

    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Guest Registered',
            'message' => "New guest named {$this->guest->full_name} has been added to the system.",
            'type' => 'guest',
            'guest_id' => $this->guest->id,
            'url' => route('guests.show', $this->guest->id),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Guest Registered')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new guest has been registered.')
            ->line('Name: ' . $this->guest->full_name)
            ->line('Phone: ' . $this->guest->phone)
            ->line('ID Number: ' . $this->guest->id_number)
            ->action('View Guest', route('guests.show', $this->guest->id));
    }
}