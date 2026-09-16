<?php

namespace App\Notifications;

use App\Models\HousekeepingTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification
{
    use Queueable;

    public $task;

    public function __construct(HousekeepingTask $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Cleaning Task Assigned',
            'message' => "You have been assigned Room {$this->task->room->room_number}",
            'type' => 'task',
            'task_id' => $this->task->id,
            'url' => route('housekeeping.dashboard'),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Cleaning Task Assigned')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned a new cleaning task.')
            ->line('Room: ' . ($this->task->room->room_number ?? 'N/A'))
            ->line('Notes: ' . ($this->task->notes ?? 'None'))
            ->action('View Tasks', route('housekeeping.dashboard'))
            ->line('Please complete the task on time.');
    }
}