<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shorten_url extends Model
{
    //
    protected $fillable = [
        'code', 'link'
    ];
}
