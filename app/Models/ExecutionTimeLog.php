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
     * @param datetime  $startDate
     * @param datetime  $endDate
     * @param int  $limit
     * @return Collection
     */
    public function getLongMethods($startDate, $endDate, $limit = 10)
    {
        return $this->select('controller_name', 'method_name')
            ->selectRaw('MAX(execution_time) as execution_time')
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate)
            ->groupBy('controller_name', 'method_name')
            ->orderByDesc('execution_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Получение 'томозящих IP'
     *
     * @param datetime  $startDate
     * @param datetime  $endDate
     * @param bool  $isInTheList
     * @param int  $limit
     * @return Collection
     */
    public function getLongIps($startDate, $endDate, $isInTheList, $limit = 10)
    {
        if ($isInTheList) {
            $extracting = function ($query) {
                $query->whereNotNull('ip_black_lists.ip')
                    ->orWhereNotNull('ip_white_lists.ip');
            };
        } else {
            $extracting = function ($query) {
                $query->whereNull('ip_black_lists.ip')
                    ->whereNull('ip_white_lists.ip');
            };
        }

        return $this->leftJoin('ip_black_lists', function ($join) {
            $join->on('execution_time_logs.ip_address', '=', 'ip_black_lists.ip');
        })
            ->leftJoin('ip_white_lists', 'execution_time_logs.ip_address', '=', 'ip_white_lists.ip')
            ->select('ip_address')
            ->selectRaw('MAX(execution_time) as execution_time')
            ->selectRaw('(ip_black_lists.ip IS NOT NULL) AS is_black_lists')
            ->selectRaw('(ip_white_lists.ip IS NOT NULL) AS is_white_lists')
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate)
            ->where($extracting)
            ->groupBy('ip_address')
            ->orderByDesc('execution_time')
            ->limit($limit)
            ->get();
    }
}
