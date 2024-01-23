<?php

namespace App\Imports;

use App\dnclist;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UpdateDNC implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        foreach($collection as $r){
            if (!dnclist::where('number', '=', $r[0])->exists()) {
                $data = dnclist::create(
                    [
                    'number'=> $r['0'],
                ]
                );
            }
        }
    }
}
