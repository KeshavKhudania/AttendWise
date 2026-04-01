<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\DatabaseMessage;

class ImportCompletedNotification extends Notification
{
    protected array $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Student Import Completed',
            'message' => "Imported {$this->summary['inserted']} students, skipped {$this->summary['skipped']}.",
            'data' => $this->summary
        ];
    }
}
