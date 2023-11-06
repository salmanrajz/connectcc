<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class old_lead_numbers extends Model
{
    //
    protected $fillable = [
        'leadid','agent_id','old_numbers','old_plans','old_pay_status','old_pay_charges'
    ];
}
