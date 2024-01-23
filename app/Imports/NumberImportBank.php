<?php

namespace App\Imports;

use App\dnclist;
use App\uploaderdatabank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NumberImportBank implements ToCollection, WithStartRow, WithCalculatedFormulas, WithChunkReading, ShouldQueue
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
            $num_prefix = substr($row[2], 0, 3);
            $an_prefix = substr($row[3], 0, 3);
            // dnclist::where('number', '=', $k)->first();
            if (dnclist::where('number', '=', $row[2])->first() || dnclist::where('number', '=', $row[3])->first()) {
                // echo "DNC NUMBER " . ',' . $k . ',' . preg_replace('/0/', '971', $k, 1);
                // echo '<br>';
                $mark_dnd = 1;
            } else {
                $mark_dnd = 0;
            }
            if (!uploaderdatabank::where('number', '=', $row[2])->exists()) {
                uploaderdatabank::create([
                    'name' => $row['1'],
                    'number' => $row['2'],
                    'alternative_number' => $row['3'],
                    'area' => $row['4'],
                    'emirates' => $row['5'],
                    'prefix_number' => $num_prefix,
                    'prefix_an' => $an_prefix,
                    'mark_dnd' => $mark_dnd,
                    'mark_soft_dnd' => 0,
                    'status_1' => 0,
                    'assigned_status' => 0,
                ]);
            }
        }
    }
    public function batchSize(): int
    {
        return 5000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
