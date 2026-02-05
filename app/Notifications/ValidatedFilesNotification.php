<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ValidatedFilesNotification extends Notification
{
    use Queueable;

    public $status;
    public $fullname;

    public function __construct($status, $fullname)
    {
        $this->status = $status;
        $this->fullname = $fullname;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

        if ($this->status == 'accept') {
            return [
                'title' => "Validated by {$this->fullname}",
                'message' => 'Your uploaded files have been successfully validated.',
                'type' => 'upload_accept',
            ];
        } else {
            return [
                'title' => "Rejected by {$this->fullname}",
                'message' => 'There was an issue with your uploaded files. Please review and reupload.',
                'type' => 'upload_reject',
            ];
        }
    }
}
