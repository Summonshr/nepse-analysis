<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
{

    public $casts = [
        'dividend' => 'float',
        'type' => 'string',
        'distribution_date' => 'string',
    ];


}
