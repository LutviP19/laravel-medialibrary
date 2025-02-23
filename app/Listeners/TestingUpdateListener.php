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
        return 'redis'; //'redis' | rabbitmq;
    }
    
    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return env('REDIS_QUEUE'); //env('REDIS_QUEUE') | env('RABBITMQ_QUEUE');
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
        if ($event->testing && strlen($event->testing->name) > 0) {
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
        // return $event->data->subtotal >= 5000;
        return true;
    }

    /**
     * Handle a job failure.
     */
    public function failed(TestingUpdateEvent $event, Throwable $exception): void
    {
        Log::stack(['daily'])->error('TestingUpdateListener failed: '.$exception->getMessage());
    }
}
