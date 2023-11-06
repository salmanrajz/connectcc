<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedBack extends Model
{
    //
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'customer_alternative_number',
        'existing_customer',
        'status',
        'validation_code',
        'lead_date',
        'alternative_number',
        'plan_desc',
    ];
}
