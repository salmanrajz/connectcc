<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class later_leads extends Model
{
    //
    protected $fillable = [
        'lead_no','userid','username','later_date','status'
    ];
}
