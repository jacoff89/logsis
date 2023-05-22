<?php

namespace App\Services;

use App\Models\ExecutionTimeLog;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;

class WorkingWithRabbitMQ
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
        $this->channel->basic_publish($msg, '', $this->queue_name);

        if (env('APP_DEBUG')) {
            app('log')->debug($msg->body);
        }

        $this->channel->close();
        //$connection->close();
    }

    public function receive(): void
    {
        echo " [*] WorkingWithRabbitMQ запущен. Ожидается прием сообщений. Для выхода нажмите CTRL+C\n";

        $callback = function (object $msg) {
            $data = json_decode($msg->body);

            $create_date = $data->create_date;
            $controller_name = $data->controller_name;
            $execution_time = $data->execution_time;
            $method_name = $data->method_name;
            $ip_address = $data->ip_address;

            $log = new ExecutionTimeLog;

            $log->controller_name = $controller_name;
            $log->method_name = $method_name;
            $log->execution_time = $execution_time;
            $log->create_date = $create_date;
            $log->ip_address = $ip_address;

            $log->save();

            if (env('APP_DEBUG')) {
                echo ' [x] Сообщение: ', $msg->body, "\n";
            }
            $msg->ack();
        };

        $this->channel->basic_consume($this->queue_name, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
