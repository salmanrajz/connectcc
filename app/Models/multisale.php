<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class multisale extends Model
{
    //
    protected $fillable = [
        'customer_name',
        'gender',
        'emirates',
        'pay_status',
        'pay_charges',
        'customer_number',
        'age',
        'nationality',
        'number_commitment',
        'sim_type',
        'select_plan',
        'contract_commitment',
        'benefits',
        'lead_no',
        'selected_number',
        'device',
        'commitment_period',
        'monthly_payment',
        'total_monthly_payment',
        'status',
        'language',
        'dob',
        'later_date',
        'saler_name',
        'saler_id',
        'remarks',
        'emirate_id',
        'emirate_num',
        'etisalat_number',
        'additional_document',
        'mobile_payment',
        'date_time',
        'date_time_follow',
        'share_with',
        'pre_check_remarks',
        'pre_check_agent',
        'lead_type',
        'channel_type'
    ];
}
