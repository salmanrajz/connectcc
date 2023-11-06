<?php

namespace App\Exports;

use App\lead_sale;
use App\numberdetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadAvailableNumber implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = numberdetail::select('numberdetails.number', 'numberdetails.type', 'numberdetails.passcode', 'numberdetails.updated_at', \DB::raw('COUNT(lead_sales.selected_number) as number_of_used'))
        ->leftjoin("lead_sales", \DB::raw("FIND_IN_SET(numberdetails.number,lead_sales.selected_number)"), ">", \DB::raw("'0'"))
        ->where('numberdetails.status', 'Available')
        ->groupBy('lead_sales.selected_number')->get();
    }
    //
    //
    public function headings(): array
    {
        return [
            // 'S#',
            'Number',
            'Type',
            'Pass Code',
            'Last Updated At',
            'Number of Count',
        ];
    }
}
