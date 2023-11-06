<?php

namespace App\Exports;

use App\oldData;
use Maatwebsite\Excel\Concerns\FromCollection;

class olddataexport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = oldData::all();
    }
}
