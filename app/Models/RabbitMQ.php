<?php

namespace App\Models;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;

class RabbitMQ
{
    public function send(string $message): void
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', '127.0.0.1'),
            env('RABBITMQ_PORT', '5672'),
            env('RABBITMQ_LOGIN', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );
        $channel = $connection->channel();

        $channel->queue_declare('queue1', false, false, false, false);


        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', 'queue1');

        if(env('APP_DEBUG'))
            app('log')->debug($msg->body);

        $channel->close();
        $connection->close();
    }

    public function receive(): void
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', '127.0.0.1'),
            env('RABBITMQ_PORT', '5672'),
            env('RABBITMQ_LOGIN', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );
        $channel = $connection->channel();

        $channel->queue_declare('queue1', false, false, false, false);

        echo " [*] RabbitMQ запущен. Ожидается прием сообщений. Для выхода нажмите CTRL+C\n";

        $callback = function ($msg)
        {
            $data = json_decode($msg->body);

            $date = $data->date;
            $controllerName = $data->controllerName;
            $executionTime = $data->executionTime;
            $methodName = $data->methodName;

            DB::insert('insert into execution_time_log (controller_name, method_name, execution_time, date) values (?, ?, ?, ?)', [$controllerName, $methodName, $executionTime, $date]);

            if(env('APP_DEBUG'))
                echo ' [x] Сообщение: ', $msg->body, "\n";
        };

        $channel->basic_consume('queue1', '', false, true, false, false, $callback);

        while (count($channel->callbacks))
        {
            $channel->wait();
        }
    }
}
