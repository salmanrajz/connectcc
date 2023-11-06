<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class itproductplans extends Model
{
    //
    protected $fillable = [
        'name',
        'type',
        'pricing',
        'description',
        'status',
    ];
}
