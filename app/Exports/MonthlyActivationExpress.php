<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\activation_form;
use Carbon\Carbon;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyActivationExpress implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = activation_form::select('activation_forms.customer_name', 'activation_forms.customer_number', 'users.name as agent_name', 'users.agent_code', 'activation_forms.emirate_location', 'activation_forms.activation_selected_no', 'activation_forms.gender', 'activation_forms.nationality', 'activation_forms.sim_type', 'plans.plan_name',  'activation_forms.activation_date', 'activation_forms.activation_sr_no', 'activation_forms.status', 'numberdetails.type')
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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no'
            )
            -> where('activation_forms.channel_type', 'ExpressDial')
            // ->where('channel_type','ExpressDial')
            // ->where('is_prepaid','1')->get();
            // ->where('users.agent_code','CC4')
            // ->wherein('users.agent_code',['CC8','CC4','CC5','CC7'])
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())->get();

            ->whereMonth('activation_forms.created_at', Carbon::now()->month)->get();

        // ->whereDate('activation_forms.created_at', Carbon::today())->get();

        // ->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'Customer Name',
            'Customer Number',
            'Agent Name',
            'Call Center',
            'Emirates',
            'Selected Number',
            'Gender',
            'Nationality',
            'Sim Type',
            'Plan Name',
            'Activation Date', 'SR Number', 'status', 'Number Type'
        ];
    }
}
