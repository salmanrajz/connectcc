<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer_notification extends Model
{
    //
    protected $fillable = [
        'lead_id','type','userid','status'
    ];
}
