<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class channel_partner extends Model
{
    //
    protected $fillable = [
        'name','type','status','secondary_status'
    ];
}
