<?php

namespace App\Listeners;

use App\Events\TestingUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use DateTime;
use Throwable;

class TestingUpdateListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine the time at which the listener should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addSeconds(10);
    }

    /**
     * Get the name of the listener's queue connection.
     */
    public function viaConnection(): string
    {
        return config('api-config.event_default.connection');
    }
    
    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return config('api-config.event_default.queue');
    }
    
    /**
     * Get the number of seconds before the job should be processed.
     */
    public function withDelay(TestingUpdateEvent $event): int
    {
        // return $event->highPriority ? 0 : 60;
        return 0;
    }

    /**
     * Handle the event.
     */
    public function handle(TestingUpdateEvent $event): void
    {
        //
        if ($event->testing && $event->testing->users_count > 0) {
            Log::stack(['daily'])->info('TestingUpdateListener data: '. json_encode($event));
            $this->delete();
            Log::stack(['daily'])->info('TestingUpdateListener deleted.');
        }
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TestingUpdateEvent $event): bool
    {
        return $event->testing->users_count >= 50;
        // return false;
    }

    /**
     * Handle a job failure.
     */
    public function failed(TestingUpdateEvent $event, Throwable $exception): void
    {
        Log::stack(['daily'])->error('TestingUpdateListener failed: '.$exception->getMessage());
    }
}
