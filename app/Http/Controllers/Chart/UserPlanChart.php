<?php
namespace App\Http\Controllers\Chart;

use App\Models\User;
use vitopedro\chartjs\PieChart;
use Illuminate\Support\Facades\DB;

class UserPlanChart
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $records = User::select(['id', 'plan_id', DB::raw('count(*) as total')])
            ->groupBy('plan_id')
            ->with('plan')
            ->get();
        $labels = [];
        $data = [];
        foreach ($records as $record) {
            $labels[] = $record->plan->name;
            $data[] = $record->total;
        }
        $pie = new PieChart();
        $pie->setLabels($labels);
        $pie->setSeries([
            [
                'label' => 'Users',
                'data'  => $data,
            ],
        ]);
        return $pie;
    }
}
