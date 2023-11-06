<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dnc_checker_log extends Model
{
    //
    protected $fillable = [
        'ip_address', 'FullAd', 'lat', 'lng','userid'
    ];
}
