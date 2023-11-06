<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhatsAppScan extends Model
{
    //
    protected $fillable = [
        'wapnumber','start','end', 'count_digit','is_aamt'
    ];
}
