<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class RemindFailure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FailureAgent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will remind to everyone';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $details[]
        $salesData = \App\lead_sale::selectRaw("COUNT(*) as count, lead_sales.saler_id")
        ->LeftJoin(
            'users',
            'users.id',
            'lead_sales.saler_id'
        )
            // ->whereBetween('created_at', [Carbon::createFromDate($year, $month, 1), Carbon::createFromDate($year, $month, $daysCount)])
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->where('users.agent_code', 'CC8')
            ->where('lead_sales.status','1.02')
            ->groupBy('users.id')
            ->get()->pluck('saler_id');
        $a = array();
        $data =  \App\User::where('role', 'Sale')
            ->whereNotIn('id', $salesData)
            ->where('users.agent_code', 'CC8')
            ->get();
        foreach ($data as $k => $v) {
            $a[$k]['name'] = $v['name'];
            $a[$k]['email'] = $v['email'];
            $a[$k]['call_center'] = $v['agent_code'];
            $last_sale =     \Carbon\Carbon::parse(\App\Http\Controllers\HomeController::LastSaleCounter($v->id))->diffForHumans();
            if ($last_sale == '1 second ago') {
                $a[$k]['last_sale'] = 'No Sale Made Yet';
            }
            else{
                $a[$k]['last_sale'] = 'System Issue - Contact Administrator';
            }
        }

        // $data[] =
        //
        \Mail::mailer('smtp')
            // ->to(['muhamin@etisalat.ae', 'oabdulla@etisalat.ae'])
        ->to(['parhakooo@gmail.com'])
            // ->cc(['salmanahmed334@gmail.com'])
            // ->bcc(['isqintl@gmail.com','salmanahmed334@gmail.com'])
            // ->from('crm.riuman.com','riuman')
        ->send(new \App\Mail\RemindFailureMail($a));
    }
}
