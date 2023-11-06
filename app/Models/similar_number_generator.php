<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class similar_number_generator extends Model
{
    //
    protected $fillable = [
        'number_id','number','generated_number','userid','remarks'
    ];
}
