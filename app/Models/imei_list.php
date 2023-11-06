<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class imei_list extends Model
{
    //
    protected $fillable =  [
        'name', 'imei_number','status'
    ];
}
