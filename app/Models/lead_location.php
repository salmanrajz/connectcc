<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lead_location extends Model
{
    //
    protected $fillable = [
        'lead_id',
        'location_url',
        'lat',
        'lng',
        'assign_to',
        'status',
    ];
}
