<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class additional_salary extends Model
{
    //
    protected $fillable = [
        'userid','manager_id','comments','inc_amount','dec_amount','salary_month'
    ];
}
