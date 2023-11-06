<?php

namespace App\Exports;

use App\activation_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class previousnonprepaidact implements FromCollection
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
        return $data = activation_form::select('activation_forms.customer_name', 'activation_forms.customer_number', 'users.name as agent_name', 'users.agent_code', 'activation_forms.emirate_location', 'activation_forms.activation_selected_no', 'numberdetails.type', 'activation_forms.gender', 'activation_forms.nationality', 'plans.plan_name')
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
            // ->where('users.agent_code', $this->id)
            ->where('activation_forms.is_prepaid',0)
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())->get();

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
            'Sim Type',
            'Gender',
            'Nationality',
            'Plan Name',
        ];
    }
}
