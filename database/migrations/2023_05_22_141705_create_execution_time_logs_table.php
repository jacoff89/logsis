<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutionTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execution_time_logs', function (Blueprint $table) {

            $table->id();
            $table->string('controller_name', 100)
                ->charset('utf8mb4')
                ->collation('utf8mb4_unicode_ci');
            $table->string('method_name', 50)
                ->charset('utf8mb4')
                ->collation('utf8mb4_unicode_ci');
            $table->float('execution_time', 4, 2);
            $table->dateTime('create_date')->index();
            $table->ipAddress('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('execution_time_logs');
    }
}
