<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveStock extends Model
{
    public $casts = [
        'sn'=>'int',
        'ltp'=>'float',
        'ltv'=>'float',
        'open'=>'float',
        'point_change'=>'float',
        'percentage_change'=>'float',
        'high'=>'float',
        'low'=>'float',
        'volume'=>'float',
        'previous_closing'=>'float',
    ];
}
