<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class remarks_notification extends Model
{
    //
    protected $fillable = [
        'leadid','userid','remarks','group_id','is_read','notification_type',
    ];
}
