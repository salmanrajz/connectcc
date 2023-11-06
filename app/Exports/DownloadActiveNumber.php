<?php

namespace App\Exports;

use App\numberdetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DownloadActiveNumber implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = numberdetail::select('numberdetails.number', 'numberdetails.passcode', 'numberdetails.status', 'numberdetails.type', 'numberdetails.call_center', 'numberdetails.identity', 'numberdetails.region', 'numberdetails.created_at', 'numberdetails.updated_at')->where('channel_type', 'TTF')
        ->whereIn('status', ['Active'])
        ->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'Number',
            'Pass Code',
            'Status',
            'Type',
            'Call Center',
            'Keyword',
            'Region',
            'Uploaded Date',
            'Last Update Number',
        ];
    }
}
