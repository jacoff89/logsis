<?php

namespace App\Models;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;

class RabbitMQ
{
    public function send($message)
    {
        $connection = new AMQPStreamConnection('77.232.135.231', 5672, 'admin', 'deyshW5f');
        $channel = $connection->channel();

        $channel->queue_declare('queue1', false, false, false, false);


        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', 'queue1');

        echo ' [x] Sent '.$msg->body."\n<br />";

        $channel->close();
        $connection->close();
    }

    public function receive()
    {
        $connection = new AMQPStreamConnection('77.232.135.231', 5672, 'admin', 'deyshW5f');
        $channel = $connection->channel();

        $channel->queue_declare('queue1', false, false, false, false);

        echo " [*] RabbitMQ запущен. Ожидается прием сообщений. Для выхода нажмите CTRL+C\n";

        $callback = function ($msg)
        {
            $data = json_decode($msg->body);

            $date = $data->date;
            $controllerName = $data->controllerName;
            $executionTime = $data->executionTime;

            DB::insert('insert into execution_time_log (controller_name, execution_time, date) values (?, ?, ?)', [$controllerName, $executionTime, $date]);

            echo ' [x] Сообщение: ', $msg->body, "\n";
        };

        $channel->basic_consume('queue1', '', false, true, false, false, $callback);

        while (count($channel->callbacks))
        {
            $channel->wait();
        }
    }
}
