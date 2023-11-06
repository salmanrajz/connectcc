<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class number_assigner extends Model
{
    //
    protected $fillable = [
        'number','manager_id','user_id','call_center','remarks','remarks_2','status'
    ];
}
