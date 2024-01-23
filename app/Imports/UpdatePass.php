<?php

namespace App\Imports;

use App\numberdetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UpdatePass implements ToCollection,WithStartRow
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
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);
            // return $rows;
            //
            // 'number' => $row[1],
            //         'passcode' => $row[2],
            //         'type' => $row[3],
            //         'channel_type' => $row[4],
            //         'call_center' => $row[5],
            //         'identity' => $row[6],
            //
            if (!numberdetail::where('number', '=', $row[1])->exists()) {
                //
                numberdetail::create([
                    'number' => $row[1],
                    'passcode' => $row[2],
                    'type' => $row[3],
                    'channel_type' => $row[4],
                    'call_center' => $row[5],
                    'identity' => $row[6],
                    'newlist'=> 1,

                ]);
                //

            } else {
                $n = numberdetail::where('number', '=', $row[1])->first();
                $n2 = numberdetail::findorfail($n->id);
                if($n2->status == 'Special Hide - Do not touch it Boss Said'){
                    $n2->number = $row['1'];
                    $n2->passcode = $row['2'];
                    $n2->type = $row['3'];
                    $n2->channel_type = $row['4'];
                    $n2->call_center = $row['5'];
                    $n2->status = 'Available';
                    $n2->newlist = 1;

                    // $n2->identity = $row['6'];
                    // $n2->call_center = $row[5];
                    // $n2->type = $row['3'];
                    // $n2->identity = $row[6];
                    // $n2->
                    $n2->save();
                }
                else{

                $n2->number = $row['1'];
                $n2->passcode = $row['2'];
                $n2->type = $row['3'];
                $n2->channel_type = $row['4'];
                $n2->call_center = $row['5'];
                $n2->newlist = 1;

                // $n2->identity = $row['6'];
                // $n2->call_center = $row[5];
                // $n2->type = $row['3'];
                // $n2->identity = $row[6];
                // $n2->
                $n2->save();
                    // update(['old_status','Already Exist']);
                }

            }
        }
        //
    }
    //  public function batchSize(): int
    // {
    //     return 4000;
    // }

    // public function chunkSize(): int
    // {
    //     return 3000;
    // }
}
