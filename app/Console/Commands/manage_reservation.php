<?php

namespace App\Console\Commands;

use App\Models\choosen_number;
use App\Models\lead_sale;
use App\Models\numberdetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class manage_reservation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ManageReservation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Reservation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return 0;
        $date = new \DateTime();
        $date->modify('-15 Hours');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $dataNum = choosen_number::select("choosen_numbers.number_id", 'choosen_numbers.id')
        ->Join(
            'numberdetails',
            'numberdetails.id',
            '=',
            'choosen_numbers.number_id'
        )
        // ->where("choosen_numbers.user_id", auth()->user()->id)
        ->where('numberdetails.non_c', '1')
        ->where('numberdetails.status', 'Reserved')
        ->where(
            'numberdetails.updated_at',
            '<',
            $formatted_date
        )
            ->get();
        foreach ($dataNum as $d) {
            $dcn = choosen_number::findorfail($d->id);
            $dcn->status = '0';
            $dcn->save();
            $dn = numberdetail::findorfail($d->number_id);
            if (
                $dn->status == 'Hold'
            ) {
            } else {
                $dn->status = 'Available';
                $dn->non_c = '0';
                $dn->save();
            }
        }
        // $today = Carbon::today();
        // $l = lead_sale::whereDate('later_date', $today)->where('status', '1.06')->get();
        // // if($l){
        // foreach ($l as $k) {
        //     $l = lead_sale::findorfail($k->id);
        //     $l->status = '1.10';
        //     $l->save();
        // }
    }
}
