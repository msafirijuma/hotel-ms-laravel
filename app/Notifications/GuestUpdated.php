<?php

namespace App\Notifications;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GuestUpdated extends Notification
{
    use Queueable;

    public $guest;

    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Guest Information Updated',
            'message' => "Guest {$this->guest->full_name} details have been updated",
            'type' => 'guest',
            'guest_id' => $this->guest->id,
            'url' => route('guests.show', $this->guest->id),
        ];
    }
}