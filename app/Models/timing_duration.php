<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class timing_duration extends Model
{
    //
    protected $fillable = [
        'lead_no',
        'lead_generate_time',
        'lead_accept_time',
        'lead_proceed_time',
        'sale_agent',
        'verify_agent',
        'status',
        // 'lead_no',
    ];
}
