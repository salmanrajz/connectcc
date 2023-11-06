<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shorten_url extends Model
{
    //
    protected $fillable = [
        'code', 'link'
    ];
}
