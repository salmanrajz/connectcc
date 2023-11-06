<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserLog implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;
    function __construct($id)
    {
        $this->id = $id;
    }
    public function collection()
    {
        //
        return $data = User::find($this->id)->authentications;
    }
    public function headings(): array
    {
        return [
            'S#',
            'Agent Table',
            'Agent ID',
            'Ip Address',
            'User Agent (Browser)',
            'Login At',
            'Logout At',
        ];
    }
}
