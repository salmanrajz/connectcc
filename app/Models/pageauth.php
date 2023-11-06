<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pageauth extends Model
{
    //
    protected $fillable = [
        'userid','expires_at','code','other'
    ];
}
