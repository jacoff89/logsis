<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Получение 'томозящих методов'
     *
     * @param datetime $startDate
     * @param datetime $endDate
     * @param int $limit
     * @return Collection
     */
    public function getLongMethods($startDate, $endDate, $limit = 10)
    {
        return $this->select('controller_name', 'method_name')
            ->selectRaw('MAX(execution_time) as execution_time')
            ->whereBetween('create_date', [$startDate, $endDate])
            ->groupBy('controller_name', 'method_name')
            ->orderByDesc('execution_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Получение 'томозящих IP'
     *
     * @param datetime $startDate
     * @param datetime $endDate
     * @param bool $isInTheList
     * @param int $limit
     * @return Collection
     */
    public function getLongIps($startDate, $endDate, $isInTheList, $limit = 10)
    {
        $query = $this->select('ip_address')
            ->selectRaw('MAX(execution_time) as execution_time');

        if ($isInTheList) {
            $query->addSelect('list_of_ips.list')
                ->join('list_of_ips', 'execution_time_logs.ip_address', '=', 'list_of_ips.ip');
        } else {
            $query->leftJoin('list_of_ips', 'execution_time_logs.ip_address', '=', 'list_of_ips.ip')
                ->whereNull('list_of_ips.ip');
        }

        $query->whereBetween('create_date', [$startDate, $endDate])
            ->groupBy('ip_address')
            ->orderByDesc('execution_time')
            ->limit($limit);

        return $query->get();
    }
}
