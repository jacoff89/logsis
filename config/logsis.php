<?php

return [
    'rabbit_mq_queue_name' => env('RABBIT_MQ_QUEUE_NAME', 'queue1'),
    'rabbit_mq_host' => env('RABBITMQ_HOST', '127.0.0.1'),
    'rabbit_mq_port' => env('RABBITMQ_PORT', '5672'),
    'rabbit_mq_login' => env('RABBITMQ_LOGIN', 'guest'),
    'rabbit_mq_password' => env('RABBITMQ_PASSWORD', 'guest')
];
