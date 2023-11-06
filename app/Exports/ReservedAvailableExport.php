<?php

namespace App\Exports;

use App\numberdetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservedAvailableExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = numberdetail::select('numberdetails.number','numberdetails.type','numberdetails.passcode','numberdetails.updated_at', \DB::raw('COUNT(numberdetails.number) as number_of_used'))
        ->Join(
            'choosen_numbers','choosen_numbers.number_id','numberdetails.id'
        )
        ->Join(
            'choosen_number_logs',
            'choosen_number_logs.number_id','numberdetails.id'
        )
        ->where('numberdetails.status','Available')
        ->groupBy('numberdetails.number')->get();
    }
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
