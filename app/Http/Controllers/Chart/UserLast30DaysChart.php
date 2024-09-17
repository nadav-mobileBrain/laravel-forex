<?php

namespace App\Http\Controllers\Chart;

use Carbon\Carbon;
use App\Models\Signal;
use Carbon\CarbonPeriod;
use vitopedro\chartjs\LineChart;
use Illuminate\Support\Facades\Auth;

class UserLast30DaysChart
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $query = Signal::query();
        $planId = Auth::user()->plan_id;
        $query->where('created_at', '>=', now()->subDays(15)->format('Y-m-d'));
        $query->where('plan_ids', 'LIKE', "%{$planId}%");
        $collection = $query->get();
        $chartQuery = $collection->sortBy('created_at')
            ->groupBy(function ($entry) {
                return $entry->created_at->format('Y-m-d');
            })
            ->map(function ($entries) {
                return $entries->count();
            });
        $newData = collect([]);
        CarbonPeriod::since(now()->subDays(15))
            ->until(now())
            ->forEach(function (Carbon $date) use ($chartQuery, &$newData) {
                $key = $date->format('Y-m-d');
                $newData->put($key, $chartQuery[$key] ?? 0);
            });

        $chartQuery = $newData;
        $newData = collect([]);
        $format = 'Y-m-d';
        CarbonPeriod::since(now()->subDays(15))
            ->until(now())
            ->forEach(function (Carbon $date) use (&$newData, $format) {
                $key = $date->format($format);
                $newData->put($key, 0);
            });

        $line = new LineChart();
        $line->setLabels($chartQuery->keys());
        $line->setSeries([
            [
                'label' => 'Signal',
                'data'  => $chartQuery->values()->toArray(),
            ],
        ]);

        return $line;
    }
}
