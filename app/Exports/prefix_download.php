<?php

namespace App\Exports;

use App\WhatsAppMnpBank;
use App\WhatsAppScan;
use Maatwebsite\Excel\Concerns\FromCollection;

class prefix_download implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        // ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes
        ini_set('memory_limit', '-1'); //300 seconds = 5 minutes

        return $data = WhatsAppScan::select('wapnumber')->whereIn('count_digit',['3','2','1','random'])->offset(600000)->limit(200000)->get();
        // return $data = WhatsAppScan::select('wapnumber')->whereIn('count_digit',['3','2','1','random'])->limit(200000)->get();
        // return $data = WhatsAppScan::whereIn('count_digit',['2'])->offset(400000)->limit(100000)->get();
        // return $data = WhatsAppScan::whereIn('count_digit',['2'])->get();
        // ->offset(3400000)->limit(200000)->get();
    }
}


// 971589165458
