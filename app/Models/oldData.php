<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oldData extends Model
{
    //
    protected $fillable = [
        'name', 'contact_no', 'alternative_contact_no','country','front_id','back_id','status','user_id','plan', 'activation_date','audio'
    ];
}
