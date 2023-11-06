<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class elife_log extends Model
{
    //
    protected $fillable = [
        'userid','number_id','remarks','status','identify'
    ];
}
