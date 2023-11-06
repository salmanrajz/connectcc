<?php

namespace App\Exports;

use App\activation_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MyPreviousMonthlyActivation implements FromCollection,WithHeadings
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
        return $data = activation_form::select(
            'activation_forms.customer_name',
            // 'activation_forms.customer_number',
            'users.name as agent_name',
            'users.agent_code',
            'activation_forms.emirate_location',
            'activation_forms.activation_selected_no',
            'numberdetails.type',
            'activation_forms.gender',
            'activation_forms.nationality',
            'plans.plan_name',
            'activation_forms.created_at',
            'activation_forms.sim_type',
        )
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
        ->where('users.agent_code', $this->id)
        ->where('activation_forms.status','1.02')
        ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
        ->whereYear('activation_forms.created_at', Carbon::now()->year)
        ->get();
        // return $data = \App\lead_sale::select('lead_sales.customer_name',
        // //  'lead_sales.customer_number',
        //  'users.name', 'users.agent_code', 'lead_sales.emirates', 'lead_sales.selected_number', 'lead_sales.sim_type', 'lead_sales.gender', 'lead_sales.nationality', 'plans.plan_name', 'status_codes.status_name', 'lead_sales.created_at', 'status_codes.status_name')
        // ->Join(
        //     'users',
        //     'users.id',
        //     'lead_sales.saler_id'
        // )
        //     // ->leftjoin(
        //     //     'plans','plans.id','lead_sales.select_plan'
        //     // )
        //     ->leftjoin("plans", \DB::raw("FIND_IN_SET(plans.id,lead_sales.select_plan)"), ">", \DB::raw("'0'"))
        //     ->Join(
        //         'status_codes',
        //         'status_codes.status_code',
        //         'lead_sales.status'
        //     )
        //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
        //     ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
        //     ->whereYear('lead_sales.created_at', Carbon::now()->subyear())
        //     // ->get();
        //     // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
        //     ->where('users.agent_code', $this->id)->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'Customer Name',
            // 'Customer Number',
            'Agent Name',
            'Call Center',
            'Emirates',
            'Selected Number',
            'Sim Type',
            'Gender',
            'Nationality',
            'Plan Name',
            'Activation Date',
            'Sim Type',
        ];
    }
}
