<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rabbitmq-consumer-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to consume RabbitMQ message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queueName = env('RABBITMQ_QUEUE');
        // $queueName = 'rabbitmq';

        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();

        $channel->exchange_declare($queueName, 'fanout', false, false, false);

        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

        $channel->queue_bind($queue_name, $queueName);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] ', $msg->getBody(), "\n";
            \Log::info('RabbitMQ artisan message received: '.$msg->getBody());
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $channel->close();
        $connection->close();
    }
}
