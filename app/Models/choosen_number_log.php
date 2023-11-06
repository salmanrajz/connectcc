<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class choosen_number_log extends Model
{
    //
    protected $fillable = [
        'number_id',
        'user_id',
        'agent_group',
    ];
}
