<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class whatsapp_number extends Model
{
    //
    protected $fillable = [
        'number','country_number','userid','session_id', 'language','status'
    ];
}
