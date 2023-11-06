<?php

namespace App\Exports;

use App\numberdetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DownloadAvailableNumber implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = numberdetail::select('numberdetails.number','numberdetails.passcode','numberdetails.status','numberdetails.type','numberdetails.call_center', 'numberdetails.identity', 'numberdetails.region', 'numberdetails.created_at', 'numberdetails.updated_at','numberdetails.channel_type','numberdetails.newlist')
            // ->whereIn('channel_type',['ExpressDial'])
            // ->where("created_at", ">", \Carbon\Carbon::now()->subMonths(6))
            // ->whereIn('status',['Available','Reserved','Hold', 'Special Hide - Do not touch it Boss Said'])
            // ->where('status','!=','Active')
            // ->whereYear()
        ->whereYear('numberdetails.created_at', Carbon::now()->year)
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
            'Channel Type',
            'New List'
        ];
    }
}
