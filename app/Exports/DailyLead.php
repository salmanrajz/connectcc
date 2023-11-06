<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyLead implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        // return diag_test::all();
        return $postpaid_verified = \App\User::select('lead_sales.lead_no',"lead_sales.created_at",'lead_sales.customer_name','lead_sales.customer_number','remarks.remarks', "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'remarks','remarks.lead_id','lead_sales.id'
            )
            ->whereIn('lead_sales.status',
                    ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02']
            )
            ->whereDate('lead_sales.created_at', Carbon::today())
            // ->whereDate('lead_sales.created_at', Carbon::yesterday())
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->get();
    }
    public function headings(): array
    {
        return [
            'Lead No',
            'Date',
            'Customer Name',
            'Customer Number',
            'remarks',
            'status'
            // 'Amount',
            // 'speciality',
            // 'marketer',
            // 'facebook_id',
            // 'phone',
            // 'address',
            // 'dob',
            // 'remarks'

        ];
    }
}
