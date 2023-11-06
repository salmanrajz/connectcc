<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class numberdetail extends Model
{
    //
    protected $fillable = [
        'number',
        'passcode',
        'remarks',
        'type',
        'channel_type',
        'book_type','old_status','status','call_center', 'identity','region','non_c','personal_status', 'newlist'
    ];
}
