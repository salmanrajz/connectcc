<?php

namespace App\Exports;

use App\activation_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyActivation implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = activation_form::select('activation_forms.customer_name','activation_forms.lead_no','activation_forms.pay_status', 'activation_forms.customer_number', 'users.name as agent_name','users.email as email', 'users.agent_code', 'activation_forms.emirate_location', 'activation_forms.activation_selected_no', 'activation_forms.gender', 'activation_forms.nationality','activation_forms.sim_type', 'plans.plan_name',  'activation_forms.activation_date', 'activation_forms.activation_sr_no','activation_forms.status','numberdetails.type', 'activation_forms.language', 'activation_forms.channel_type','activation_forms.updated_at','is_prepaid', 'numberdetails.channel_type as ct', 'plans.revenue')
        // return $data = activation_form::select(\DB::raw("YEAR(activation_forms.created_at) year"), \DB::raw("MONTH(activation_forms.created_at) month"),'activation_forms.activation_date', 'activation_forms.activation_sr_no','activation_forms.customer_name','plans.plan_name', 'activation_forms.activation_selected_no','activation_forms.channel_type')
        ->LeftJoin(
            'plans',
            'plans.id',
            'activation_forms.select_plan'
        )
        ->LeftJoin(
            'users',
            'users.id',
            'activation_forms.saler_id'
        )
        // ->LeftJoin(
        //     'audio_recordings','audio_recordings.lead_no','activation_forms.lead_id'
        // )
        ->LeftJoin(
            'numberdetails','numberdetails.number','activation_forms.activation_selected_no'
        )
        ->whereIn('activation_forms.channel_type', ['TTF','ExpressDial','MWH','IdeaCorp'])
        // ->whereIn('activation_forms.channel_type', ['TTF'])
        // ->whereIn('activation_forms.channel_type', ['MWH'])
        // ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
        // ->where('users.agent_code','AAMT')
        // ->where('is_prepaid','1')
        ->where('users.agent_code','CC4')
        // ->get();
        // ->wherein('users.agent_code',['CC11'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            // ->get();

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get();

            // ->whereDate('activation_forms.created_at', Carbon::today())->get();

        // ->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'Customer Name',
            'Lead No',
            'Pay Charges',
            'Customer Number',
            'Agent Name',
            'Email ',
            'Call Center',
            'Emirates',
            'Selected Number',
            'Gender',
            'Nationality',
            'Sim Type',
            'Plan Name',
            'Activation Date', 'SR Number','status','Number Type','Language','Channel Partner','Last Update', 'is_prepaid','Channel Type'
        ];
    }
}
