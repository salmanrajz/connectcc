<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class main_data_manager_assigner extends Model
{
    //
    protected $fillable = [
        'number_id','manager_id','call_center'
    ];
}
