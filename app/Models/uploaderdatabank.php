<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class uploaderdatabank extends Model
{
    //
    protected $table = 'uploaderdatabank';
    protected $fillable = [
        'name', 'number', 'alternative_number', 'area', 'emirates', 'prefix_number', 'prefix_an', 'mark_dnd', 'mark_soft_dnd', 'status_1', 'assigned_status'
    ];
}
