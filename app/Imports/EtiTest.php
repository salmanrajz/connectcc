<?php

namespace App\Imports;

use App\dummy_data_test;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EtiTest implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        // dd($collection);
        foreach($collection as $row){
            $d = dummy_data_test::create([
                'data1' => $row[1],
                'data2' => $row[2],
                'data3' => $row[3],
                'data4' => $row[4],
            ]);
        }
    }
}
