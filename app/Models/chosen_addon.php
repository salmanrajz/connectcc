<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chosen_addon extends Model
{
    //
    protected $fillable = [
        'lead_id',
        'addon_id',
        'status',
    ];
}
