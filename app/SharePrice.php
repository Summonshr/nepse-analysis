<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharePrice extends Model
{

    public $appends = [
        'month'
    ];

    public $casts =[
        'closing_price'=>'float',
        'amount'=>'float',
        'no_of_transaction'=>'float',
        'traded_shares'=>'float',
        'max_price'=>'float',
        'min_price'=>'float'
    ];

    public function getMonthAttribute(){
        return \Carbon\Carbon::parse($this->date)->format('F');
    }
}
