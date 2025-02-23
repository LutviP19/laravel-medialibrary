<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TestingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $testing;
    protected $notif_type;


    /**
     * Create a new notification instance.
     */
    public function __construct($testing)
    {
        //
        $this->testing = $testing;
        $this->notif_type ='testing-notification';
        
        //dd($this->testing);
        $this->afterCommit();
    }

    /**
     * Determine which connections should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaConnections(): array
    {
        return [
            // 'mail' => 'redis',
            // 'database' => 'sync',
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaQueues(): array
    {
        return [
            'rabbitmq' => env('RABBITMQ_QUEUE'),
            // 'slack' => 'slack-queue',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the notification's database type.
     *
     * @return string
     */
    public function databaseType(object $notifiable): string
    {
        return $this->notif_type;
    }

    /**
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return $this->notif_type;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/api/testing/'.$this->testing->id);
 
        return (new MailMessage)
                    ->subject('Testing Notification ' . $this->testing->id)
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
            // 'user_id' => auth()->user()->id,
            'name' => $this->testing->name,
            'description' => $this->testing->description,
            'image' => $this->testing->image,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'notif_id' => $this->testing->id,
            'user_id' => auth()->user()->id,
            'name' => $this->testing->name,
            'description' => $this->testing->description,
            'image' => $this->testing->image,
        ]);
    }

}
