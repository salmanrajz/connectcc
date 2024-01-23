<?php

namespace App\Imports;

use App\number_assigner;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BulkImporter implements
    ToCollection,
    WithStartRow
    ,WithChunkReading,
    ShouldQueue
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
            // dd($row[0]);
            // $random = mt_rand(13455623, 93455632);
            // $request['password'] = Hash::make($random);
            if (!number_assigner::where('number', '=', $row[0])->exists()) {
                number_assigner::create([
                    'number' => $row['0'],
                    // 'channel_type' => $row[8],
                ]);
                // dd($row);
            } else {
                // bulknumber::create([
                //     'number' => '00000000000',
                //     'type' => 'P2P',
                // //     // 'channel_type' => $row[8],
                // ]);
                // return "soom";
                // dd($row);
            }
        }
    }
    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
