<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attendance_management extends Model
{
    //
    protected $fillable = [
        'userid','date','timing','status','created_at','status','mobile_status'
    ];
}
