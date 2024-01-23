<?php

namespace App\Console\Commands;

use App\Exports\ConnectMonthlyExport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class DailyReportConnect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DailyReportConnect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Connect Report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Excel::store(new ConnectMonthlyExport, 'connect-etisalat-monthly-export.xlsx', 'azure');
        $details = [
            'pdf_location' => 'https://salmanrajzzdiag.blob.core.windows.net/connectcc/connect-etisalat-monthly-export.xlsx',
            'subject' => 'Daily Activation Report ' . Carbon::now()->subDay()->format('F Y'),
            'email_name' => 'Connect',
            'send_mail' => 'report@connectcc.ae'
            // 'subject' => 'Sales Report Month Year TTF till ' . Carbon::now()->subDay()->format('d F Y'),
        ];
        // \Mail::to(['salmanahmed334@gmail.com'])
        // \Mail::
        \Mail::mailer('smtp2')
        // ->to(['muhamin@etisalat.ae', 'oabdulla@etisalat.ae'])
        ->to(['parhakooo@gmail.com'])
        ->cc(['salmanahmed334@gmail.com'])
        // ->bcc(['isqintl@gmail.com', 'salmanahmed334@gmail.com'])
        // ->from('crm.riuman.com','riuman')
        ->send(new \App\Mail\DailyExpress($details));
        //
    }
}
