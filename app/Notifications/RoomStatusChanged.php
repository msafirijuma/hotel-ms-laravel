<?php

namespace App\Notifications;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RoomStatusChanged extends Notification
{
    use Queueable;

    public $room;
    public $oldStatus;
    public $newStatus;

    public function __construct(Room $room, $oldStatus, $newStatus)
    {
        $this->room = $room;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Room Status Changed',
            'message' => "Room {$this->room->room_number} status changed from " . ucfirst($this->oldStatus) . " to " . ucfirst($this->newStatus),
            'type' => 'room_status',
            'room_id' => $this->room->id,
            'url' => route('rooms.index'),
        ];
    }
}