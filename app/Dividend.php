<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
{

    public $casts = [
        'total_dividend' => 'float',
        'bonus_share' => 'float',
        'cash_dividend' => 'float',
        'right_share' => 'float',
    ];

    public function getCashDividendAttribute($value)
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        return 0;
    }


    public function getBonusShareAttribute($value)
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        return 0;
    }

    public function getTotalDividendAttribute($value)
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        return 0;
    }

    public function getRightShareAttribute($value)
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        return 0;
    }

    public function toApi()
    {
        return [
            'cash_dividend' => $this->cash_dividend,
            'bonus_share' => $this->bonus_share,
            'total_dividend' => $this->total_dividend,
            'right_share' => $this->right_share,
            'bonuse_distribution_date' => $this->bonuse_distribution_date,
        ];
    }
}
