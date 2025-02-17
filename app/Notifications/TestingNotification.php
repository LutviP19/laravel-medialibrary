<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $testing;


    /**
     * Create a new notification instance.
     */
    public function __construct($testing)
    {
        //
        $this->testing = $testing;
        
        $this->afterCommit();

        //dd($this->testing);
    }

    /**
     * Determine which connections should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaConnections(): array
    {
        return [
            'mail' => 'redis',
            'database' => 'sync',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/api/testing/'.$this->testing->id);
 
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line($this->testing->name)
                    ->lineIf(strlen($this->testing->name) > 0, "{$this->testing->description}")
                    ->action('View Detail', $url)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'notif_id' => $this->testing->id,
            'name' => $this->testing->name,
            'description' => $this->testing->description,
            // 'image' => $this->testing->image,
        ];
    }
}
