<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activation_form extends Model
{
    //
    protected $fillable = [
        'cust_id',
        'lead_no',
        'lead_id',
        'verification_id',
        'customer_name',
        'customer_number',
        'age',
        'gender',
        'nationality',
        'language',
        'original_emirate_id',
        'emirate_number',
        'additional_documents',
        'sim_type',
        'number_commitment',
        'contract_commitment',
        'selected_number',
        'flexible_minutes',
        'data',
        'local_minutes',
        'preffer_number_allowed',
        'free_minutes_to_preffered',
        'monthly_plan',
        'device_name',
        'device_duration',
        'device_payment',
        'select_plan',
        'benefits',
        'total',
        'monthly_payment',
        'emirate_location',
        'verify_agent',
        'remarks',
        'pay_status',
        'pay_charges',
        'activation_date',
        'activation_sr_no',
        'activation_service_order',
        'activation_selected_no',
        'activation_sim_serial_no',
        'activation_emirate_expiry',
        'activation_sold_by',
        'emirate_id_front',
        'emirate_id_back',
        'activation_screenshot',
        'saler_id',
        'later',
        'recording',
        'assing_to',
        'backoffice_by',
        'cordination_by',
        'date_time',
        'status','channel_type','is_prepaid'
    ];
}
