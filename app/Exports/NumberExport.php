<?php

namespace App\Exports;

use App\numberdetail;
use Maatwebsite\Excel\Concerns\FromCollection;

class NumberExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return numberdetail::all();
    }
}
