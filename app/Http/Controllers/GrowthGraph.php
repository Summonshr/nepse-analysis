<?php

namespace App\Http\Controllers;
use App\SharePrice;

class GrowthGraph extends Controller
{
    public function __invoke()
    {
        return SharePrice::with('company')->latest()->take(7000)->select(['company', 'date', 'closing_price'])->get()->groupBy('company')->map(function ($group, $key) {
            $last = 0;
            if ($group->count() < 20) {
                return false;
            }
            $values = $group->pluck('date', 'closing_price')->sort()->flip()->map(function ($single) use (&$last) {
                if ($last > $single) {
                    $last = $single;
                    return 'L';
                }
                $last = $single;
                return 'G';
            });
            $count = array_count_values($values->values()->toArray());
            return ['count' => $count, 'values' => $values, 'name' => $key, 'code' => data_get($group->first()->getRelationValue('company'), 'code')];
        })->filter(function ($each) {
            return $each['count']['G'] >= 10 && $each['count']['G'] >= $each['count']['L'];
        })->sortByDesc('count.G')->values();
    }
}
