<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FileStatusNotification extends Notification
{
    use Queueable;

    public $fileName;
    public $status;


    public function __construct($fileName, $status)
    {
        $this->fileName = $fileName;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->status === 'accepted' ? 'File Accepted' : 'File Rejected',
            'message' => "Your file '{$this->fileName}' has been {$this->status}.",
            'status' => $this->status,
        ];
    }
}
