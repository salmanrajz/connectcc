<?php

namespace App\Exports;

use App\activation_form;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyActiveNonVerifiedLead implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;
    function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        //
        return $data = activation_form::select('activation_forms.customer_name', 'activation_forms.customer_number', 'users.name as agent_name', 'users.agent_code', 'activation_forms.emirate_location', 'activation_forms.activation_selected_no', 'activation_forms.gender', 'activation_forms.nationality', 'activation_forms.sim_type', 'plans.plan_name')
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
            'lead_sales',
            'lead_sales.id',
            'activation_forms.lead_id'
        )
        ->where('lead_sales.status','1.11')
        ->where('users.agent_code', $this->id)->get();
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
            // 'Activation Date', 'SR Number'
        ];
    }
}
