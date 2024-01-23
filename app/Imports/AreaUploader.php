<?php

namespace App\Imports;

use App\elife_areas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AreaUploader implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        foreach($collection as $area){
            elife_areas::create([
                'area_name' => $area['0'],
            ]);
        }
    }
}
