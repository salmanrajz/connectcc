<?php

namespace App\Exports;

use App\activation_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyActivation implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = activation_form::select('activation_forms.customer_name','activation_forms.customer_number','users.name as agent_name','users.agent_code', 'activation_forms.emirate_location', 'activation_forms.activation_selected_no','activation_forms.gender','activation_forms.nationality','plans.plan_name', 'plans.revenue','plans.monthly_payment','activation_forms.activation_date', 'activation_forms.activation_sr_no')
        ->LeftJoin(
            'plans','plans.id','activation_forms.select_plan'
        )
        ->LeftJoin(
            'users','users.id','activation_forms.saler_id'
        )
        ->whereDate('activation_forms.created_at', Carbon::today())->get();

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
            'Plan Name',
            'Plan Revenue',
            'Plan Monthly Payment',
            'Activation Date','SR Number'
        ];
    }
}
