<?php

namespace App\Imports;

use App\mnp_data;
use App\WhatsAppScan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class mnp_bulk implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    //
    public function startRow(): int
    {
        return 2;
    }
    //
    public function collection(Collection $collection)
    {
        //
        foreach ($collection as $row) {
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);
            $length = Str::length($row[0]);
            if($length == 12){
                if (!WhatsAppScan::where('wapnumber', '=', $row[0])->exists()) {
                    // $a = substr($row[0],0,3);
                    if (preg_match('/(.)\\1{6}/', $row[0])) {
                        $count_digit = 7;
                    } elseif (preg_match('/(.)\\1{5}/', $row[0])) {
                        // echo '###' . $i . '<br> => 5 Times Number';
                        $count_digit = 6;


                    } elseif (preg_match('/(.)\\1{4}/', $row[0])) {
                        // echo '###' . $i . '<br> => 5 Times Number';
                        $count_digit = 5;

                    } else if (preg_match('/(.)\\1{3}/', $row[0])) {
                        // echo '###' . $i . '<br> => 4 Times Number';
                        $count_digit = 4;


                    } else if (preg_match('/(.)\\1{2}/', $row[0])) {
                        // echo '###' . $i . '<br> => 3 Times Number';
                        $count_digit = 3;

                    } else if (preg_match('/(.)\\1{1}/', $row[0])) {
                        // echo '###' . $i . '<br> => 2 Times Number';
                        $count_digit = 2;


                    }
                    // else if (preg_match('/(.)\\1{1}/', $i)) {
                    //     // echo '###' . $i . '<br> => 2 Times Number';
                    //     if (!WhatsAppScan::where('wapnumber', '=', $i)->exists()) {
                    //         $d = WhatsAppScan::create([
                    //             'wapnumber' => $i,
                    //         ]);
                    //     }
                    // }
                    else {
                        // echo $i . ' => <br>' . '=> No 3 Times';
                        $count_digit = 'random';


                    }
                    WhatsAppScan::create([
                        'wapnumber' => $row[0],
                        'is_aamt' => 1,
                        'count_digit' => $count_digit,
                        // 'channel_type' => $row[8],
                    ]);
                }
            }
        }
        //
    }
    // public function batchSize(): int
    // {
    //     return 1000;
    // }

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
