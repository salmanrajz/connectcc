<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class verification_form extends Model
{
    //
    protected $fillable = [
        'cust_id',
        'lead_no',
        'lead_id',
        'customer_name',
        'gender',
        'emirate_location',
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
        'emirate_number',
        'etisalat_number',
        'additional_documents',
        'mobile_payment',
        'date_time',
        'date_time_follow',
        'share_with',
        'pre_check_remarks',
        'pre_check_agent',
        'original_emirate_id',
        'verify_agent',
        'assing_to',
        'cordination_by',

    ];
}
