<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $fillable = [
        'year',
        'previous_year',
        'current_year',
        'previous_quarter',
        'code'
    ];

    public $casts = [
        "previous_quarter" => 'float',
        "current_quarter" => 'float',
        "previous_year" => 'float',
        "earning_per_share" => 'float'
    ];
}
