<?php

namespace App\Console\Commands;

use App\Services\WorkingWithRabbitMQ;
use Illuminate\Console\Command;

class RabbitReceiveStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit-receive:start {queue_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск получения сообщений RabbitMQ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $queue_name = $this->argument('queue_name');

        $rabbitMQ = new WorkingWithRabbitMQ($queue_name);
        $rabbitMQ->receive();
    }

}
