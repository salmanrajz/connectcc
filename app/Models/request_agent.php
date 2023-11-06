<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class request_agent extends Model
{
    //
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'status', 'agent_code','profile','cnic_front','cnic_back',
];
}
