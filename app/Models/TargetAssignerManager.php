<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetAssignerManager extends Model
{
    //
    protected $fillable = [
        'name','month','target','call_center_id','status'
    ];
}
