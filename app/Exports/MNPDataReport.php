<?php

namespace App\Exports;

use App\main_data_user_assigned;
use Maatwebsite\Excel\Concerns\FromCollection;

class MNPDataReport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $data = main_data_user_assigned::select('main_data_user_assigners.number_id', 'main_data_user_assigners.status', 'whats_app_mnp_banks.number','users.name as agent_name','users.agent_code')
        ->Join(
            'users',
            'users.id',
            'main_data_user_assigners.user_id'
        )
        ->Join(
            'whats_app_mnp_banks',
            'whats_app_mnp_banks.id',
            'main_data_user_assigners.number_id'
        )
        // ->where('users.agent_code', auth()->user()->agent_code)
        ->WhereNotNull('main_data_user_assigners.status')
        // ->where('main_data_user_assigners.status',$status)
        // ->groupby('main_data_user_assigners.status')
        ->get();
    }
}
