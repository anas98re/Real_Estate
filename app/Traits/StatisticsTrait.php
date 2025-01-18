<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait StatisticsTrait
{
    /**
     * Get monthly order statistics.
     *
     * @param  string  $model  The model class name.
     * @param  array  $conditions  Additional query conditions (optional).
     * @return array Monthly order counts.
     */
    public function getMonthlyStatistics($model, $conditions = [])
    {
        return collect(range(1, 12))->map(function ($month) use ($model, $conditions) {
            $query = $model::query();

            if (! empty($conditions)) {
                $query = $query->where($conditions['key'], $conditions['value']);
            }

            return $query->whereMonth('created_at', $month)->count();
        })->all();
    }

    public function getWeeklyStatistics($model)
    {
        $year = date('Y');
        $weeks = [];

        for ($week = 1; $week <= 52; $week++) {
            $weeks[$week] = ['week' => $week, 'count' => 0];
        }

        $orders = $model::select(DB::raw('DATEPART(wk, created_at) as week, COUNT(*) as count'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('DATEPART(wk, created_at)'))
            ->get();

        foreach ($orders as $order) {
            $weeks[$order->week] = ['week' => $order->week, 'count' => $order->count];
        }

        return array_values($weeks);
    }

    public function getDailyStatistics($model)
    {
        $year = date('Y');
        $startOfYear = Carbon::createFromDate($year, 1, 1);
        $endOfYear = Carbon::createFromDate($year, 12, 31);

        $dates = [];

        for ($date = $startOfYear; $date->lte($endOfYear); $date->addDay()) {
            $dates[$date->format('Y-m-d')] = ['date' => $date->format('Y-m-d'), 'count' => 0];
        }

        $orders = $model::select(DB::raw('CAST(created_at AS DATE) as date, COUNT(*) as count'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->get();

        foreach ($orders as $order) {
            $dates[$order->date] = ['date' => $order->date, 'count' => $order->count];
        }

        return array_values($dates);
    }
}
