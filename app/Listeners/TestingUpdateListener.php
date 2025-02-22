<?php

namespace App\Listeners;

use App\Events\TestingUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class TestingUpdateListener implements ShouldQueueAfterCommit
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the queued listener.
     *
     * @var int
     */
    public $backoff = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Calculate the number of seconds to wait before retrying the queued listener.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [1, 5, 10];
    }

    /**
     * Get the name of the listener's queue connection.
     */
    public function viaConnection(): string
    {
        return 'rabbitmq'; //'redis';
    }
    
    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return env('RABBITMQ_QUEUE'); //'default';
    }
    
    /**
     * Get the number of seconds before the job should be processed.
     */
    public function withDelay(TestingUpdateEvent $event): int
    {
        // return $event->highPriority ? 0 : 60;
        return 10;
    }

    /**
     * Handle the event.
     */
    public function handle(TestingUpdateEvent $event): void
    {
        //
        // if (true) {
        if (count($event->data) > 0) {
            $this->release(30);
        }
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TestingUpdateEvent $event): bool
    {
        // return $event->data->subtotal >= 5000;
        return false;
    }

    /**
     * Handle a job failure.
     */
    public function failed(TestingUpdateEvent $event, Throwable $exception): void
    {
        \Log::error('TestingUpdateListener failed: '.$exception->getMessage());
    }
}
