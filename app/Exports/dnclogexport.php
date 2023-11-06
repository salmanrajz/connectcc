<?php

namespace App\Exports;

use App\dnc_checker_log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class dnclogexport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = dnc_checker_log::orderBy('id','desc')->get();

        // ->whereDate('activation_forms.created_at', Carbon::today())->get();

        // ->get();
    }
    public function headings(): array
    {
        return [
            'S#',
            'ip address',
            'Full Ad',
            'lat',
            'lang',
            'UserID',
        ];
    }
}
