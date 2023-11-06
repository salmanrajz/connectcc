<?php

namespace App\Exports;

use App\lead_sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgentReport implements FromCollection, WithHeadings
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
        // return $data = activation_form::select('activation_forms.customer_name', 'activation_forms.customer_number', 'users.name as agent_name', 'users.agent_code', 'activation_forms.emirate_location', 'activation_forms.activation_selected_no', 'numberdetails.type', 'activation_forms.gender', 'activation_forms.nationality', 'plans.plan_name')
        // ->LeftJoin(
        //     'plans',
        //     'plans.id',
        //     'activation_forms.select_plan'
        // )
        //     ->LeftJoin(
        //         'users',
        //         'users.id',
        //         'activation_forms.saler_id'
        //     )
        //     ->LeftJoin(
        //         'numberdetails',
        //         'numberdetails.number',
        //         'activation_forms.activation_selected_no'
        //     )
        //     ->where('users.agent_code', $this->id)
        //     ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())->get();
        //SELECT a.customer_name, a.customer_number,a.selected_number, c.plan_name,d.status_name,b.name from lead_sales a
        // INNER JOIN users b on b.id = a.saler_id
        // INNER JOIN plans c on c.id = a.select_plan
        // INNER JOIN status_codes d on d.status_code = a.status
        // WHERE b.id = '477'
        return $data = lead_sale::select('lead_sales.customer_name','lead_sales.customer_number','users.name','users.agent_code','lead_sales.emirates','lead_sales.selected_number','lead_sales.sim_type','lead_sales.gender','lead_sales.nationality','plans.plan_name','status_codes.status_name','lead_sales.created_at')
        ->Join(
            'users','users.id','lead_sales.saler_id'
        )
            // ->leftjoin(
            //     'plans','plans.id','lead_sales.select_plan'
            // )
        ->leftjoin("plans", \DB::raw("FIND_IN_SET(plans.id,lead_sales.select_plan)"), ">", \DB::raw("'0'"))

        ->Join(
            'status_codes','status_codes.status_code','lead_sales.status'
        )
        ->where('users.id', $this->id)->get();



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
            'Status',
            'Lead Generated Date',
        ];
    }
}
