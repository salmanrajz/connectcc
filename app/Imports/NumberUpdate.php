<?php

namespace App\Imports;

use App\numberdetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NumberUpdate implements ToCollection,WithStartRow
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
        foreach ($collection as $row) {
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);
            // return $rows;
            if (numberdetail::where('number', '=', $row[1])->exists()) {
                $sam = numberdetail::where('number','=',$row[1])->first();
                $sam->type = $row[3];
                $sam->passcode = $row[2];
                $sam->newlist = 404;
                // $sam->identity = $row[6];
                $sam->save();
                // if($sam->channel_type != 'ExpressDial'){
                //     $sam->channel_type = 'ExpressDial';
                //     // $sam->status = 'Available';
                //     // $sam->newlist = 03;
                //     $sam->save();

                // }
                                // numberdetail::create([
                //     'number' => $row[1],
                //     'passcode' => $row[2],
                //     'type' => $row[3],
                //     'channel_type' => $row[4],
                //     'call_center' => $row[5],
                //     'identity' => $row[6],
                // ]);
                // StockData::create([
                //     'vendor' => $row[0],
                //     'model' => $row[1],
                //     'description' => $row[2],
                //     'shipping_type' => $row[3],
                //     'SKU' => $row[4],
                //     'stock' => $row[5],
                //     'CP' => $row[6],
                //     'product_title' => $row[7],
                //     'storage' => $row[8],
                //     'cpu' => $row[9],
                //     'DDR' => $row[10],
                //     'video_graphics' => $row[11],
                //     'display' => $row[12],
                //     'price' => $row[13],
                //     // 'father_name' => $row[4],
                //     // 'email' => $faker->unique()->email,
                //     // 'secondary_email' => $random,
                //     // 'password' => $request['password'],
                //     // 'batch_no' => $row[1],
                //     // 'batch_group' => $row[5],
                //     // 'contact' => $row[6],
                //     // 'f_contact' => $row[7],
                //     // 'address' => $row[8],
                //     // 'form_no' => $row[9],
                //     // 'status' => 1,
                //     ]);
                // }
                // else{
                // return "failed";
            } else {
                numberdetail::create([
                    'number' => $row[1],
                    'passcode' => $row[2],
                    'type' => $row[3],
                    'channel_type' => $row[4],
                    'call_center' => $row[5],
                    'identity' => $row[6],
                    'newlist' => '404',
                ]);
                // $n = numberdetail::where('number', '=', $row[0])->first();
                // $n2 = numberdetail::findorfail($n->id);
                // $n2->remarks = 'PULL BACK';
                // $n2->status = 'Active';
                // $n2->save();
                // update(['old_status','Already Exist']);
            }
        }
    }
}
