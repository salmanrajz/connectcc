<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMnpBank extends Model
{
    //
    protected $fillable = [
        'number','number_id','language','status','remarks','soft_dnd','dnd','is_aamt'
    ];
}
