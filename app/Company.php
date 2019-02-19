<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $appends = ['avg_dividend', 'should_invest'];

    public $casts = ['profile' => 'json'];

    public function getAvgDividendAttribute()
    {
        return $this->relationLoaded('dividends') ? $this->dividends->groupBy('type')->map(function ($group) {
            return round($group->avg('dividend'), 2);
        }) : [];
    }

    public function history()
    {
        return $this->hasMany(SharePrice::class, 'company', 'name');
    }

    public function dividends()
    {
        return $this->hasMany(Dividend::class, 'code', 'code');
    }

    public function livestock()
    {
        return $this->hasMany(LiveStock::class, 'symbol', 'code');
    }

    public function dividend($data, $type)
    {
        $this->dividends()->saveMany(collect($data)->filter()->map(function ($map) use ($type) {
            if (!$map[1] || !$map[2]) {
                return null;
            }
            $divident = new Dividend();
            $divident->type = $type;
            $divident->dividend = $map[1];
            $divident->distribution_date = $map[2];
            return $divident;
        })->filter());
    }


    public function toApi()
    {
        if (request('minimal')) {
            $this->latest_share_price = optional($this->livestock()->select('ltp')->latest()->first())->ltp;
            return array_only($this->toArray(), ['code', 'name', 'latest_share_price']);
        }

        $array = $this->toArray();

        $history = $this->history()->select('id', 'closing_price', 'date')->get();

        $monthly_analysis = $history->groupBy('month')->mapWithKeys(function ($month, $index) {

            return [substr($month->first()->month, 0, 3) => ['month' => substr($month->first()->month, 0, 3), 'value' => ceil($month->avg('closing_price'))]];

        })->values();
        $array['highest'] = $monthly_analysis->reduce(function ($carry, $item) {
            if (!$carry) {
                return $item;
            }
            return $carry['value'] > $item['value'] ? $carry : $item;
        });

        $array['lowest'] = $monthly_analysis->reduce(function ($carry, $item) {
            if (!$carry) {
                return $item;
            }
            return $carry['value'] < $item['value'] ? $carry : $item;
        });

        $array['latest_share_price'] = optional($history->sortByDesc('id')->first())->closing_price;

        return $array;
    }

    public function getShouldInvestAttribute()
    {
        return $this->dividends->groupBy('type')->map(function ($group) {
            return $group->avg('dividend') < $group->sortByDesc('distribution_date')->first()->dividend;
        });
    }
}
