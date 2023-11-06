<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MyPreviousReport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    //
    protected $id;
    function __construct($id)
    {
        $this->id = $id;
    }
    //
    public function collection()
    {
        //
        return $data = \App\lead_sale::select('lead_sales.lead_no','lead_sales.customer_name', 'lead_sales.customer_number', 'users.name', 'users.agent_code', 'lead_sales.emirates', 'lead_sales.selected_number', 'lead_sales.sim_type', 'lead_sales.gender', 'lead_sales.nationality', 'plans.plan_name','lead_sales.remarks', 'status_codes.status_name', 'lead_sales.created_at','lead_sales.channel_type','lead_sales.sr_number','lead_sales.language')
        ->Join(
            'users',
            'users.id',
            'lead_sales.saler_id'
        )
        // ->leftjoin(
        //     'plans','plans.id','lead_sales.select_plan'
        // )
        ->leftjoin("plans", \DB::raw("FIND_IN_SET(plans.id,lead_sales.select_plan)"), ">", \DB::raw("'0'"))
        ->Join(
            'status_codes',
            'status_codes.status_code',
            'lead_sales.status'
        )
        ->whereIn('lead_sales.status',['1.15','1.03'])
            // ->where('lead_sales.channel_type','IdeaCorp')
        // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06','1.03','1.01',''])
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

        // ->where('users.agent_code', $this->id)
        ->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'lead_no',
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
            'Customer Remarks',
            'Status',
            'Lead Generated Date','Status','language'
        ];
    }
}
