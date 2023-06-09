<?php

namespace App\Services;

use App\Models\ExecutionTimeLog;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class WorkingWithRabbitMQ
{
    private $channel;

    private $queueName;

    public function __construct(string $queueName)
    {
        $this->queueName = $queueName;

        $connection = new AMQPStreamConnection(
            config('rabbit_mq.rabbit_mq_host'),
            config('rabbit_mq.rabbit_mq_port'),
            config('rabbit_mq.rabbit_mq_login'),
            config('rabbit_mq.rabbit_mq_password')
        );

        $this->channel = $connection->channel();

        $this->channel->queue_declare($queueName, false, false, false, false);
    }

    public function send(string $message): void
    {
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $this->queueName);

        if (env('APP_DEBUG')) {
            app('log')->debug($msg->body);
        }

        $this->channel->close();
    }

    public function receive(): void
    {
        echo __('messages.rabbit_mq_starting');

        $callback = function (object $msg) {
            $data = json_decode($msg->body);

            $createDate = $data->createDate;
            $controllerName = $data->controllerName;
            $executionTime = $data->executionTime;
            $methodName = $data->methodName;
            $ipAddress = $data->ipAddress;

            $log = new ExecutionTimeLog;
            $log->controller_name = $controllerName;
            $log->method_name = $methodName;
            $log->execution_time = $executionTime;
            $log->create_date = $createDate;
            $log->ip_address = $ipAddress;
            $log->save();

            if (env('APP_DEBUG')) {
                echo __('messages.rabbit_mq_message'), $msg->body, "\n";
            }
            $msg->ack();
        };

        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
