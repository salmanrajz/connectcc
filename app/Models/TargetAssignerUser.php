<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetAssignerUser extends Model
{
    //
    protected $fillable = [
        'name','month','target','user','status'
    ];
}
