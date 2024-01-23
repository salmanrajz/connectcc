<?php

namespace App\Imports;

use App\elife_bulker;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class elife_bulk implements ToCollection, WithStartRow
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
        // dd($collection);
        foreach ($collection as $row) {
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);
            if (!elife_bulker::where('number', '=', $row[1])->exists()) {
                elife_bulker::create([
                    'customer_name' => $row[1],
                    'number' => $row[2],
                    'plan' => $row[3],
                    'area' => $row[4],
                    // 'channel_type' => $row[8],
                ]);
            }
        }
    }
}
