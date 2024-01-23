<?php

namespace App\Imports;

use App\numberdetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;


class UploadRegionNumber implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 2;
    }
    public function collection(Collection $rows)
    {
        // $faker = Faker::create();
        // dd($rows);
        foreach ($rows as $row) {
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);
            // return $rows;
            if (!numberdetail::where('number', '=', $row[1])->exists()) {
                numberdetail::create([
                    'number' => $row[1],
                    'passcode' => $row[2],
                    'type' => $row[3],
                    'channel_type' => $row[4],
                    'call_center' => $row[5],
                    'region' => $row[6],
                    'identity' => $row[7],

                ]);

            } else {

            }
        }
    }

}
