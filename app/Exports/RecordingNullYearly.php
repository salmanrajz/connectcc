<?php

namespace App\Exports;

use App\verification_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RecordingNullYearly implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $operation = verification_form::select("lead_sales.customer_name",'lead_sales.customer_number','lead_sales.selected_number', "status_codes.status_name",'lead_sales.nationality','users.agent_code')
        // $user =  DB::table("subjects")->select('subject_name', 'id')
        ->LeftJoin(
            'timing_durations',
            'timing_durations.lead_no',
            '=',
            'verification_forms.lead_no'
        )
        ->LeftJoin(
            'remarks',
            'remarks.lead_no',
            '=',
            'verification_forms.lead_no'
        )
        ->LeftJoin(
            'status_codes',
            'status_codes.status_code',
            '=',
            'verification_forms.status'
        )
        ->LeftJoin(
            'lead_sales',
            'lead_sales.id',
            '=',
            'verification_forms.lead_no'
        )
        ->LeftJoin(
            'users',
            'users.id',
            '=',
            'lead_sales.saler_id'
        )
        ->LeftJoin(
            'lead_locations',
            'lead_locations.lead_id',
            '=',
            'lead_sales.id'
        )
        // ->LeftJoin(
        //     'audio_recordings',
        //     'audio_recordings.lead_no',
        //     '=',
        //     'lead_sales.id'
        // )
        // ->whereIn(
        //     'lead_sales.status',
        //     ['1.02']
        // )
        ->where('lead_sales.sim_type', 'New')
        ->where('lead_locations.assign_to', '!=', 136)
        ->where('lead_sales.status', '1.10')
        // ->whereNull('audio_recordings.audio_file')
        ->whereMonth('lead_sales.created_at', Carbon::now()->month)
        ->groupBy('lead_sales.id')
        ->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'Customer Name',
            'Customer Number',
            'Selected Number',
            'Status',
            'Nationality',
            'Agent Code',
        ];
    }
}
