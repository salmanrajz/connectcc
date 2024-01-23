<?php

namespace App\Imports;

use App\activation_form;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UpdateSR implements ToCollection,WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 2;
    }
    public function collection(Collection $collection)
    {
        //
        foreach ($collection as $row) {

                // dd($row);
                $d = activation_form::where('activation_selected_no',$row[5])->first();
                if($d){
                    $d->real_sr = $row['19'];
                    $d->save();
                }
        }
    }
}
