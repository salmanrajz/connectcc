<?php

namespace App\Http\Controllers;

use App\Exports\DailyActivation;
use App\Exports\DailyLead;
use App\Exports\DownloadAvailableNumber;
use App\Exports\MonthlyActivation;
use App\Exports\MyMonthlyActivation;
use App\Exports\MyPreviousMonthlyActivation;
use App\Exports\RecordingNullYearly;
use App\Exports\AgentReport;
use App\Exports\CustomActivationDownload;
use App\Exports\DailyActiveNonVerifiedLead;
use App\Exports\dnclogexport;
use App\Exports\DownloadActiveNumber;
use App\Exports\EtisalatMonthlyExport;
use App\Exports\LeadAvailableNumber;
use App\Exports\MonthlyActivationExpress;
use App\Exports\MonthlyLead;
use App\Exports\MyPreviousReport;
use App\Exports\nonverifiedexport;
use App\Exports\olddataexport;
use App\Exports\prefix_download;
use App\Exports\previousnonprepaidact;
use App\Exports\ReservedAvailableExport;
use App\Exports\TotalFollowUp;
use App\Exports\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    //
    public function export_daily_report()
    {
        ob_end_clean();

        return Excel::download(new DailyLead, 'daily_report.xlsx');
    }
    //
    public function PrefixDownload()
    {
        ob_end_clean();

        return Excel::download(new prefix_download, 'prefix_download.xlsx');
    }
    public function monthly_export(Request $request)
    {
        $id = $request->id;
        ob_end_clean();

        return Excel::download(new MonthlyLead($id),  '_' . $id . '_MonthlyLead.xlsx');
    }
    public function available_export()
    {
        ob_end_clean();

        return Excel::download(new DownloadAvailableNumber, 'available_number.xlsx');
    }
    public function active_export()
    {
        ob_end_clean();

        return Excel::download(new DownloadActiveNumber, 'active_number.xlsx');
    }
    public function daily_activation_export()
    {
        ob_end_clean();

        return Excel::download(new DailyActivation, 'daily_activation.xlsx');
    }
    public function RecordingNullMonth()
    {
        ob_end_clean();

        return Excel::download(new RecordingNullYearly, 'Recording_null_monthly.xlsx');
    }
    public function monthly_activation_export()
    {
        ob_end_clean();

        return Excel::download(new MonthlyActivation, 'monthly_activation.xlsx');
    }
    public function dnclogexport()
    {
        ob_end_clean();

        return Excel::download(new dnclogexport, 'dnc_log_export.xlsx');
    }
    public function monthly_activation_export_express()
    {
        ob_end_clean();

        return Excel::download(new MonthlyActivationExpress, 'monthly_activation_express.xlsx');
    }
    public function ReservedAvailableExport()
    {
        ob_end_clean();

        return Excel::download(new ReservedAvailableExport, 'ReservedAvailableExport.xlsx');
    }
    public function LeadAvailableNumber()
    {
        ob_end_clean();

        return Excel::download(new LeadAvailableNumber, 'LeadAvailableNumber.xlsx');
    }
    public function my_monthly_activation_export()
    {
        $id = auth()->user()->agent_code;
        ob_end_clean();
        return Excel::download(new MyMonthlyActivation($id), '_' . $id . '_monthly_activation.xlsx');
    }
    public function olddataexport()
    {
        // $id = auth()->user()->agent_code;
        ob_end_clean();
        return Excel::download(new olddataexport, 'olddataexport.xlsx');
    }
    public function nonverifiedexport()
    {
        // $id = auth()->user()->agent_code;
        ob_end_clean();
        return Excel::download(new nonverifiedexport, 'nonverifiedexport.xlsx');
    }
    public function callcenter_monthly_activation_export(Request $request)
    {
        $id = $request->id;
        ob_end_clean();

        return Excel::download(new MyMonthlyActivation($id), '_' . $id . 'monthly_activation.xlsx');
    }
    public function my_previous_monthly_activation_export()
    {
        $id = auth()->user()->agent_code;
        ob_end_clean();

        return Excel::download(new MyPreviousMonthlyActivation($id), '_' . $id . '_monthly_activation.xlsx');
    }
    public function previousnonprepaidact(Request $request)
    {
        // $id = auth()->user()->agent_code;
        $id = $request->id;

        $d =  Excel::download(new previousnonprepaidact($id),   $id . '_' . 'previous_monthly_activation.xlsx');
        ob_end_clean();
        return $d;
    }
    public function callcenter_previous_monthly_activation_export(Request $request)
    {
        // $id = auth()->user()->agent_code;
        $id = $request->id;
        ob_end_clean();

        return Excel::download(new MyPreviousMonthlyActivation($id), '_' . $id . 'monthly_activation.xlsx');
    }
    public function callcenter_all_report(Request $request)
    {
        // $id = auth()->user()->agent_code;
        $id = $request->id;
        ob_end_clean();

        return Excel::download(new MyPreviousReport($id),  '_' . $id . 'previous_leads_report.xlsx');
    }
    public function AgentReport(Request $request)
    {
        // $id = auth()->user()->agent_code;
        $id = $request->id;
        ob_end_clean();

        return Excel::download(new AgentReport($id), 'AgentReport.xlsx');
    }
    public function allfollowup(Request $request)
    {
        // $id = auth()->user()->agent_code;
        // $id = $request->id;
        ob_end_clean();

        return Excel::download(new TotalFollowUp, 'follow-up-report.xlsx');
    }
    public function UserLog(Request $request)
    {
        // $id = auth()->user()->agent_code;
        $id = $request->id;
        ob_end_clean();

        return Excel::download(new UserLog($id), 'UserLog.xlsx');
    }
    public function DownloadCustomReport(Request $request)
    {
        // $id = auth()->user()->agent_code;
        ob_end_clean();

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $channel_partner = $request->channel_partner;
        $number_category = $request->number_category;
        // ob_end_clean();

        return Excel::download(new CustomActivationDownload($start_date, $end_date, $channel_partner, $number_category), 'CustomActivationDownload.xlsx');
    }
    public function monthly_activation_etisalat(Request $request)
    {
        // return $request;
        ob_end_clean();

        Excel::store(new EtisalatMonthlyExport, 'etisalat-monthly-export.xlsx', 'azure');
        // Excel::store(new EtisalatMonthlyExport, 'etisalat-monthly-export.xlsx', 'azure');
        $details = [
            'pdf_location' => 'https://salmanrajzzdiag.blob.core.windows.net/callmax/etisalat-monthly-export.xlsx',
            'subject' => 'Sales Report ' . Carbon::now()->subDay()->format('F Y') . ' (TTF)',
            // 'subject' => 'Sales Report Month Year TTF till ' . Carbon::now()->subDay()->format('d F Y'),
        ];
        // \Mail::to(['salmanahmed334@gmail.com'])
        \Mail::to(['salmanahmed334@gmail.com', 'isqintl@gmail.com', 'kkashifs@gmail.com'])
            // ->cc($app->notify_email)
            // ->from('crm.riuman.com','riuman')
            ->send(new \App\Mail\DailyEmail($details));
    }
    public function non_active_report(Request $request)
    {
        // $id = auth()->user()->agent_code;
        // $id = $request->id;
        ob_end_clean();

        $callcenter = \App\Models\call_center::where('status', '1')->get();
        foreach ($callcenter as $cc) {
            if ($cc->call_center_name == 'CC1') {
                $secondary_email = 'hassancheema360@gmail.com';
            } elseif ($cc->call_center_name == 'CC2') {
                $secondary_email = 'cc2alert@gmail.com';
            } elseif ($cc->call_center_name == 'CC4') {
                $secondary_email = 'Anwarnoor87@gmail.com';
            } elseif ($cc->call_center_name == 'CC5') {
                $secondary_email = 'Areedahmed907@gmail.com';
            } elseif ($cc->call_center_name == 'CC7') {
                $secondary_email = 'salmanahmed334@gmail.com';
            } elseif ($cc->call_center_name == 'CC8') {
                $secondary_email = 'Malik.icma009@gmail.com';
            } elseif ($cc->call_center_name == 'CC9') {
                $secondary_email = 'aquibjaved219@gmail.com';
            } elseif ($cc->call_center_name == 'CC10') {
                $secondary_email = 'hassancheema360@gmail.com';
            }
            Excel::store(new DailyActiveNonVerifiedLead($cc->call_center_name),   $cc->call_center_name . '_non-active-lead.xlsx', 'azure');
            $details = [
                'pdf_location' => 'https://salmanrajzzdiag.blob.core.windows.net/callmax/' . $cc->call_center_name . '_non-active-lead.xlsx',
                'subject' => 'Non Active Lead Report',
                // 'subject' => 'Sales Report Month Year TTF till ' . Carbon::now()->subDay()->format('d F Y'),
            ];
            \Mail::to(['salmanahmed334@gmail.com', $secondary_email])
                // ->cc($app->notify_email)
                // ->from('crm.riuman.com','riuman')
                ->send(new \App\Mail\DailyEmail($details));
        }
    }
}
