<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class iprecord extends Model
{
    //
    protected $fillable = [
        'ip', 'location'
    ];
}
