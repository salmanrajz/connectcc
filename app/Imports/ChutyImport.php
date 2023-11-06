<?php

namespace App\Imports;

use App\Models\chutya;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ChutyImport implements

    ToCollection,
    WithChunkReading,
    ShouldQueue

{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        ini_set('max_execution_time', '30000'); //300 seconds = 5 minutes
        ini_set('max_file_uploads', '-1');

        foreach ($collection as $key => $row) {
            // if($key == 200){
                // dd("ok");
                $mydata[] = $row[0];
            // }
        }
        // dd($mydata);
        $mydata = implode("&", $mydata);
        // $mydata = 1;
        // $dd = json_encode($mydata);
        // $ee = json_decode($dd);
        // dd($ee);
        $d = chutya::where('number',$mydata)->first();
        if(!$d){
            chutya::create([
                'number' => $mydata
            ]);
        }
    }
    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
