<?php

namespace App\Console\Commands;

use App\Models\choosen_number;
use App\Models\lead_location;
use App\Models\lead_sale;
use App\Models\numberdetail;
use App\Models\whatsapp_number;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CoordinationFollow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CoordinationFollow:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Daily Pending Coordination to Follow Up Coordination';

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
        // $k = choosen_number::create([
        //     'number_id' => 1,
        //     'user_id' => 1,
        //     'status' => '1',
        //     'agent_group' => 1,
        //     // 'ip_address' => Request::ip(),
        //     'date_time' => Carbon::now()->toDateTimeString(),
        // ]);
        $date = new \DateTime();
        $date->modify('-3 hours');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $dataNum = choosen_number::select("choosen_numbers.number_id", 'choosen_numbers.id')
        ->Join(
            'numberdetails',
            'numberdetails.id',
            '=',
            'choosen_numbers.number_id'
        )
            // ->where("choosen_numbers.user_id", auth()->user()->id)
        ->where('choosen_numbers.status', '1')
        ->where('numberdetails.non_c','!=',1)
        ->where('numberdetails.status','Reserved')
        ->where(
            'choosen_numbers.created_at',
            '<',
                $formatted_date
        )
        ->get();
        foreach ($dataNum as $d) {
            $dcn = choosen_number::findorfail($d->id);
            $dcn->status = '0';
            $dcn->personal_status = 'Auto Available';
            $dcn->status_time = Carbon::now();
            $dcn->save();
            $dn = numberdetail::findorfail($d->number_id);
            if($dn->status == 'Hold'){

            }else{
                $dn->status = 'Available';
                $dn->personal_status = 'Auto Available';
                $dn->status_time = Carbon::now();
                $dn->save();
            }
        }
        $today = Carbon::today();
        $l = lead_sale::whereDate('later_date',$today)->where('status','1.06')->get();
        // if($l){
            foreach($l as $k){
                $l = lead_sale::findorfail($k->id);
                $l->status = '1.10';
                $l->save();
                //
                $ld = lead_location::where('lead_id', $k->id)->first();
                $ld->assign_to = '136';
                $ld->save();
                //
            }
        //
        $whatsapp_data = whatsapp_number::where('download',1)->delete();
        // }
        // return 0;
        // $s = lead_sale::where(['date_time', ]);
        // $posts = lead_sale::whereDate('created_at', Carbon::today())->get();
        // $posts = lead_sale::select('lead_sales.*')
        // ->whereDate('updated_at', Carbon::today())
        // ->where('status', '1.07')->get();
        // if($posts){
        //     foreach ($posts as $k => $value) {
        //         // echo $k . '<br>';
        //         // echo $value->id . '<br>';
        //         $p = lead_sale::findorfail($value->id);
        //         $p->status = '1.16';
        //         $p->remarks = 'Time Out, Please Proceed Next Day Immediately';
        //         $p->save();
        //     }
        // }
        // //
        // $posts = lead_sale::select('lead_sales.*')
        // ->whereDate('updated_at', Carbon::today())
        // ->where('status', '1.10')->get();
        // if($posts){
        //     foreach ($posts as $k => $value) {
        //         // echo $k . '<br>';
        //         // echo $value->id . '<br>';
        //         $p = lead_sale::findorfail($value->id);
        //         $p->status = '1.17';
        //         $p->remarks = 'Time Out, Please Proceed Next Day Immediately';
        //         $p->save();
        //     }
        // }


    }
}
