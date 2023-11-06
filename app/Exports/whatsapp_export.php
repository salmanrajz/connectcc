<?php

namespace App\Exports;

use App\Models\whatsapp_number;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class whatsapp_export implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($id, $sessionid)
    {
        $this->id = $id;
        $this->sessionid = $sessionid;
    }
    public function collection()
    {
        //
        return $data = whatsapp_number::select('number','country_number')
        ->where('userid',$this->id)
        ->where('session_id',$this->sessionid)
        ->get();
    }
    public function headings(): array
    {
        return [
            // 'S#',
            'Number',
            'Number with Country Code',
        ];
    }
}
