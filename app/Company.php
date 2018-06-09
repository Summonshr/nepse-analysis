<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public $casts = ['profile' => 'json'];

    public function history()
    {
        return $this->hasMany(SharePrice::class, 'company', 'name');
    }

    public function dividends()
    {
        return $this->hasMany(Dividend::class, 'code', 'code');
    }

    public function toApi()
    {
        $array = $this->toArray();
        $history = $this->history()->select('id', 'closing_price', 'date')->get();
        $monthly_analysis = $history->groupBy('month')->map(function ($month) {
            return ['month' => $month->first()->month, 'value' => ceil($month->avg('closing_price'))];
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
}
