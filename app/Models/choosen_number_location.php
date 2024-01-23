<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class choosen_number_location extends Model
{
    //
    protected $fillable = [
        'lead_id',
        'location_url',
        'lat',
        'lng',
        'status',
    ];
}
