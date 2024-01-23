<?php

namespace App\Imports;

use App\numberdetail;
use App\OldNumbers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ActiveForm implements ToCollection, WithStartRow, WithChunkReading, ShouldQueue
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

        foreach ($rows as $row) {
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);

            if (!numberdetail::where('number', '=', $row[1])->exists()) {
                // numberdetail::create([
                //     'status' => 'Active',
                //     'Remarks' => 'RECENT ACTIVE',
                //     // 'number' => $row[1],
                //     // 'passcode' => $row[2],
                //     // 'type' => $row[7],
                //     // 'channel_type' => $row[8],
                // ]);
            } else {
                $n = numberdetail::where('number', '=', $row[1])->first();
                $n3 = OldNumbers::create([
                    'number' => $row['1'],
                ]);
                $n2 = numberdetail::findorfail($n->id);
                // $n2->status = 'Active';
                $n2->delete();
                // $n2->remarks = 'TTF ACT';
                // $n2->save();
                // $n = numberdetail::where('number', '=', $row[1])->first();
                // $n2 = numberdetail::findorfail($n->id);
                // $n2->old_status = 'Already Exist';
                // $n2->save();
                // update(['old_status','Already Exist']);
            }
        }
    }
    // public function startRow(): int
    // {
    //     return 1;
    // }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
