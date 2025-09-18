<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Str;
use App\Customs\EncryptionCustom;

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

    protected $id;

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

        $this->id = rand(1,3);
        echo " [*] Waiting for messages ID $this->id. To exit press CTRL+C\n";

        // Consume body
        $callback = function ($msg) {
            $body = EncryptionCustom::decrypt($msg->getBody());

            if(Str::isJson($body)) {
                $data = json_decode($body, true);
                $date = date('d-m-Y H:i:s');

                echo ' [x] ', $date, "\n";
                foreach($data as $key => $val){
                    if(is_array($val)) {
                        // Filtered ID
                        if(isset($val['id']) && 
                            $val['id'] === $this->id) {
                            
                            foreach($val as $k => $v) {                            
                                echo "$k: $v\n";
                            }
                        }
                    }
                    else
                        echo "$key: $val\n";
                }
                
                echo "=====\n";
            }
            else {
                echo ' [x] ', $body, "\n";
                // \Log::info('RabbitMQ artisan message received: '.$msg->getBody());
            }            
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
