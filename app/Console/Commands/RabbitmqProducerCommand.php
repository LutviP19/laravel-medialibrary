<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use function Laravel\Prompts\text;
use App\Customs\EncryptionCustom;

class RabbitmqProducerCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rabbitmq-producer-command {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to produce RabbitMQ message';

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        $data = [
            ['id' => 1, 'title' => fake()->name(), 'contents' => fake()->text(100)],
            ['id' => 2, 'title' => fake()->name(), 'contents' => fake()->text(100)],
            ['id' => 3, 'title' => fake()->name(), 'contents' => fake()->text(100)],
        ];

        $default = 'Hello World';
        $default = json_encode($data);
        return [
            'message' => fn () => text(
                label: 'Type message want you send?',
                placeholder: 'E.g. Hello World',
                default: $default,
                hint: 'This will be displayed on your profile.'
            ),
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = EncryptionCustom::encrypt($this->argument('message'));

        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();

        $channel->exchange_declare(env('RABBITMQ_QUEUE'), 'fanout', false, false, false);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, env('RABBITMQ_QUEUE'));
        echo ' [x] Sent: ', EncryptionCustom::decrypt($message), "\n";

        $channel->close();
        $connection->close();

        $this->info("Sending message to RabbitMQ: {$message}");
    }
}
