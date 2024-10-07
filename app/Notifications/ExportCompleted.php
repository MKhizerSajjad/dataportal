<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportCompleted extends Notification
{
    use Queueable;

    protected $zipFilePath;

    public function __construct($zipFilePath)
    {
        $this->zipFilePath = $zipFilePath;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can add 'database' or other channels
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Export is Ready!')
            ->greeting('Hello!')
            ->line('Your export of contacts is complete and ready for download.')
            ->line('You can download the exported file using the button below:')
            ->action('Download Export', asset($this->zipFilePath))
            ->line('Thank you for using our application!');
    }
}
