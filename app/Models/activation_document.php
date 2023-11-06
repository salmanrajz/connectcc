<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activation_document extends Model
{
    //
    protected $fillable = [
        'username',
        'document_name',
        'lead_id',
        'activation_id',
        'status',
    ];
}
