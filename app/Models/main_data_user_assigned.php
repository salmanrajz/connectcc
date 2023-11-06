<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class main_data_user_assigned extends Model
{
    protected $table = 'main_data_user_assigners';
    //
    protected $fillable = [
        'number_id','user_id','call_center','status','mark_dnd','mark_soft_dnd'
    ];
}
