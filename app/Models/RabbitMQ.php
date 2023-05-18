<?php

namespace App\Models;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;

class RabbitMQ
{
    private $channel;

    private $queue_name;

    public function __construct(string $queue_name)
    {
        $this->queue_name = $queue_name;

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', '127.0.0.1'),
            env('RABBITMQ_PORT', '5672'),
            env('RABBITMQ_LOGIN', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $this->channel = $connection->channel();

        $this->channel->queue_declare($queue_name, false, false, false, false);
    }

    public function send(string $message): void
    {
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', 'queue1');

        if (env('APP_DEBUG'))
            app('log')->debug($msg->body);

        $this->channel->close();
        $connection->close();
    }

    public function receive(): void
    {
        echo " [*] RabbitMQ запущен. Ожидается прием сообщений. Для выхода нажмите CTRL+C\n";

        $callback = function ($msg) {
            $data = json_decode($msg->body);

            $date = $data->date;
            $controller_name = $data->controller_name;
            $execution_time = $data->execution_time;
            $method_name = $data->method_name;

            DB::insert('insert into execution_time_log (controller_name, method_name, execution_time, date) values (?, ?, ?, ?)', [$controller_name, $method_name, $execution_time, $date]);

            if (env('APP_DEBUG'))
                echo ' [x] Сообщение: ', $msg->body, "\n";
        };

        $this->channel->basic_consume($this->queue_name, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
