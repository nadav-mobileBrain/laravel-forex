<?php

namespace App\Http\Controllers\Chart;

use App\Models\Signal;
use vitopedro\chartjs\LineChart;
use vitopedro\chartjs\ColumnChart;
use Illuminate\Support\Facades\Auth;

class UserSymbolPipsChart
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $query = Signal::with('symbol');
        $planId = Auth::user()->plan_id;
        $query->where('created_at', '>=', now()->subDays(15)->format('Y-m-d'));
        $query->where('plan_ids', 'LIKE', "%{$planId}%");
        $collection = $query->get();
        $data = $collection
            ->groupBy(function ($entry) {
                return $entry->symbol->name;
            })
            ->map(function ($entries) {
                $win = 0;
                $loss = 0;
                $entries->each(function ($entry) use (&$win, &$loss) {
                    if ($entry->win == 1) {
                        $win += $entry->pips;
                    }
                    if ($entry->win == 2) {
                        $loss += $entry->pips;
                    }
                });
                return [
                    'win'  => $win,
                    'loss' => $loss,
                ];
            });

        $winData = [];
        $lossData = [];
        foreach ($data as $d) {
            $winData[] = $d['win'];
            $lossData[] = $d['loss'];
        }

        $line = new LineChart();
        $line = new ColumnChart();
        $line->setLabels($data->keys());
        $line->setSeries([
            [
                'label' => 'Win Pips',
                'data'  => $winData,
            ],
            [
                'label' => 'Loss Pips',
                'data'  => $lossData,
            ],
        ]);
        return $line;
    }
}
