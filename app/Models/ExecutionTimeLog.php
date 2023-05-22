<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutionTimeLog extends Model
{
    use HasFactory;

    /**
     * Не обрабатывать временные метки
     *
     * @var bool
     */
    public $timestamps = false;
}
