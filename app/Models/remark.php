<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class remark extends Model
{
    //
    protected $fillable = [
        'remarks',
        'lead_status',
        'lead_id',
        'remarks',
        'lead_no',
        'date_time',
        'user_agent',
        'source',
        'user_agent_id',

    ];
}
