<?php

namespace App\Models;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;

class RabbitMQ
{
    public function send($message)
    {
        $connection = new AMQPStreamConnection('192.168.XXX.XXX', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('queue1', false, false, false, false);


        $msg = new AMQPMessage('Hello World!'.rand(1, 1000));
        $channel->basic_publish($msg, '', 'queue1');

        echo ' [x] Sent '.$msg->body."\n<br />";

        $channel->close();
        $connection->close();
    }

    public function receive()
    {
        $connection = new AMQPStreamConnection('192.168.XXX.XXX', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('queue1', false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg)
        {
            DB::insert('insert into users (controller_name, execution_time, date) values (?, ?, ?)', [1, 'Marc', '1']);

            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume('queue1', '', false, true, false, false, $callback);

        while (count($channel->callbacks))
        {
            $channel->wait();
        }
    }
}
