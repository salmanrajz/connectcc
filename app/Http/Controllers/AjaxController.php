<?php

namespace App\Http\Controllers;

use App\Models\choosen_number;
use App\Models\choosen_number_log;
use App\Models\elife_plan;
use App\Models\itproductplans;
use App\Models\lead_sale;
use App\Models\later_leads;
use App\Models\numberdetail;
use App\Models\multisale;
use App\Models\plan;
use App\Models\remark;
use App\Models\CustomerFeedBack;
use App\Models\lead_location;
use App\Models\choosen_number_location;
use App\Models\customer_notification;
use App\Models\elife_log;
use App\Models\emirate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Events\MyEvent;
use App\Models\Events\TaskEvent;
use App\Models\status_code;
use App\Models\timing_duration;
use App\Models\User;
use App\Models\verification_form;
use App\Models\country_phone_code;
use App\Models\old_lead_numbers;
use App\Models\TargetAssignerUser;
use thiagoalessio\TesseractOCR\TesseractOCR;
// use werk365\IdentityDocuments\IdentityDocuments;
use GoogleCloudVision\GoogleCloudVision;
use GoogleCloudVision\Request\AnnotateImageRequest;
use Illuminate\Support\Facades\Session;
// use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;




// use Request;


class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public static function MaroDikro($origin_lat, $origin_lng, $dest_lat, $dest_lng)
    {
        $origin = $origin_lat . ',' . $origin_lng;
        // $destination = $dest_lat . ',' . $dest_lng;
        if (empty($dest_lat) || empty($dest_lng)) {
            return $destination = $dest_lat . ',' . $dest_lng;
        } else {
            return $destination = '23,23' . ',' .  '23,23';
        }
        $response = \GoogleMaps::load('distancematrix')
            ->setParam([
                'origins'          => $origin,
                'destinations'     => $destination,
                // 'mode' => 'driving' ,
                // 'language' => 'fr',

            ])->get();
        $res = json_decode($response);
        // dd($res);
        // dd($res['row);
        // return $res->rows->elements->duration;
        foreach ($res->rows as $resp) {
            foreach ($resp->elements as $dist) {
                //     // dd($dist->distance);
                // dd($dist);
                foreach ($dist->duration as $dt) {
                    // dd($dt);
                    $d = $dt;
                }
                foreach ($dist->distance as $ds) {
                    // dd($ds);
                    // echo $ds;
                    echo $variable = substr($ds, 0, strpos($ds, "km"));

                    // echo strtok($ds, 'KM');
                    // foreach($ds['value'] as $dsf){
                    //     echo $dsf;
                    // }
                }
            }
        }
    }
    public static function passcode_fetch($number)
    {
        $l = numberdetail::where('number', $number)->first();
        // return $lead_id;
        return $l->passcode;
    }
    public static function  typefetch($number)
    {
        $l = numberdetail::where('number', $number)->first();
        // return $lead_id;
        return $l->type;
    }
    public static function status_fetch($number)
    {
        $l = numberdetail::where('number', $number)->first();
        // return $lead_id;
        return $l->status;
    }
    public static function total_time($first_date, $second_date)
    {
        $t1 = Carbon::parse($first_date);
        $t2 = Carbon::parse($second_date);
        // return $diff = $t1->diff($t2);
        return $t1->diff($t2)->format('%H:%I:%S');
    }
    public function numbersystem(Request $request)
    {
        // return $slug;
        // return auth()->user()->role;
        if (auth()->user()->role == 'NumberSuperAdmin' || auth()->user()->role == 'Manager' || auth()->user()->role == 'Cordination' || auth()->user()->role == 'Emirate Coordinator' || auth()->user()->role == 'FloorManager' || auth()->user()->role == 'TeamLeader' || auth()->user()->role == 'Admin') {
            // return "boom";
            // return auth()->user()->agent_code;
            // comment feb 2021
            // $emirates = emirate::all();
            // $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "users.name", "choosen_numbers.created_at as datetime")
            // ->Join(
            //     'choosen_numbers',
            //     'choosen_numbers.number_id',
            //     '=',
            //     'numberdetails.id'
            // )
            // ->Join(
            //     'users',
            //     'users.id',
            //     '=',
            //     'choosen_numbers.user_id'
            // )
            // ->where("numberdetails.status", 'Reserved')
            // ->wherein('numberdetails.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            // ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
            // ->get();
            // return view('number.number-dtl-user', compact('data', 'emirates','slug'));
            // comment feb 2021 end
            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                ->wherein('numberdetails.channel_type', ['ConnectCC'])
                ->groupBy('numberdetails.type')
                ->get();
            //
            // $data = numberdetail::;
            return view('number.number-dtl', compact('q'));
        } else if (auth()->user()->role == 'NumberVerification') {
            $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "users.name", "choosen_numbers.created_at as datetime", "users.agent_code as UserAgentGroup")
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'choosen_numbers.user_id'
                )
                ->where("choosen_numbers.status", 4)
                ->wherein('numberdetails.channel_type', ['ConnectCC'])
                ->where("choosen_numbers.agent_group", '!=', 'ARF')
                // ->where("","")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("choosen_numbers.user_id", $request->simtype)
                // ->where("numberdetails.type", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
            return view('number.number-ver-user', compact('data', 'slug'));
        } else if (auth()->user()->role == 'NumberCord') {
            $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "users.name", "choosen_numbers.created_at as datetime", "users.agent_code as UserAgentGroup")
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'choosen_numbers.user_id'
                )
                ->where("choosen_numbers.status", 4)
                ->wherein('numberdetails.channel_type', ['ConnectCC'])
                ->where("choosen_numbers.agent_group", '!=', 'ARF')
                // ->where("","")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("choosen_numbers.user_id", $request->simtype)
                // ->where("numberdetails.type", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
            return view('number.number-cord-user', compact('data', 'slug'));
        } else if (auth()->user()->role == 'NumberActivation') {
            return view('number.number-activation');
        } else if (auth()->user()->role == 'NumberAdmin' || auth()->user()->role == 'sale') {


            //
            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                ->wherein('numberdetails.channel_type', ['ConnectCC'])
                ->groupBy('numberdetails.type')
                ->get();
            //
            // $data = numberdetail::;
            return view('number.number-dtl', compact('q', 'slug'));
        } else {
            return redirect(route('home'));
        }
    }
    // public function ClientIp(Request $request){

    //         // Gettingip address of remote user
    //         return $user_ip_address=$request->ip();
    //
    public function update_number(Request $request)
    {
        // return $request;
        $lead_data = $d = lead_sale::findOrFail($request->leadid);
        $planName = implode(',', $request->plan_new);
        $SelNumber = implode(",", $request->selnumber);
        $activation_charge = implode(",", $request->activation_charges_new);
        $activation_rate_new = implode(
            ",",
            $request->activation_rate_new
        );
        // foreach(explode())
        $old = old_lead_numbers::create([
            'leadid' => $request->leadid,
            'agent_id' => auth()->user()->id,
            'old_numbers' => $request->old_number,
            'old_plans' => $request->old_plan,
            'old_pay_status' => $request->old_pay_status,
            'old_pay_charges' => $request->old_pay_charges,
        ]);

        if (strpos($request->old_number, ",") !== false) {
            // list($d, $l) = explode('.', $dm, 2);
            foreach (explode(',', $request->old_number) as $key => $val) {

                // foreach ($request->old_number as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Available';
                    $k->save();
                    // CHANGE LATER
                }
                // return $d->id;
                // return "number has been reserved";

            }
        } else {
            $count = numberdetail::select("numberdetails.id")
                ->where('numberdetails.number', $request->old_number)
                ->count();
            if ($count > 0) {
                $dn = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $request->old_number)
                    ->first();
                $k = numberdetail::findorfail($dn->id);
                $k->status = 'Available';
                $k->save();
                // CHANGE LATER
            }
        }
        if (strpos($SelNumber, ",") !== false) {
            // list($d, $l) = explode('.', $dm, 2);
            foreach (explode(',', $SelNumber) as $key => $val) {
                // foreach ($request->SelNumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Hold';
                    $k->save();
                    // CHANGE LATER
                }
                // return $d->id;
                // return "number has been reserved";

            }
        } else {
            $count = numberdetail::select("numberdetails.id")
                ->where('numberdetails.number', $SelNumber)
                ->count();
            if ($count > 0) {
                $dn = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $SelNumber)
                    ->first();
                $k = numberdetail::findorfail($dn->id);
                $k->status = 'Hold';
                $k->save();
                // CHANGE LATER
            }
            // return "Something Wrong";
        }
        // return "Number has been reserved";
        $lead_data->selected_number = $SelNumber;
        $lead_data->select_plan = $planName;
        $lead_data->pay_status = $activation_charge;
        $lead_data->pay_charges = $activation_rate_new;
        $lead_data->save();
        return response()->json(['success' => 'Number has been updated, Please wait']);
    }
    //

    public function update_lead_number(Request $request)
    {
        // return $request->leadid;
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->where('lead_sales.id', $request->leadid)
            ->first();
        $q = numberdetail::select("numberdetails.type")
            ->where("numberdetails.status", "Available")
            ->whereIn("numberdetails.channel_type", ['ConnectCC'])
            ->groupBy('numberdetails.type')

            ->get();
        return view('ajax.update-number', compact('operation', 'q'));
    }
    // }
    public function AcceptLead(Request $request)
    {
        // return $request;
        $d = timing_duration::select('id', 'verify_agent', 'lead_proceed_time')
            ->where('lead_no', $request->lead_id)
            ->first();
        if (!$d) {
            // $td = timing_duration::
            $d =  timing_duration::create([
                'lead_no' => $request->lead_id,
                'lead_generate_time' => Carbon::now()->toDateTimeString(),
                'sale_agent' => auth()->user()->id,
                'status' => '1.01',
            ]);
            //
            if ($d->verify_agent == auth()->user()->id) {
                return "1";
            } else if ($d->verify_agent == '0') {
                $data  = timing_duration::findorfail($d->id);
                $data->lead_accept_time = Carbon::now()->toDateTimeString();
                $data->verify_agent = auth()->user()->id;
                $data->save();
                return '1';
            } else if ($d->verify_agent != '') {
                return "0";
            } else {
                $data  = timing_duration::findorfail($d->id);
                $data->lead_accept_time = Carbon::now()->toDateTimeString();
                $data->verify_agent = auth()->user()->id;
                $data->save();
                return '1';
            }
        }
        // if($d->lead_proceed_time != ''){
        //     return "1";
        // }
        // return $d->verify_agent;
        else if ($d->verify_agent == auth()->user()->id) {
            return "1";
        } else if ($d->verify_agent == '0') {
            $data  = timing_duration::findorfail($d->id);
            $data->lead_accept_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            return '1';
        } else if ($d->verify_agent != '') {
            return "0";
        } else {
            $data  = timing_duration::findorfail($d->id);
            $data->lead_accept_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            return '1';
        }
    }
    public function pending_lead(Request $request)
    {
        // if (auth()->user()->email == 'rabiashamim@verification.com') {

        //     $operation = lead_sale::select("timing_durations.lead_generate_time", 'timing_durations.verify_agent', "lead_sales.*", "status_codes.status_name")
        //         // $user =  DB::table("subjects")->select('subject_name', 'id')
        //         ->LeftJoin(
        //             'timing_durations',
        //             'timing_durations.lead_no',
        //             '=',
        //             'lead_sales.id'
        //         )
        //         ->Join(
        //             'status_codes',
        //             'status_codes.status_code',
        //             '=',
        //             'lead_sales.status'
        //         )
        //         ->whereIn('lead_sales.lead_type', ['HomeWifi'])
        //         ->where('lead_sales.status', '1.01')
        //         ->orderBy('lead_sales.updated_at', 'asc')
        //         // ->groupBy('lead_sales.id')
        //         // ->where('lead_sales.channel_type', $request->slug)
        //         ->get();
        //     $channel_partner = \App\Models\channel_partner::where('status', '1')->latest("updated_at")->get();
        //     // $operation = lead_sale::wherestatus('1.01')->get();
        //     return view('dashboard.view-operation-lead', compact('operation', 'channel_partner'));
        // } else {

            $operation = lead_sale::select("timing_durations.lead_generate_time", 'timing_durations.verify_agent', "lead_sales.*", "status_codes.status_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->whereIn('lead_sales.lead_type', ['postpaid', 'HomeWifi'])
                ->where('lead_sales.status', '1.01')
                ->orderBy('lead_sales.updated_at', 'asc')
                // ->groupBy('lead_sales.id')
                // ->where('lead_sales.channel_type', $request->slug)
                ->get();
            $channel_partner = \App\Models\channel_partner::where('status', '1')->latest("updated_at")->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('verification.view-operation-lead', compact('operation', 'channel_partner'));
        // }
    }
    public function CheckPendingLead(request $request)
    {
        // return $request;
        // return auth()->user()->id;
        $d = customer_notification::select("customer_notifications.id")
            ->where('userid', auth()->user()->id)
            ->count();
        if ($d > 0) {
            notify()->success('you have pending leads, please proceed');
        }
        return $d;
    }
    public function checkNumData(request $request)
    {
        // return $request;
        return $dataNum = choosen_number::select("choosen_numbers.id")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("choosen_numbers.user_id", auth()->user()->id)
            ->where("choosen_numbers.status", 1)
            // ->where("numberdetails.status", 'Available')
            ->count();
    }
    public function CheckPackageName(Request $request)
    {
        // return $request;
        $d = numberdetail::select("numberdetails.type")
            ->where("numberdetails.number", $request->number)
            // ->where('numberdetails. ')
            ->first();
        return $d->type;
        // return $d->type;
    }
    public function CheckChannelPartner(Request $request)
    {
        // return $request;
        $d = numberdetail::select("numberdetails.channel_type")
            ->where("numberdetails.number", $request->number)
            ->first();
        return $d->channel_type;
        // return $d->type;
    }
    public function NumberByCallCenter(Request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.*")
        $data = \App\Models\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
            ->LeftJoin(
                'choosen_numbers',
                'choosen_numbers.number_id',
                'numberdetails.id'
            )
            // ->where("remarks.user_agent_id", auth()->user()->id)
            // ->where("numberdetails.id", $request->id)
            ->where("numberdetails.call_center", $request->simtype)
            ->whereIn("numberdetails.channel_type", ['ConnectCC'])
            ->where("numberdetails.status", 'Available')
            // ->latest()
            // ->get();
            ->groupBy('numberdetails.number')->orderBy('numberdetails.id', 'asc')->get();

        return view('number.number-dtl-fetch', compact('data'));

        // ->groupBy('numberdetails.type')
    }
    public function NumberByType(Request $request)
    {
        // return $request;
        if (auth()->user()->role == 'ARF') {
            return $data = numberdetail::select("numberdetails.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                // ->where("numberdetails.type", $request->simtype)
                ->where("numberdetails.status", 'Available')
                ->where("numberdetails.channel_type", 'OCP1')
                // ->groupBy('numberdetails.type')
                ->get();
            //
            // $data = numberdetail::wherestatus('Available')->get();
            return view('number.number-dtl-fetch', compact('data'));
        } else if ($request->simtype == 'All') {
            if (auth()->user()->region == 'Pak') {
                $data = numberdetail::select("numberdetails.*")
                    ->where("numberdetails.status", 'Available')
                    ->whereIn("numberdetails.channel_type", ['ConnectCC'])
                    ->where("numberdetails.call_center", 'Default')
                    // ->where('numberdetails.region','Pak')
                    // ->whereNull('numberdetails.region')
                    ->latest()
                    ->get();
            } else {
                // return "z";
                $agent_code = auth()->user()->agent_code;
                $data = \App\Models\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                    ->LeftJoin(
                        'choosen_numbers',
                        'choosen_numbers.number_id',
                        'numberdetails.id'
                    )
                    ->where("numberdetails.status", 'Available')
                    ->whereIn("numberdetails.channel_type", ['ConnectCC'])
                    ->where("numberdetails.call_center", 'Default')
                    ->whereNull('numberdetails.region')
                    // ->where('numberdetails.identity', '=!', 'Pak')
                    // ->when($id)
                    ->when($agent_code, function ($q) use ($agent_code) {
                        if ($agent_code == 'Salman') {
                            return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                        } else if ($agent_code == 'CC10') {
                            return
                                // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                                $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                            // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                        }
                        if ($agent_code == 'CC4') {
                            // return
                            // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                            // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                            // $q->whereNotIn('numberdetails.identity', ['May24EIDSGLDE3','May24EIDSilvE3']);
                            // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                        }

                        // if($agent_code == 'CC2' || $agent_code == 'CC8'){
                        //     return
                        //     // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                        //     $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                        //     // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                        // }
                    })
                    ->groupBy('numberdetails.number')->orderBy('numberdetails.updated_at', 'asc')->get();

                //  $data = numberdetail::select("id","number","channel_type","type","identity")
                // ->where("numberdetails.status", 'Available')
                // ->whereIn("numberdetails.channel_type", ['TTF','ExpressDial','MWH','Ideacorp'])
                // ->where("numberdetails.call_center", 'Default')
                // ->whereNull('numberdetails.region')
                // // ->where('numberdetails.identity', '=!', 'Pak')
                // // ->when($id)
                // ->when($agent_code, function ($q) use ($agent_code) {
                //     if($agent_code == 'CC10'){
                //         return
                //         // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                //         $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                //         // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                //     }
                //     // if($agent_code == 'CC2' || $agent_code == 'CC8'){
                //     //     return
                //     //     // $q->whereNotIn('numberdetails.identity','!=', 'August Num')
                //     //     $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                //     //     // ->orwhere('numberdetails.identity','!=', 'EidSpecial');
                //     // }
                // })
                // // ->where('numberdetails.region', 'Pak')
                // // ->limit(10)
                // ->latest()
                // ->get();
            }

            //
            // $data = numberdetail::wherestatus('Available')->get();
            return view('number.number-dtl-fetch', compact('data'));
        } else {
            if (auth()->user()->region == 'Pak') {
                $agent_code = auth()->user()->agent_code;

                $data = numberdetail::select("numberdetails.*")
                    ->where("numberdetails.type", $request->simtype)
                    ->whereIn("numberdetails.channel_type", ['ConnectCC'])
                    ->where("numberdetails.status", 'Available')
                    ->where("numberdetails.call_center", 'Default')
                    ->when($agent_code, function ($q) use ($agent_code) {
                        if ($agent_code == 'Salman') {
                            return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                        } else if ($agent_code == 'CC10') {
                            return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        } else if ($agent_code == 'AAMT' || $agent_code == 'Aamt') {
                            return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                                ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                            // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        } else if ($agent_code == 'CC4') {
                            // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                            // return $q->whereNotIn('numberdetails.identity', ['May24EIDSGLDE3','May24EIDSilvE3']);
                            // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED'])
                            // ->whereIn("numberdetails.channel_type", ['ExpressDial']);;
                            // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        } else {
                            return $q->whereIn("numberdetails.channel_type", ['ConnectCC']);
                        }
                    })
                    // ->where('numberdetails.region', 'Pak')
                    ->latest()
                    ->get();
            } else {

                $agent_code = auth()->user()->agent_code;
                // $data = numberdetail::select("numberdetails.*")
                $data = \App\Models\numberdetail::select('numberdetails.id', 'numberdetails.number', 'numberdetails.channel_type', 'numberdetails.type', 'numberdetails.identity', \DB::raw('count(*) as total'))
                    ->LeftJoin(
                        'choosen_numbers',
                        'choosen_numbers.number_id',
                        'numberdetails.id'
                    )
                    ->where("numberdetails.status", 'Available')
                    ->whereIn("numberdetails.channel_type", ['ConnectCC'])
                    ->where("numberdetails.call_center", 'Default')
                    ->where("numberdetails.type", $request->simtype)
                    ->whereNull('numberdetails.region')
                    // ->where('numberdetails.identity', '=!', 'Pak')
                    // ->when($id)
                    ->when($agent_code, function ($q) use ($agent_code) {
                        if ($agent_code == 'Salman') {
                            return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                        } else if ($agent_code == 'CC10') {
                            return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        } else if ($agent_code == 'CC4') {
                            // return $q->whereNotIn('numberdetails.identity', ['JulyGLD1ED23K', 'JulySILV1ED23K']);

                            // return $q->where('numberdetails.identity','!=', 'Apr23ED1SIL','May24EIDSGLDE3');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        }
                    })
                    ->groupBy('numberdetails.number')->orderBy('numberdetails.updated_at', 'desc')->get();

                // ->where('numberdetails.region', 'Pak')
                // ->limit(10)
                // ->latest()
                // ->get();
            }

            //
            // $data = numberdetail::wherestatus('Available')->get();
            return view('number.number-dtl-fetch', compact('data'));
        }
    }
    public function NumberByType2(Request $request)
    {
        // return $request;
        // return "Salman";
        if ($request->simtype == 'Active') {
            // return auth()->user()->agent_code;
            if (auth()->user()->agent_code == 'ARF') {
                $data = numberdetail::select("numberdetails.*", "choosen_numbers.agent_group")
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    ->Join(
                        'choosen_numbers',
                        'choosen_numbers.number_id',
                        '=',
                        'numberdetails.id'
                    )
                    ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
                    ->where("numberdetails.status", $request->simtype)
                    // ->where("numberdetails.status", 'Available')
                    // ->groupBy('numberdetails.type')
                    ->get();
                //
                // $data = numberdetail::wherestatus('Available')->get();
                return view('number.number-dtl-report', compact('data'));
            } else {
                $data = numberdetail::select("numberdetails.*", "choosen_numbers.agent_group", "users.name")
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    ->LeftJoin(
                        'choosen_numbers',
                        'choosen_numbers.number_id',
                        '=',
                        'numberdetails.id'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'choosen_numbers.user_id'
                    )
                    // ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
                    ->where("numberdetails.status", $request->simtype)
                    // ->where("numberdetails.status", 'Available')
                    // ->groupBy('numberdetails.type')
                    ->get();
                //
                // $data = numberdetail::wherestatus('Available')->get();
                return view('number.number-dtl-report', compact('data'));
            }
        } else if ($request->simtype == 'Hold') {

            $data = numberdetail::select("numberdetails.*", "choosen_numbers.agent_group", "users.*", "choosen_numbers.id as cid")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->LeftJoin(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'choosen_numbers.user_id'
                )
                ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
                ->where("numberdetails.status", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
            //
            // $data = numberdetail::wherestatus('Available')->get();
            return view('number.number-dtl-report', compact('data'));
        } else if ($request->simtype == 'Available') {

            $data = numberdetail::select("numberdetails.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
            //
            // $data = numberdetail::wherestatus('Available')->get();
            return view('number.number-dtl-report', compact('data'));
        } else if ($request->simtype == 'Reserved') {
            // return $request->simtype;
            $data = numberdetail::select("numberdetails.*", "choosen_numbers.agent_group")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
                ->where("numberdetails.status", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
            //
            // $data = numberdetail::wherestatus('Available')->get();
            return view('number.number-dtl-report', compact('data'));
        }
    }
    public function ReservedNum(Request $request)
    {
        // return $request;
        $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "choosen_numbers.created_at as datetime")
            ->Join(
                'choosen_numbers',
                'choosen_numbers.number_id',
                '=',
                'numberdetails.id'
            )
            // ->where("","")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("choosen_numbers.status", 1)
            ->where("numberdetails.book_type", 0)
            ->whereIn("numberdetails.channel_type", ['ConnectCC'])
            ->where("choosen_numbers.user_id", $request->simtype)
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)p
            // ->whereMonth('choosen_numbers.created_at', '=', Carbon::now()->subMonth()->month)
            ->whereDate('choosen_numbers.created_at', '=', Carbon::today())

            // ->where("numberdetails.type", $request->simtype)
            // ->where("numberdetails.status", 'Available')
            // ->groupBy('numberdetails.type')
            ->get();
        //
        // $data = numberdetail::wherestatus('Available')->get();
        return view('number.number-dtl-rcvd', compact('data'));
    }
    //
    public function user_selected_number(Request $request)
    {
        // $myrole = auth()->user()->role;
        $myrole = auth()->user()->multi_agentcode;
        $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "choosen_numbers.created_at as datetime", "users.name as agent_name")
            ->Join(
                'choosen_numbers',
                'choosen_numbers.number_id',
                '=',
                'numberdetails.id'
            )
            ->Join(
                'users',
                'users.id',
                'choosen_numbers.user_id'
            )
            // ->where("","")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("choosen_numbers.status", 1)
            ->where("numberdetails.book_type", 0)
            ->whereIn("numberdetails.channel_type", ['ConnectCC'])
            // ->where("users.agent_code", auth()->user()->agent_code)
            ->when($myrole, function ($query) use ($myrole) {
                if ($myrole == '1') {
                    // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                    //
                    return $query->where('users.agent_code', auth()->user()->agent_code);
                } else {
                    return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                }
                // else if($myrole == 'KHICordination'){
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
                // else {
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
            })
            ->whereMonth('choosen_numbers.updated_at', Carbon::now()->month)
            // ->whereMonth('choosen_numbers.created_at', '=', Carbon::now()->subMonth()->month)
            ->whereDate('choosen_numbers.created_at', '=', Carbon::today())

            // ->where("numberdetails.type", $request->simtype)
            // ->where("numberdetails.status", 'Available')
            // ->groupBy('numberdetails.type')
            ->get();
        //
        // $data = numberdetail::wherestatus('Available')->get();
        return view('dashboard.user-selected-number', compact('data'));
    }
    //
    public function guest_res(Request $request)
    {
        // return $request;
        if (auth()->user()->region == 'Pak') {

            $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "choosen_numbers.created_at as datetime")
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                // ->where("","")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("choosen_numbers.status", 1)
                ->where("numberdetails.book_type", 0)
                ->wheInre("numberdetails.channel_type", ['ConnectCC'])
                ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
                ->where('numberdetails.region', 'Pak')

                // ->where("numberdetails.type", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
        } else {
            $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "choosen_numbers.created_at as datetime")
                ->Join(
                    'choosen_numbers',
                    'choosen_numbers.number_id',
                    '=',
                    'numberdetails.id'
                )
                // ->where("","")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("choosen_numbers.status", 1)
                ->where("numberdetails.book_type", 0)
                ->wheInre("numberdetails.channel_type", ['ConnectCC'])
                ->where("choosen_numbers.agent_group", auth()->user()->agent_code)
                ->where('numberdetails.region', '!=', 'Pak')
                // ->where("numberdetails.type", $request->simtype)
                // ->where("numberdetails.status", 'Available')
                // ->groupBy('numberdetails.type')
                ->get();
        }
        //
        // $data = numberdetail::wherestatus('Available')->get();
        return view('number.guest-number', compact('data'));
    }
    //

    public function ajaxRequest()
    {
        return view('ajaxRequest');
    }
    public function PlanType(Request $request)
    {
        // return $request;
        $country  = $request->country;
        if (auth()->user()->id == 1 || auth()->user()->agent_code == 'AAMT' || auth()->user()->agent_code == 'CC4') {
            // if(auth()->user()->id == 1){
            $users = plan::select("plans.id", "plans.plan_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                ->where('plans.plan_category', $request['id'])
                // ->where('plans.status','1')
                ->when($country, function ($query) use ($country) {
                    if ($country == 'United Arab Emirates') {
                        return $query->whereIn('plans.is_uae', [0, 1]);
                    } else {
                        return $query->where('plans.is_uae', 1);
                    }
                })
                // ->where('plans.is_uae',$request->country)
                ->get();
        } else if (auth()->user()->agent_code == 'CC11' || auth()->user()->agent_code == 'CC4') {
            // if(auth()->user()->id == 1){
            $users = plan::select("plans.id", "plans.plan_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                ->where('plans.plan_category', $request['id'])
                // ->where('plans.status','1')
                ->where('plans.monthly_payment', '>=', 200)
                ->when($country, function ($query) use ($country) {
                    if ($country == 'United Arab Emirates') {
                        return $query->whereIn('plans.is_uae', [0, 1]);
                    } else {
                        return $query->where('plans.is_uae', 1);
                    }
                })
                // ->where('plans.is_uae',$request->country)
                ->get();
        } else {


            $users = plan::select("plans.id", "plans.plan_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                ->where('plans.plan_category', $request['id'])
                ->where('plans.status', '1')
                ->when($country, function ($query) use ($country) {
                    if ($country == 'United Arab Emirates') {
                        return $query->whereIn('plans.is_uae', [0, 1])
                            ->where('plans.status', '1');
                    }
                    //             else {
                    //         return $query->where('plans.status',0);
                    //         // ->where('plans.is_uae', 0)
                    // }
                })
                ->get();
        }
        // return $users;
        $collection = collect($users);
        $abcd =  $collection->pluck('plan_name', 'id');
        return response()->json($abcd, 200);
    }
    public function PlanType2(Request $request)
    {
        // return $request;
        $users = plan::select("plans.id", "plans.plan_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')

            ->where('plans.plan_category', $request['id'])
            ->where('plans.status', '1')
            ->get();
        $collection = collect($users);
        $abcd =  $collection->pluck('plan_name', 'id');
        return response()->json($abcd, 200);
    }
    public function ajaxRequestPost(Request $request)
    {
        $ajaxName = $request['ajaxName'];
        // return $ajaxName;
        if ($ajaxName == 'PlanFetch') {
            // return $request['plan_name'];
            $plan = plan::whereid($request->plan_name)->get();
            // return json($plan);
            return $plan;
        } else if ($ajaxName == 'ElifePlanFetch') {
            $plan = elife_plan::whereid($request->plan_name)->get();
            // return json($plan);
            return $plan;
        }
        // $input = $request->all();
        // \Log::info($input);

        // return response()->json(['success' => 'Got Simple Ajax Request.']);
    }
    public function ajaxRequestItPost(Request $request)
    {
        // $ajaxName = $request['ajaxName'];
        // // return $ajaxName;
        // return $request->id;
        // if($ajaxName == 'PlanFetch'){
        //     // return $request['plan_name'];
        //  $plan = itproductplans::wheretype($request->id)->get();
        $plan = itproductplans::select("itproductplans.id", "itproductplans.name")
            ->where("itproductplans.type", $request->id)
            ->get();
        $collection = collect($plan);
        $abcd =  $collection->pluck('name', 'id');

        return response()->json($abcd, 200);
        // return json($plan);

        // return response()->json(['success' => 'Got Simple Ajax Request.']);
    }
    public function ajaxRequestItPlan(Request $request)
    {
        $plan = itproductplans::select("itproductplans.pricing", "itproductplans.description")
            ->where("itproductplans.id", $request->id)
            ->get();
        $collection = collect($plan);
        $abcd =  $collection->pluck('pricing', 'description');
        return response()->json($abcd, 200);
    }
    //
    public function search_number(Request $request)
    {
        //
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;

            $data = numberdetail::select('numberdetails.*')
                ->where('number', 'LIKE', "%$search%")
                ->wherein('channel_type', ['ConnectCC'])
                ->first();
            if ($data->region != 'Pak') {
                $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type,' ',channel_type ) as number"))
                    ->where('number', 'LIKE', "%$search%")
                    ->wherein('channel_type', ['ConnectCC'])
                    ->get();
            } else {
                $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type,' :Region => ',region,' ',channel_type ) as number"))
                    ->where('number', 'LIKE', "%$search%")
                    ->wherein('channel_type', ['ConnectCC'])
                    ->get();
            }
            // if($data->count() > 0){}
            // $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type ) as number"))
            //     ->where('number', 'LIKE', "%$search%")
            //     ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //     ->get();
        }
        return response()->json($data);
        //
    }
    public function search_customer_number(Request $request)
    {
        //
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;

            // $data = numberdetail::select('numberdetails.*')
            // ->where('number', 'LIKE', "%$search%")
            // ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            // ->first();
            // if($data->region != 'Pak'){
            //     $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type ) as number"))
            //         ->where('number', 'LIKE', "%$search%")
            //         ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //         ->get();
            // }
            // else{
            //     $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type,' :Region => ',region ) as number"))
            //         ->where('number', 'LIKE', "%$search%")
            //         ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //         ->get();

            // }
            $data = lead_sale::select(DB::raw("CONCAT(customer_number, ' ' , lead_no, ' ', status_name) as number"))
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->where('customer_number', 'LIKE', "%$search%")->get();
            // ->where('lead_sales.customer_number')
            // if($data->count() > 0){}
            // $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type ) as number"))
            //     ->where('number', 'LIKE', "%$search%")
            //     ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //     ->get();
        }
        return response()->json($data);
        //
    }
    public function search_lead_number(Request $request)
    {
        //
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;

            // $data = numberdetail::select('numberdetails.*')
            // ->where('number', 'LIKE', "%$search%")
            // ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            // ->first();
            // if($data->region != 'Pak'){
            //     $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type ) as number"))
            //         ->where('number', 'LIKE', "%$search%")
            //         ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //         ->get();
            // }
            // else{
            //     $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type,' :Region => ',region ) as number"))
            //         ->where('number', 'LIKE', "%$search%")
            //         ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //         ->get();

            // }
            $data = lead_sale::select(DB::raw("CONCAT(customer_number, ' ' , lead_no, ' ', status_name) as number"))
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->where('lead_no', 'LIKE', "%$search%")->get();
            // ->where('lead_sales.customer_number')
            // if($data->count() > 0){}
            // $data = numberdetail::select(DB::raw("CONCAT(number,' ', status, ' ', call_center,' ',passcode,' ',type ) as number"))
            //     ->where('number', 'LIKE', "%$search%")
            //     ->wherein('channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
            //     ->get();
        }
        return response()->json($data);
        //
    }
    //
    public function number_search_lead(Request $request)
    {
        // return $request;
        $n = explode(' ', $request->number);
        $number = $n['0'];
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.selected_number', 'LIKE', "%$number%")
            // ->where('lead_sales.selected_number','LIKE',"%number%")
            // ->where('lead_sales.lead_type', $request['simtype'])
            // ->where('status_codes.status_name', $request['status'])
            // ->whereBetween('lead_sales.date_time', [$request['start'], $request['end']])
            // ->where('lead_salses.date_time', '>=', $request['start'])
            // ->where('lead_sales.date_time', '>=', $request['end'])
            ->get();
        return view('ajax.ajax', compact('operation'));
    }
    public function lead_search(Request $request)
    {
        // return $request;
        $n = explode(' ', $request->number);
        $number = $n['1'];
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.lead_no', $number)
            ->groupBy('lead_sales.id')
            // ->where('lead_sales.selected_number','LIKE',"%number%")
            // ->where('lead_sales.lead_type', $request['simtype'])
            // ->where('status_codes.status_name', $request['status'])
            // ->whereBetween('lead_sales.date_time', [$request['start'], $request['end']])
            // ->where('lead_salses.date_time', '>=', $request['start'])
            // ->where('lead_sales.date_time', '>=', $request['end'])
            ->get();
        return view('ajax.ajax', compact('operation'));
    }
    public function number_original_lead(Request $request)
    {
        // return $request;
        $n = explode(' ', $request->number);
        $number = $n['0'];
        $operation = numberdetail::select('numberdetails.*')
            ->where('number', 'LIKE', "%$number%")
            ->first();
        $call_center = \App\Models\call_center::where('status', '1')->get();
        return view('ajax.edit-number', compact('operation', 'call_center'));
    }
    public function number_original_lead_reserved(Request $request)
    {
        // return $request;
        $n = explode(' ', $request->number);
        $number = $n['0'];
        $operation = numberdetail::select('numberdetails.*')
            ->where('number', 'LIKE', "%$number%")
            ->first();
        $call_center = \App\Models\call_center::where('status', '1')->get();
        return view('ajax.edit-number-reserved', compact('operation', 'call_center'));
    }
    public function NumberEdit(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [ // <---

            'number' => 'required|numeric',
            'number_category' => 'required',
            'number_passcode' => 'required|numeric',
            'call_center' => 'required',
            'number_status' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        // $data = numberdetail::findorfail($request->id)
        // return "zom";
        $data = numberdetail::updateOrCreate(['id' => $request->id], [
            // $data = numberdetail::create([
            'number' => $request->number,
            'status' => $request->status,
            'type' => $request->number_category,
            'passcode' => $request->number_passcode,
            'call_center' => $request->call_center,
            'identity' => $request->number_status,
            'channel_type' => $request->channel_partner,
            // 'channel_type' => 'TTF',
        ]);
        return response()->json(['success' => 'Updated']);
    }
    public function number_reserved_search_lead(Request $request)
    {
        // return $request;
        $n = explode(' ', $request->number);
        $number = $n['0'];
        $data = numberdetail::select("numberdetails.*", 'users.name', 'users.agent_code as agent_group', 'choosen_numbers.id as cid', 'users.email')
            // $user =  DB::table("subjects")->select('subject_name', 'id')

            ->Join(
                'choosen_numbers',
                'choosen_numbers.number_id',
                '=',
                'numberdetails.id'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'choosen_numbers.user_id'
            )
            ->where('numberdetails.number', 'LIKE', "%$number%")
            ->where('choosen_numbers.status', 1)
            // ->where('lead_sales.selected_number','LIKE',"%number%")
            // ->where('lead_sales.lead_type', $request['simtype'])
            // ->where('status_codes.status_name', $request['status'])
            // ->whereBetween('lead_sales.date_time', [$request['start'], $request['end']])
            // ->where('lead_salses.date_time', '>=', $request['start'])
            // ->where('lead_sales.date_time', '>=', $request['end'])
            ->get();
        return view('ajax.ajax-res-num', compact('data'));
    }
    public function customer_number_search_lead(Request $request)
    {
        // return $request;
        $n = explode(' ', $request->number);
        $number = $n['0'];
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.customer_number', 'LIKE', "%$number%")
            // ->where('lead_sales.selected_number','LIKE',"%number%")
            // ->where('lead_sales.lead_type', $request['simtype'])
            // ->where('status_codes.status_name', $request['status'])
            // ->whereBetween('lead_sales.date_time', [$request['start'], $request['end']])
            // ->where('lead_salses.date_time', '>=', $request['start'])
            // ->where('lead_sales.date_time', '>=', $request['end'])
            ->get();
        return view('ajax.ajax', compact('operation'));
    }
    //
    public function dataAjax(Request $request)
    {
        // return $request;
        $agent_code = auth()->user()->agent_code;
        $pid = $request->pid;
        if ($request->id == 'my') {
            $data = [];
            if ($request->has('q')) {
                $search = $request->q;
                $data = numberdetail::select("number", "number")
                    ->Join(
                        'choosen_numbers',
                        'choosen_numbers.number_id',
                        '=',
                        'numberdetails.id'
                    )
                    ->where('choosen_numbers.status', '1')
                    ->where('number', 'LIKE', "%$search%")
                    ->where('user_id', auth()->user()->id)
                    // ->where('book_type', 0)
                    ->where(function ($query) use ($agent_code) {
                        $query->where('call_center', $agent_code)
                            ->orWhere('call_center', 'default');
                    })
                    ->when($agent_code, function ($q) use ($agent_code, $pid) {
                        if ($agent_code == 'Salman') {
                            return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                        } else if ($agent_code == 'CC10') {
                            return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        } else if ($agent_code == 'AAMT') {
                            //  $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial'])
                            return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                ->where('numberdetails.channel_type', 'ExpressDial');
                            // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                            // return $q->where('numberdetails.identity', 'EidSpecial');
                        } else {
                            return $q->where('channel_type', $pid);
                        }
                    })

                    // ->orWhere('email', 'LIKE', '%' . $search_query . '%')

                    // ->where('lead_sales.selected_number','=!', $search)
                    // ->where('type', $request->id)
                    // ->where('numstatus','Available')
                    ->get();
            }
            return response()->json($data);
        } else {

            $dataNum = choosen_number::select("choosen_numbers.id")
                ->Join(
                    'numberdetails',
                    'numberdetails.id',
                    'choosen_numbers.number_id'
                )
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("choosen_numbers.user_id", auth()->user()->id)
                ->where('numberdetails.channel_type', $request->type)
                // ->where()
                ->where("choosen_numbers.status", 1)
                // ->where("numberdetails.status", 'Available')
                ->count();
            if ($dataNum <= 1000) {
                $data = [];

                if ($request->has('q')) {
                    $search = $request->q;
                    if (auth()->user()->region == 'Pak') {

                        $data = numberdetail::select("number", "number")
                            // ->Join(
                            //     'choosen_numbers',
                            //     'choosen_numbers.number_id',
                            //     '=',
                            //     'numberdetails.id'
                            // )
                            ->where('number', 'LIKE', "%$search%")
                            // ->where('user_id', auth()->user()->id)
                            ->where('channel_type', $request->pid)
                            ->where('type', $request->id)
                            ->where('status', 'Available')
                            // ->whereNull('numberdetails.region')
                            ->where(function ($query) use ($agent_code) {
                                $query->where('call_center', $agent_code)
                                    ->orWhere('call_center', 'default');
                                // ->where('region', auth()->user()->region);
                            })
                            ->get();
                    } else {

                        $data = numberdetail::select("number", "number")
                            // ->Join(
                            //     'choosen_numbers',
                            //     'choosen_numbers.number_id',
                            //     '=',
                            //     'numberdetails.id'
                            // )
                            ->where('number', 'LIKE', "%$search%")
                            // ->where('user_id', auth()->user()->id)
                            ->where('channel_type', $request->pid)
                            ->where('type', $request->id)
                            ->where('status', 'Available')
                            ->whereNull('numberdetails.region')
                            ->where(function ($query) use ($agent_code) {
                                $query->where('call_center', $agent_code)
                                    ->orWhere('call_center', 'default');
                                // ->where('region', auth()->user()->region);
                            })
                            ->when($agent_code, function ($q) use ($agent_code) {
                                if ($agent_code == 'Salman') {
                                    return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                                } else if ($agent_code == 'CC10') {
                                    return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                    // return $q->where('numberdetails.identity', 'EidSpecial');
                                } else if ($agent_code == 'AAMT') {
                                    return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22']);
                                    // return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial']);
                                    // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                    // return $q->where('numberdetails.identity', 'EidSpecial');
                                }
                            })
                            ->get();
                    }
                }
                return response()->json($data);
            }
        }
    }
    public function dataAjaxMultiChannel(Request $request)
    {
        // return $request;
        $agent_code = auth()->user()->agent_code;
        if ($request->id == 'my') {
            $data = [];
            if ($request->has('q')) {
                $search = $request->q;
                $data = numberdetail::select("number", "number")
                    ->Join(
                        'choosen_numbers',
                        'choosen_numbers.number_id',
                        '=',
                        'numberdetails.id'
                    )
                    ->where('choosen_numbers.status', '1')
                    ->where('number', 'LIKE', "%$search%")
                    ->where('user_id', auth()->user()->id)
                    // ->where('channel_type', $request->pid)
                    // ->where('book_type', 0)
                    ->where(function ($query) use ($agent_code) {
                        $query->where('call_center', $agent_code)
                            ->orWhere('call_center', 'default');
                    })

                    // ->orWhere('email', 'LIKE', '%' . $search_query . '%')

                    // ->where('lead_sales.selected_number','=!', $search)
                    // ->where('type', $request->id)
                    // ->where('numstatus','Available')
                    ->get();
            }
            return response()->json($data);
        } else {


            $dataNum = choosen_number::select("choosen_numbers.id")
                ->Join(
                    'numberdetails',
                    'numberdetails.id',
                    'choosen_numbers.number_id'
                )
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("choosen_numbers.user_id", auth()->user()->id)
                // ->wheInre('numberdetails.channel_type',['TTF','ExpressDial','MWH','Ideacorp'])
                // ->where()
                ->where("choosen_numbers.status", 1)
                // ->where("numberdetails.status", 'Available')
                ->count();
            if ($dataNum <= 1000) {
                $data = [];

                if ($request->has('q')) {
                    $search = $request->q;
                    if (auth()->user()->region == 'Pak') {

                        $data = numberdetail::select("number", "number")
                            // ->Join(
                            //     'choosen_numbers',
                            //     'choosen_numbers.number_id',
                            //     '=',
                            //     'numberdetails.id'
                            // )
                            ->where('number', 'LIKE', "%$search%")
                            // ->where('user_id', auth()->user()->id)
                            // ->where('channel_type', $request->pid)
                            ->where('type', $request->id)
                            ->where('status', 'Available')
                            // ->whereNull('numberdetails.region')
                            ->where(function ($query) use ($agent_code) {
                                $query->where('call_center', $agent_code)
                                    ->orWhere('call_center', 'default');
                                // ->where('region', auth()->user()->region);
                            })
                            ->get();
                    } else {
                        // return $request;
                        $pid = $request->pid;
                        $data = numberdetail::select("number", "number")
                            // ->Join(
                            //     'choosen_numbers',
                            //     'choosen_numbers.number_id',
                            //     '=',
                            //     'numberdetails.id'
                            // )
                            ->where('number', 'LIKE', "%$search%")
                            // ->where('user_id', auth()->user()->id)
                            // ->where('channel_type', $request->pid)
                            ->where('type', $request->id)
                            ->where('status', 'Available')
                            ->whereNull('numberdetails.region')
                            ->where(function ($query) use ($agent_code) {
                                $query->where('call_center', $agent_code)
                                    ->orWhere('call_center', 'default');
                                // ->where('region', auth()->user()->region);
                            })
                            ->when($agent_code, function ($q) use ($agent_code, $pid) {
                                if ($agent_code == 'Salman') {
                                    return $q->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                                } else   if ($agent_code == 'CC10') {
                                    return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                    // return $q->where('numberdetails.identity', 'EidSpecial');
                                } else if ($agent_code == 'AAMT') {
                                    return $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial', 'Sil1Dec22ED', 'NYJAN1GLD22', 'NYJAN1SILV22'])
                                        // $q->whereNotIn('numberdetails.identity', ['August Num', 'EidSpecial'])
                                        ->where('numberdetails.channel_type', 'ExpressDial');
                                    // return $q->where('numberdetails.identity', '!=', 'EidSpecial');
                                    // return $q->where('numberdetails.identity', 'EidSpecial');
                                } else {
                                    // return $q->where('channel_type', $pid);
                                }
                            })
                            ->get();
                    }
                }
                return response()->json($data);
            }
        }
    }
    public function SaleReport(request $request)
    {
        // return $request;
        $reportName = $request->reportName;
        $userId = $request->userid;
        return view('dashboard.sale.sale-report', compact('reportName', 'userId'));
    }
    public function DtlReport(request $request)
    {
        // return $request;
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->where('status_codes.status_name', $request->reportName)
            ->where('lead_sales.lead_type', $request->ProductType)
            ->where('lead_sales.saler_id', $request->userid)
            ->get();
        // $reportName = $request->reportName;
        // $userId = $request->userid;
        return view('dashboard.sale.sale-dtl', compact('operation'));
    }
    public function ReportByDay(request $request)
    {
        // return $date = Carbon::now()->startOfDay;
        // return $request;
        if (auth()->user()->role == 'Manager') {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                // ->where('users.id', $request['userid'])
                ->where('lead_sales.lead_type', $request['simtype'])
                ->where('status_codes.status_name', $request['status'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereBetween('lead_sales.date_time', [$request['start'], $request['end']])
                // ->where('lead_salses.date_time', '>=', $request['start'])
                // ->where('lead_sales.date_time', '>=', $request['end'])
                ->get();
            return view('ajax.ajax', compact('operation'));
        } else if (auth()->user()->role == 'Admin' || auth()->user()->role == 'SuperAdmin') {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                // ->where('users.id', $request['userid'])
                ->where('lead_sales.lead_type', $request['simtype'])
                ->where('status_codes.status_name', $request['status'])
                ->whereBetween('lead_sales.date_time', [$request['start'], $request['end']])
                // ->where('lead_salses.date_time', '>=', $request['start'])
                // ->where('lead_sales.date_time', '>=', $request['end'])
                ->get();
            return view('ajax.ajax', compact('operation'));
        }
    }
    public function report(Request $request)
    {
        // return "boom boom";
        // return $request->userid;
        // return $request['start'];
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->where('users.id', $request['userid'])
            ->where('lead_sales.status', $request['reportName'])
            ->get();
        return view('ajax.ajax', compact('operation'));
    }
    public function ChannelReport(Request $request)
    {
        // return "boom boom";
        // return $request->userid;
        // return $request['start'];

        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            ->Join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->where('users.id', $request['userid'])
            ->where('lead_sales.status', $request['reportName'])
            // ->where('lead_sales.channel_type', $request['type'])
            // ->whereMonth('lead_sales.created_at')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.updated_at', Carbon::now()->year)


            ->get();
        return view('ajax.ajax', compact('operation'));
    }
    public function BookNum(request $request)
    {
        // return $request;
        if (auth()->user()->agent_code == 'ARF') {
            $ct = 5;
        } elseif (auth()->user()->agent_code == 'CC5') {
            $ct = 5;
        } else if (auth()->user()->agent_code == 'CC3' || auth()->user()->agent_code == 'CC9') {
            $ct = 2;
        } else {
            $ct = 1;
        }
        // return json()->
        // return "2";
        // return ""
        $data = numberdetail::select("numberdetails.id")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("numberdetails.id", $request->id)
            ->where("numberdetails.status", 'Available')
            ->count();
        $dataNum = choosen_number::select("choosen_numbers.id")
            ->Join(
                'numberdetails',
                'numberdetails.id',
                '=',
                'choosen_numbers.number_id'
            )
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("choosen_numbers.user_id", auth()->user()->id)
            // ->where("numberdetails.status", 'Reserved')
            // ->where('numberdetails.book_type','0')
            ->whereIn('choosen_numbers.status', ['1', '2'])
            ->where("numberdetails.channel_type", $request->Channel)
            ->whereDate('choosen_numbers.created_at', Carbon::today())
            ->count();
        if ($data < 1) {
            // notify()->error('Number Already Reserved');
            // return 0;
            return response()->json(['success' => "Number Already Reserved"]);
        } else if ($ct > $dataNum) {
            $d = numberdetail::findorfail($request->id);
            $d->status = 'Reserved';
            $d->save();
            $k = choosen_number::create([
                'number_id' => $request->id,
                'user_id' => auth()->user()->id,
                'status' => '1',
                'agent_group' => auth()->user()->agent_code,
                // 'ip_address' => Request::ip(),
                'date_time' => Carbon::now()->toDateTimeString(),
            ]);
            $log = choosen_number_log::create([
                // 'number'
                'number_id' => $request->id,
                'user_id' => auth()->user()->id,
                'agent_group' => auth()->user()->agent_code,
            ]);
            notify()->success('Number Succesfully Reserved');
            return 1;
        } else if ($dataNum >= $ct) {
            // "error";
            notify()->error('You Already Cross Limit, please revive old');
            return 2;
        }
    }
    public function SaveChanges(Request $request)
    {
        if ($request->sim_type == 'HomeWifi') {
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_elife' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // if (empty($request->audio)) {
            //     // return "s";
            //     return response()->json(['error' => ['Please Attach Audio']]);
            //     // return response()->json(['error' => ]);
            //     // notify()->error('Please Submit Audio');
            //     // return redirect()->back()
            //     //     ->withInput();
            // }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_elife);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(
            //     ",",
            //     $request->activation_rate_new
            // );

            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\Models\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            // if ($wp->role == 'TTF-SALE') {
            //     $status_code = '1.01';
            // } else {
            //     $status_code = '1.01';
            // }
            $d->update([
                // 'status' => '1.01',
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                'area' => $request->area,
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
            ]);
            // }
            return response()->json(['success' => "Successfully Saved"]);
        }
        if ($request->sim_type == 'MNP') {
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_mnp' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // if (empty($request->audio)) {
            //     // return "s";
            //     return response()->json(['error' => ['Please Attach Audio']]);
            //     // return response()->json(['error' => ]);
            //     // notify()->error('Please Submit Audio');
            //     // return redirect()->back()
            //     //     ->withInput();
            // }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(
            //     ",",
            //     $request->activation_rate_new
            // );
            $planName = $request->plan_mnp;

            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\Models\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            // if ($wp->role == 'TTF-SALE') {
            //     $status_code = '1.01';
            // } else {
            //     $status_code = '1.01';
            // }
            $d->update([
                // 'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                // 'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                // 'remarks' => $request->remarks_process_new,
                'verify_agent' => auth()->user()->id,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
            ]);
        } else {


            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // if (empty($request->audio)) {
            //     // return "s";
            //     return response()->json(['error' => ['Please Attach Audio']]);
            //     // return response()->json(['error' => ]);
            //     // notify()->error('Please Submit Audio');
            //     // return redirect()->back()
            //     //     ->withInput();
            // }
            // $planName = $request->plan_name;
            $planName = implode(',', $request->plan_new);
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(
                ",",
                $request->activation_rate_new
            );

            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\Models\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            // if ($wp->role == 'TTF-SALE') {
            //     $status_code = '1.01';
            // } else {
            //     $status_code = '1.01';
            // }
            $d->update([
                // 'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'verify_agent' => auth()->user()->id,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
            ]);
        }
        return response()->json(['success' => "Successfully Saved"]);
    }
    public function leadreject(Request $request)
    {
        // return $request;
        $d = lead_sale::findorfail($request->lead_id);
        $d->status = '1.15';
        $d->remarks = $request->reject_comment_new;
        $d->save();
        $de = verification_form::findorfail($request->ver_id);
        $de->status = '1.15';
        $de->save();
        if ($request->reject_comment_new == 'Already Active') {
            $status = 'Active';
        } else {
            $status = 'Available';
        }
        if ($d->lead_type == 'HomeWifi' || $d->lead_type == 'MNP') {
            $ntc = lead_sale::select('call_centers.notify_email')
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'call_centers',
                    'call_centers.call_center_code',
                    'users.agent_code'
                )
                ->where('lead_sales.id', $request->lead_id)->first();
            //
            if ($ntc) {
                $to = [
                    [
                        'email' =>
                        'Coordinationalert@gmail.com',
                        'name' => 'Junaid',
                    ],
                    [
                        'email' => $ntc->notify_email, 'name' => 'Coordinator'
                    ]
                ];
            } else {
                $to = [
                    [
                        'email' =>
                        'Coordinationalert@gmail.com',
                        'name' => 'Junaid',
                    ],
                    [
                        'email' =>
                        'cc8alert@gmail.com',
                        'name' => 'CC8 Coordinator',
                    ]
                ];
            }
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $d->lead_no,
                'customer_name' =>  $d->customer_name,
                'customer_number' => $d->customer_number,
                'selected_number' => $d->selected_number,
                'sim_type' => $request->simtype,
                'saler_name' => $d->saler_name,
                'remarks' => $request->reject_comment_new,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
            // return $to;
            // $to = [
            // [
            //     'email' =>
            //     'parhakooo@gmail.com',
            //     'name' => 'Manager',
            // ]
            // ];
            // $details = "";
            $subject = "";

            \Mail::to($to)
                // ->cc(['salmanahmed334@gmail.com'])
                ->send(new \App\Models\Mail\RejectMail($details, $subject));
            notify()->success('Lead Succesfully rejected');
            // return 1;
            return redirect(route('verification.final-cord-lead'));
        } else {


            // return $d->selected_number;
            foreach (explode(',', $d->selected_number) as $lp) {
                $k = numberdetail::where('number', $lp)
                    ->first();
                $dj = numberdetail::findorfail($k->id);
                // if($dj->type == 'Platinum' || $dj->type == 'Gold'){
                //     $dj->status = $status;
                //     $dj->channel_type = 'ExpressDial';
                //     $dj->book_type = '0';
                //     $dj->save();
                // }else{
                $dj->status = $status;
                $dj->book_type = '0';
                $dj->save();
                // }
                // return $dj->id;
                $dek = choosen_number::where('number_id', $dj->id)->first();
                // return $dek->id;;
                if ($dek) {
                    $dej = choosen_number::findorfail($dek->id);
                    // $dej->status = '1';
                    $de->delete();
                    // $dej->save();
                }
            }
            $ntc = lead_sale::select('call_centers.notify_email')
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'call_centers',
                    'call_centers.call_center_code',
                    'users.agent_code'
                )
                ->where('lead_sales.id', $request->lead_id)->first();
            //
            if ($ntc) {
                $to = [
                    [
                        'email' =>
                        'Coordinationalert@gmail.com',
                        'name' => 'Junaid',
                    ],
                    [
                        'email' => $ntc->notify_email, 'name' => 'Coordinator'
                    ]
                ];
            } else {
                $to = [
                    [
                        'email' =>
                        'Coordinationalert@gmail.com',
                        'name' => 'Junaid',
                    ],
                    [
                        'email' =>
                        'cc8alert@gmail.com',
                        'name' => 'CC8 Coordinator',
                    ]
                ];
            }
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $d->lead_no,
                'customer_name' =>  $d->customer_name,
                'customer_number' => $d->customer_number,
                'selected_number' => $d->selected_number,
                'sim_type' => $request->simtype,
                'saler_name' => $d->saler_name,
                'remarks' => $request->reject_comment_new,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
            // return $to;
            // $to = [
            // [
            //     'email' =>
            //     'parhakooo@gmail.com',
            //     'name' => 'Manager',
            // ]
            // ];
            // $details = "";
            $subject = "";

            \Mail::to($to)
                // ->cc(['salmanahmed334@gmail.com'])
                ->send(new \App\Models\Mail\RejectMail(
                    $details,
                    $subject
                ));
            notify()->success('Lead Succesfully rejected');
            // return 1;
            return redirect(route('verification.final-cord-lead'));
        }
        // $k->id;
        // if ($k) {
        //     $dj= numberdetail::findorfail($k->id);
        //     $dj->status = 'Reserved';
        //     $dj->book_type = '0';
        //     $dj->save();
        //     // return $dj->id;
        //     $dek = choosen_number::where('number_id', $dj->id)->first();
        //     // return $dek->id;;
        //     if ($dek) {
        //         $dej = choosen_number::findorfail($dek->id);
        //         $dej->status = '1';
        //         // $de->delete();
        //         $dej->save();
        //     }
        // }
        // return "b";
        // $d->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);

    }
    public function CordinationFollow(Request $request)
    {
        // return $request;
        $d = lead_sale::findorfail($request->lead_id);
        $d->status = '1.16';
        $d->remarks = $request->followup_remarks . 'By ' . auth()->user()->id;
        $d->save();
        $de = verification_form::findorfail($request->ver_id);
        $de->status = '1.16';
        $de->save();
        // $d->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Lead Succesfully Follow up');
        // return 1;
        return redirect(route('verification.final-cord-lead'));
    }
    public function ActivationFollow(Request $request)
    {
        // return $request;
        $d = lead_sale::findorfail($request->lead_id);
        $d->status = '1.17';
        $d->remarks = $request->followup_remarks . 'By ' . auth()->user()->id;
        $d->save();
        $de = verification_form::findorfail($request->ver_id);
        $de->status = '1.17';
        $de->save();
        // $d->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Lead Succesfully Follow up');
        // return 1;
        return redirect(route('activation.index'));
    }
    public function RevNum(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Available';
        $d->save();
        $de = choosen_number::findorfail($request->cid);
        $de->delete();
        // $d->status = 'Available';
        // $d->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Retrive');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    public function RevNum2(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Available';
        $d->save();
        // $de = choosen_number::findorfail($request->cid);
        // // $d->status = 'Available';
        // $de->delete();
        // $d->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Retrive');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    public function HoldNum(request $request)
    {
        //    HOLD NUMBER
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Hold';
        $d->save();
        $de = choosen_number::findorfail($request->cid);
        $de->status = '4';
        $de->save();

        notify()->success('Number Succesfully Forward');
        return 1;

        // HOLD NUMBER 4

    }
    public function AssignLead(request $request)
    {
        //    HOLD NUMBER
        // return $request;
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Forward';
        $d->save();
        $de = choosen_number::findorfail($request->cid);
        $de->status = '5';
        $de->save();
        choosen_number_location::create([
            'lead_id' => $request->cid,
            'location_url' => $request->google_url,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => 1,
        ]);
        notify()->success('Number Succesfully Assign');
        return 1;



        // HOLD NUMBER 4

    }
    // 55.3832735
    // 25.2778584
    public function NumberActivation(request $request)
    {
        // return $request;
        //
        if (auth()->user()->role == 'SpecialActivation') {
            // $operation = multisale
            // return "Multi Sale";
            $operation = multisale::select('multisales.*')
                ->LeftJoin(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'multisales.status'
                )
                ->where('multisales.status', '1.20')
                ->get();

            // $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.lat", "lead_locations.lng")
            //    // $user =  DB::table("subjects")->select('subject_name', 'id')
            // //    ->Join(
            // //        'timing_durations',
            // //        'timing_durations.lead_no',
            // //        '=',
            // //        'verification_forms.lead_no'
            // //    )
            //    ->Join(
            //        'lead_sales',
            //        'lead_sales.id',
            //        '=',
            //        'verification_forms.lead_no'
            //    )
            //    ->Join(
            //        'remarks',
            //        'remarks.lead_no',
            //        '=',
            //        'verification_forms.lead_no'
            //    )
            //    ->Join(
            //        'status_codes',
            //        'status_codes.status_code',
            //        '=',
            //        'verification_forms.status'
            //    )
            //    ->Join(
            //        'lead_locations',
            //        'lead_locations.lead_id',
            //        '=',
            //        'verification_forms.lead_no'
            //    )
            //    ->where('verification_forms.status', '1.10')
            //    ->where('lead_locations.assign_to', auth()->user()->id)
            //    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            //    // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            //    ->groupBy('verification_forms.lead_no')
            //    // -order('updated_at', 'desc')
            //    ->latest()
            //    ->get();
            // ->get();
            $user_lat = $request->lat;
            $user_lng = $request->lng;
            // $user_lat = '23'; $user_lng = '23';
            return view('number.number-list-activation', compact('operation', 'user_lat', 'user_lng'));
        } else {
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.lat", "lead_locations.lng")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'verification_forms.lead_no'
                )
                ->Join(
                    'lead_sales',
                    'lead_sales.id',
                    '=',
                    'verification_forms.lead_no'
                )
                ->Join(
                    'remarks',
                    'remarks.lead_no',
                    '=',
                    'verification_forms.lead_no'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'verification_forms.status'
                )
                ->Join(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'verification_forms.lead_no'
                )
                ->where('verification_forms.status', '1.10')
                ->where('lead_locations.assign_to', auth()->user()->id)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.updated_at', Carbon::now()->year)

                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                // -order('updated_at', 'desc')
                ->latest()
                ->get();
            // ->get();
            $user_lat = $request->lat;
            $user_lng = $request->lng;
            return view('number.number-list-activation', compact('operation', 'user_lat', 'user_lng'));
        }
        // return view('')
        // return auth()->user()->emirate;
        //    $data = numberdetail::select("numberdetails.*", "choosen_numbers.id as cid", "users.name", "choosen_numbers.created_at as datetime","users.agent_code as UserAgentGroup")
        //         ->Join(
        //             'choosen_numbers',
        //             'choosen_numbers.number_id',
        //             '=',
        //             'numberdetails.id'
        //         )
        //         ->Join(
        //             'users',
        //             'users.id',
        //             '=',
        //             'choosen_numbers.user_id'
        //         )
        //         ->Join(
        //             'choosen_number_locations',
        //             'choosen_number_locations.lead_id',
        //             '=',
        //             'choosen_numbers.id'
        //         )
        //         // ->where("choosen_numbers.status", 4)
        //         ->where("choosen_number_locations.emirate",auth()->user()->emirate)
        //         ->where("choosen_numbers.agent_group",'!=','ARF')
        //         // ->where("","")
        //         // ->where("remarks.user_agent_id", auth()->user()->id)
        //         // ->where("choosen_numbers.user_id", $request->simtype)
        //         // ->where("numberdetails.type", $request->simtype)
        //         // ->where("numberdetails.status", 'Available')
        //         // ->groupBy('numberdetails.type')
        //         ->get();
    }
    public function BulkActive(Request $request)
    {
        // return $request;
        foreach ($request->checkbox as $c) {
            // return $c;
            $d = numberdetail::findorfail($c);
            $d->status = 'Active';
            $d->remarks = auth()->user()->id;
            $d->save();
        }
        return "1";
    }
    public function BulkRevive(Request $request)
    {
        // return $request;
        foreach ($request->checkbox as $c) {
            $d = numberdetail::findorfail($c);
            $d->status = 'Available';
            $d->remarks = auth()->user()->id;
            $d->save();
        }
        return "1";
    }
    public function VerifyNum(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Active';
        $d->save();
        $de = choosen_number::findorfail($request->cid);
        $de->status = '2';
        // $de->delete();
        $de->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Active and Removed from the list');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    public function reject(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Reserved';
        $d->save();
        $de = choosen_number::findorfail($request->cid);
        $de->status = '1';
        // $de->delete();
        $de->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Reject');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    // public function ManagerReject(request $request){
    //     // return $request;
    //     // $data = numberdetail::select("numberdetails.id")
    //     // // ->where("remarks.user_agent_id", auth()->user()->id)
    //     // ->where("numberdetails.id", $request->id)
    //     // ->where("numberdetails.status", 'Available')
    //     // ->count();
    //     // if($data == 1){
    //     $lead_sale = lead_sale::select('lead_sales.*')
    //     ->Join(
    //         'numberdetails',
    //         'numberdetails.number',
    //         '=',
    //         'lead_sales.selected_number'
    //     )
    //     ->where('numberdetails.id',$request->id)
    //     ->where('lead_sales.status','!=','1.02')
    //     ->first();
    //     if (!empty($lead_sale)) {
    //         $ls = lead_sale::findorfail($lead_sale->id);
    //         $ls->status = '1.04';
    //         $ls->device = $request->id;
    //         $ls->selected_number = '';
    //         $ls->remarks = $request->remarks;
    //         $ls->save();

    //          $ks = verification_form::where('lead_no',$lead_sale->id)->count();
    //         if(!empty($ks)){
    //              $ks = verification_form::where('lead_no',$lead_sale->id)->update(['status'=>'1.04']);
    //         }
    //         // return "s";

    //         remark::create([
    //             'remarks' => $request->remarks,
    //             'lead_status' => '1.04',
    //             'lead_id' => $lead_sale->id,
    //             'lead_no' => $lead_sale->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
    //             'user_agent' => auth()->user()->name,
    //             'source' => 'Manager Reject',
    //             'user_agent_id' => auth()->user()->id,
    //         ]);
    //         $d = numberdetail::findorfail($request->id);
    //         $d->status = 'Available';
    //         $d->book_type = '0';
    //         $d->save();
    //         $de = choosen_number::findorfail($request->cid);
    //         // $de->status = '1';
    //         $de->delete();
    //         // $de->save();
    //         // $k = choosen_number::create([
    //         //     'number_id' => $request->id,
    //         //     'user_id' => auth()->user()->id,
    //         //     'status' => '1',
    //         //     'agent_group' => auth()->user()->agent_code,
    //         // ]);
    //         notify()->success('Number Succesfully Reject with lead');
    //         return 1;
    //     }
    //     else{
    //         $d = numberdetail::findorfail($request->id);
    //         $d->status = 'Available';
    //         $d->book_type = '0';
    //         $d->save();
    //         $de = choosen_number::findorfail($request->cid);
    //         // $de->status = '1';
    //         $de->delete();
    //         // $de->save();
    //         return "1";

    //     }
    //     // }
    //     // notify()->error('Number Already Reserved');
    //     // return 0;

    // }
    //
    public function agent_whatsapp(Request $request)
    {
        // return $request;
        $operation = lead_sale::select("lead_sales.*")
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('sim_type', 'New')
            ->where('lead_sales.status', '1.10')
            ->where('lead_locations.assign_to', $request->id)->get();
        // ->count();
        $count = $operation->count();
        foreach ($operation as $data) {
            $selected_number[] = $data->selected_number;
            $lead_no[] = $data->lead_no;
            // $selected_number[] = $data->selected_number;
        }

        $a = "https://api.whatsapp.com/send?text= *Leads In Process*  %0aDate and Time: " . carbon::now()  . ' %0a %0a';
        for ($i = 0; $i < $count; $i++) {
            if (strpos($selected_number[$i], ",") !== false) {
                foreach (explode(',', $selected_number[$i]) as $key => $k) {
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number_final[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                    // return $k;
                }
                $a .= "Lead No: *$lead_no[$i]*  %0a";
                $a .= "Number Selected M: *$selected_number_final[$i]*  %0a";
                $a .= "Pass Code : *$passcode[$i]*  %0a %0a ";
            } else {
                // return $selected_number[$i];
                $numberd = \App\Models\numberdetail::where('number', $selected_number[$i])->first();
                $selected_number_final[] = $numberd->number;
                $passcode[] = $numberd->passcode;
                $a .= "Lead No: *$lead_no[$i]*  %0a";
                $a .= "Number Selected: *$selected_number_final[$i]*  %0a";
                $a .= "Pass Code:   *$passcode[$i]*  %0a %0a";

                // $numberd::where('number', $selected_number[$i])->first();
                // $a .= "Number Selected: *$selected_number[$i]  %0a";

            }
        }
        return response()->json(['success' => $a]);
        // for ($i = 0; $i < $count; $i++) {
        // $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
        // }
    }
    public function callcenter_whatsapp_forecast(Request $request)
    {
        // return $request;
        $ach = \App\Models\activation_form::select('activation_forms.id')
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                'activation_forms.lead_id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->where('activation_forms.status', '1.02')
            // ->where('lead_sales.lead_type', $type)
            ->where('users.agent_code', trim($request->id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();

        $total = \App\Models\TargetAssignerManager::select('target_assigner_managers.target')
            ->LeftJoin(
                'call_centers',
                'call_centers.id',
                'target_assigner_managers.call_center_id'
            )
            // ->where('manager_targets.sim_type', $type)
            ->where('call_centers.call_center_code', $request->id)
            ->where('target_assigner_managers.month', Carbon::now()->month)
            ->first();
        $days = \Carbon\Carbon::now()->daysInMonth;
        $total_target_day = $total->target / $days;
        $data = date('d');
        // $final_fc = round($data * $total_target_day, 0);
        // $bt = $final_fc - $ach;
        $final_fc = round($total_target_day * $data);
        $bt = $final_fc - $ach;
        $today_date = Carbon::now()->format('d M Y');
        $day_passed = $data;
        $maybe = round($ach / $day_passed * $days);
        if ($bt > 0) {
            $z = "*Behind Target - $bt*  %0a %0a ";
        } else {
            $z = "*Ahead Target - $bt*  %0a %0a ";
        }
        // $today_date
        // return $ach . ' - ' . $total->target;
        $a = "https://api.whatsapp.com/send?text= *MONTHLY FORECAST*  CALL CENTER: " . $request->id  . ' %0a %0a';
        $a .= "*As of $today_date* %0a";
        $a .= "Total Target: *$total->target*  %0a";
        $a .= "Activated Yet: *$ach*  %0a";
        $a .= "Today Forecast : *$final_fc*  %0a %0a";
        $a .= "$z";
        $a .= "*With current sales trend you will be able to achieve $maybe by end of this month.*";
        // $a .= "*Monthly Target - $total->target*  %0a %0a ";
        return response()->json(['success' => $a]);
    }
    //
    public function agent_whatsapp_pending(Request $request)
    {
        // return $request;
        $operation = lead_sale::select("lead_sales.*")
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('appointment_to', '>=', Carbon::now())
            ->where('sim_type', 'New')
            ->where('lead_sales.status', '1.10')
            ->where('lead_locations.assign_to', $request->id)->get();
        // ->count();
        $count = $operation->count();
        foreach ($operation as $data) {
            $selected_number[] = $data->selected_number;
            $lead_no[] = $data->lead_no;
            // $selected_number[] = $data->selected_number;
        }

        $a = "https://api.whatsapp.com/send?text= *DUE FOR UPDATE*  %0aDate and Time: " . carbon::now()  . ' %0a %0a';
        for ($i = 0; $i < $count; $i++) {
            if (strpos($selected_number[$i], ",") !== false) {
                foreach (explode(',', $selected_number[$i]) as $key => $k) {
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number_final[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                    $emriates[] = $numberd->emirates;
                    // return $k;
                }
                $appointment_to[] = $numberd->appointment_to;
                $appointment_from[] = $numberd->appointment_from;
                $a .= "Lead No: *$lead_no[$i]*  %0a";
                $a .= "Number Selected M: *$selected_number_final[$i]*  %0a";
                $a .= "%0a APPOINTMENT TIME %0a *" . date('h:i A', strtotime($appointment_from[$i])) . " TO " . date('h:i A', strtotime($appointment_to[$i])) . "*";
                $a .= "Emirates : *$emriates[$i]*  %0a %0a ";
            } else {
                // return $selected_number[$i];
                $numberd = \App\Models\numberdetail::where('number', $selected_number[$i])->first();
                $selected_number_final[] = $numberd->number;
                $passcode[] = $numberd->passcode;
                $appointment_to[] = $numberd->appointment_to;
                $appointment_from[] = $numberd->appointment_from;
                $emriates[] = $numberd->emirates;
                $a .= "Lead No: *$lead_no[$i]*  %0a";
                $a .= "Number Selected: *$selected_number_final[$i]*  %0a";
                $a .= "%0a APPOINTMENT TIME %0a *" . date('h:i A', strtotime($appointment_from[$i])) . " TO " . date('h:i A', strtotime($appointment_to[$i])) . "*";
                // $a .= "Pass Code:   *$passcode[$i]*  %0a %0a";
                $a .= "Emirates : *$emriates[$i]*  %0a %0a ";

                // $numberd::where('number', $selected_number[$i])->first();
                // $a .= "Number Selected: *$selected_number[$i]  %0a";

            }
        }
        return response()->json(['success' => $a]);
        // for ($i = 0; $i < $count; $i++) {
        // $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
        // }
    }
    //
    public function LeadReAssign(request $request)
    {
        // return $request;
        // $plan = lead_sale::findorfail($request->lead_id);
        // $plan = verification_form::where('lead_no',$request->lead_id)->first();

        $plan2 = verification_form::findorfail($request->ver_id);
        $p3 = lead_location::where('lead_id', $plan2->lead_no)->first();
        $p4 = lead_location::findorfail($p3->id);
        $lead_data = lead_sale::where('id', $plan2->lead_no)
            ->first();
        $userdata = User::select('users.agent_code')->withTrashed()->where('id', $lead_data->saler_id)->first();
        // $userdata = \App\Models\User::/


        $audio = \App\Models\audio_recording::where('lead_no', $lead_data->id)->orderBy('id', 'DESC')->first();
        if ($audio) {
            $audio_file = $audio->audio_file;
            $audio_id = $audio->id;
        } else {
            $audio_file = 'no-audio';
            $audio_id = $code = rand(6, 185);
            // $audio_id =
        }
        if ($lead_data->channel_type == 'ExpressDial') {
            // $audio_url = 'https://salmanrajzdnverison.azureedge.net/callmax'.'/'.'audio/' . $audio_file;
            $audio_url = 'https://cdn.express-dial.com/callmax' . '/' . 'audio/' . $audio_file;
        } else {
            $audio_url = env('CDN_URL') . '/' . 'audio/' . $audio_file;
        }
        // $request->validate([
        //     'link' => 'required|url'
        // ]);
        $code = rand(6, 85);
        $find_unique = \App\Models\shorten_url::where('code', $code)->get()->count();
        if ($find_unique > 0) {
            $code = rand(6, 85) . '92' . $audio_id;
        } else {
            $code = rand(6, 85) . $audio_id;
        }
        $input['link'] = $audio_url;
        $input['code'] = $code;

        $a_a = \App\Models\shorten_url::create($input);

        $plan2->update([
            'assing_to' => $request->assing_to,
        ]);


        $p4->update([
            'assign_to' => $request->assing_to,
        ]);
        $lead_data->status = '1.10';
        $lead_data->save();
        $planName = $lead_data->select_plan;
        $SelNumber = $lead_data->selected_number;
        $activation_charge = $lead_data->pay_charges;
        // $activation_charge = $lead_data->pay_charges;
        $channel_checker = $lead_data->channel_type;
        if ($channel_checker == 'ExpressDial') {

            if ($audio_file == 'no-audio') {
                $audio_message = 'Pre-Verification: %0a **Customer need verification at location**';
            } else {
                $audio_message = 'Pre-Verification:  https://crm.express-dial.com/code/' . $a_a->code . '%0a %0a %0a';
            }
            if ($p4->lat == '0') {
                $lead_message = 'Location %0a **Customer will provide current location to agent directly.**';
            } else {
                $lead_message = 'Lead Location: https://maps.google.com?q=' . $p4->lng . ',' . $p4->lat;
            }
            if (strpos($planName, ",") !== false) {
                // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $planName) as $key => $k) {
                    $plan = \App\Models\plan::findorfail($k);
                    $plan_name[] = $plan->plan_name;
                    $data_gb[] = $plan->data;
                }
                foreach (explode(',', $SelNumber) as $key => $k) {
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                }
                foreach (explode(',', $activation_charge) as $key => $k) {
                    $ac[] = $k;
                }
                $tag = explode(',', $SelNumber);
                $count = count($tag);

                // $pay_status[] = $activation_rate_new[$key];
                // $plan_name['0'];
                // return $activation_charge;



                $a = "https://api.whatsapp.com/send?text= *Partner: EXPRESS DIAL*
                %0a
                *RC-65*
                %0a
                *Assigned Lead*  %0a S.No. $lead_data->id
                %0a $lead_data->date_time
                %0a %0a Name: $lead_data->customer_name
                %0a Number $lead_data->customer_number %0a %0a
                %0a *$lead_data->sim_type*
                %0a";
                for ($i = 0; $i < $count; $i++) {
                    $a .= "Selected: *$selected_number[$i]*  %0a Code = *$passcode[$i]*
                    %0a Plan : *$plan_name[$i]* %0a";
                }
                $a .= "%0a *APPOINTMENT TIME* %0a *" . date('h:i A', strtotime($lead_data->appointment_from)) . " TO " . date('h:i A', strtotime($lead_data->appointment_to)) . "*%0a";
                $a .= "%0a %0aGender: $lead_data->gender
                %0a Emirates: $lead_data->emirates
                %0a Area: $lead_data->area
                %0a Nationality: $lead_data->nationality
                %0a Document: ID $lead_data->additional_document
                %0a Language: $lead_data->language
                %0a Lead Location: $lead_message %0a %0a
                ";
                return response()->json(['success' => $a]);
            } else {
                // return $SelNumber;
                $plan = \App\Models\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();
                if ($lead_data->sim_type == 'HomeWifi') {
                    $plan = \App\Models\elife_plan::findorfail($planName);
                    $plan_name = $plan->plan_name;
                    $a = "https://api.whatsapp.com/send?text= *Partner: EXPRESS DIAL*
                    %0a
                    *RC-65*
                    %0a
                    *Assigned Lead*  %0a S.No. $lead_data->id
                    %0a $lead_data->date_time
                    %0a %0a Name: $lead_data->customer_name
                    %0a Number $lead_data->customer_number %0a %0a
                    %0a *$lead_data->sim_type*
                    %0a Plan: *$plan_name* %0a
                    %0a *APPOINTMENT TIME* %0a *" . date('h:i A', strtotime($lead_data->appointment_from)) . " TO " . date('h:i A', strtotime($lead_data->appointment_to)) . "* %0a%0a
                    %0a Gender: $lead_data->gender
                    %0a Emirates: $lead_data->emirates
                    %0a Area: $lead_data->area
                    %0a Nationality: $lead_data->nationality
                    %0a Document: ID $lead_data->additional_document
                    %0a Language: $lead_data->language
                    %0a Lead Location: $lead_message
                    ";
                } else {
                    if ($numberd) {
                        $selected_number = $numberd->number;
                        $passcode = $numberd->passcode;
                    } else {
                        $selected_number = $lead_data->selected_number;
                        $passcode = 'MNP LEAD';
                    }

                    $plan_name = $plan->plan_name;
                    $data_gb = $plan->data;
                    $selected_number = $selected_number;
                    $passcode = $passcode;
                    $pay_status = $activation_charge;
                    $a = "https://api.whatsapp.com/send?text= *Partner: EXPRESS DIAL*
                %0a
                *RC-65*
                %0a
                *Assigned Lead*  %0a S.No. $lead_data->id
                %0a $lead_data->date_time
                %0a %0a Name: $lead_data->customer_name
                %0a Number $lead_data->customer_number %0a %0a
                %0a *$lead_data->sim_type*
                %0a Selected: *$selected_number*
                %0a Code = *$passcode*
                %0a Plan: *$plan_name* %0a
                %0a *APPOINTMENT TIME* %0a *" . date('h:i A', strtotime($lead_data->appointment_from)) . " TO " . date('h:i A', strtotime($lead_data->appointment_to)) . "* %0a%0a
                %0a Gender: $lead_data->gender
                %0a Emirates: $lead_data->emirates
                %0a Area: $lead_data->area
                %0a Nationality: $lead_data->nationality
                %0a Document: ID $lead_data->additional_document
                %0a Language: $lead_data->language
                %0a Lead Location: $lead_message
                ";
                }
                return response()->json(['success' => $a]);
            }
        } else if ($channel_checker == 'IdeaCorp') {


            if ($lead_data->sim_type == 'HomeWifi') {
                if ($p4->lat == '0') {
                    $lead_message = '%0a **Customer will provide current location to agent directly.**';
                } else {
                    $lead_message = 'https://maps.google.com?q=' . $p4->lng . ',' . $p4->lat;
                }

                $plan = \App\Models\elife_plan::findorfail($planName);
                $plan_name = $plan->plan_name;
                $a = "https://api.whatsapp.com/send?text=*Ideacorp* *RC 35*
                    %0a*Lead No* : $lead_data->id
                    %0aCustomer EID NO:  $lead_data->emirate_num
                    %0aCustomer Name: $lead_data->customer_name
                    %0aCustomer Number $lead_data->customer_number
                    %0aPlan: *$plan_name*
                    %0aPin Map Location: $lead_message
                    %0aAddress: $lead_data->area
                    ";
                return response()->json(['success' => $a]);
                //                 LEAD 40924

                // Customer EID NO:  784-1968-6852654-0
                // Customer Name: Mohamed Mahmoud Mazouz
                // Customer Number:  +971 55 128 8004
                // Plan: 299 wireless 5g
                // Pin map location:  https://maps.google.com?q=24.4687990,54.3720130
                // Address:Abu Dhabi
            } else {

                // return "0";
                // if ($audio_file == 'no-audio') {
                //     $audio_message = 'Pre-Verification: %0a **Customer need verification at location**';
                // } else {
                //     $audio_message = 'Pre-Verification:  https://crm.express-dial.com/code/' . $a_a->code . '%0a %0a %0a';
                // }
                if ($p4->lat == '0') {
                    $lead_message = 'Location %0a **Customer will provide current location to agent directly.**';
                } else {
                    $lead_message = 'Lead Location: https://maps.google.com?q=' . $p4->lng . ',' . $p4->lat;
                }
                // return $lead_data->sim_type;
                if (strpos($planName, ",") !== false) {
                    // list($d, $l) = explode('.', $dm, 2);
                    foreach (explode(',', $planName) as $key => $k) {
                        $plan = \App\Models\plan::findorfail($k);
                        $plan_name[] = $plan->plan_name;
                        $data_gb[] = $plan->data;
                    }
                    foreach (explode(',', $SelNumber) as $key => $k) {
                        $numberd = \App\Models\numberdetail::where('number', $k)->first();
                        $selected_number[] = $numberd->number;
                        $passcode[] = $numberd->passcode;
                    }
                    foreach (explode(',', $activation_charge) as $key => $k) {
                        $ac[] = $k;
                    }
                    $tag = explode(',', $SelNumber);
                    $count = count($tag);

                    // $pay_status[] = $activation_rate_new[$key];
                    // $plan_name['0'];
                    // return $activation_charge;



                    $a = "https://api.whatsapp.com/send?text=*Ideacorp* *RC 35*%0a%0a$userdata->agent_code%0a%0aLead No. $lead_data->id%0aCustomer Name: $lead_data->customer_name%0aCustomer Number $lead_data->customer_number
                %0a";
                    for ($i = 0; $i < $count; $i++) {
                        $a .= "Selected: *$selected_number[$i]*  %0a Password = *$passcode[$i]*
                    %0a Plan : *$plan_name[$i]* %0a";
                    }
                    $a .= "%0aDoc $lead_data->additional_document
                %0aGender: $lead_data->gender
                %0aArea: $lead_data->emirates
                %0aNationality: $lead_data->nationality
                %0aLanguage: $lead_data->language
                %0a%0aLead Location: $lead_message %0a %0a";
                    return response()->json(['success' => $a]);
                } elseif ($lead_data->sim_type == 'HomeWifi') {
                    $plan = \App\Models\elife_plan::findorfail($planName);
                    $plan_name = $plan->plan_name;
                    $a = "https://api.whatsapp.com/send?text=*Ideacorp* *RC 35*%0a
                    *Lead No* : $lead_data->id
                    %0a %0a Name: $lead_data->customer_name
                    %0a Number $lead_data->customer_number %0a %0a
                    %0a *$lead_data->sim_type*
                    %0a Plan: *$plan_name* %0a
                    %0a Docs: $lead_data->additional_document
                    %0a Gender: $lead_data->gender
                    %0a Area: $lead_data->emirates
                    %0a Nationality: $lead_data->nationality
                    %0a Language: $lead_data->language
                    %0a Lead Location: $lead_message
                    ";
                    return response()->json(['success' => $a]);
                } else {
                    // return $SelNumber;
                    $plan = \App\Models\plan::findorfail($planName);
                    $numberd = numberdetail::where('number', $SelNumber)->first();
                    if ($numberd) {
                        $selected_number = $numberd->number;
                        $passcode = $numberd->passcode;
                        $type = $numberd->type;
                    } else {
                        $selected_number = $lead_data->selected_number;
                        $passcode = 'MNP LEAD';
                    }

                    $plan_name = $plan->plan_name;
                    $data_gb = $plan->data;
                    $selected_number = $selected_number;
                    $passcode = $passcode;
                    $pay_status = $activation_charge;
                    $a = "https://api.whatsapp.com/send?text=*Ideacorp* *RC 35*%0a%0a$userdata->agent_code%0a%0aLead No. $lead_data->id%0aCustomer Name: $lead_data->customer_name%0aCustomer Number $lead_data->customer_number%0aSelected: *$selected_number*%0aPassword = *$passcode*%0aPlan: *$plan_name* %0aCategory: $type";
                    // $a .= "%0a*APPOINTMENT TIME* %0a *" . date('h:i A',strtotime($lead_data->appointment_from)) . " TO " . date('h:i A',strtotime($lead_data->appointment_to)) . "*%0a";
                    $a .= "%0aDoc: $lead_data->additional_document%0aGender: $lead_data->gender%0aArea: $lead_data->emirates%0aNationality: $lead_data->nationality%0aLanguage: $lead_data->language%0a%0aLead Location: $lead_message %0a %0a";
                    return response()->json(['success' => $a]);
                }
            }
        } else if ($channel_checker == 'MWH') {
            // return "0";
            // if ($audio_file == 'no-audio') {
            //     $audio_message = 'Pre-Verification: %0a **Customer need verification at location**';
            // } else {
            //     $audio_message = 'Pre-Verification:  https://crm.express-dial.com/code/' . $a_a->code . '%0a %0a %0a';
            // }
            if ($p4->lat == '0') {
                $lead_message = 'Location %0a **Customer will provide current location to agent directly.**';
            } else {
                $lead_message = 'Lead Location: https://maps.google.com?q=' . $p4->lng . ',' . $p4->lat;
            }
            if (strpos($planName, ",") !== false) {
                // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $planName) as $key => $k) {
                    $plan = \App\Models\plan::findorfail($k);
                    $plan_name[] = $plan->plan_name;
                    $data_gb[] = $plan->data;
                }
                foreach (explode(',', $SelNumber) as $key => $k) {
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                    $type[] = $numberd->type;
                }
                foreach (explode(',', $activation_charge) as $key => $k) {
                    $ac[] = $k;
                }
                $tag = explode(',', $SelNumber);
                $count = count($tag);

                // $pay_status[] = $activation_rate_new[$key];
                // $plan_name['0'];
                // return $activation_charge;



                // $a = "https://api.whatsapp.com/send?text=*Ideacorp* *RC 35*%0a%0a$userdata->agent_code%0a%0aLead No. $lead_data->id%0aCustomer Name: $lead_data->customer_name%0aCustomer Number $lead_data->customer_number
                // %0a";
                // for ($i = 0; $i < $count; $i++) {
                //     $a .= "Selected: *$selected_number[$i]*  %0a Password = *$passcode[$i]*
                //     %0a Plan : *$plan_name[$i]* %0a";
                // }
                // $a .= "%0aDoc $lead_data->additional_document
                // %0aGender: $lead_data->gender
                // %0aArea: $lead_data->emirates
                // %0aNationality: $lead_data->nationality
                // %0aLanguage: $lead_data->language
                // %0a%0aLead Location: $lead_message %0a %0a";
                $a = "https://api.whatsapp.com/send?text=*POSTPAID SALE LEAD* %0aDate: $lead_data->date_time%0a%0aCustomer Name: $lead_data->customer_name %0a%0aMobile No $lead_data->customer_number %0a%0a";
                for ($i = 0; $i < $count; $i++) {
                    $a .= "%0aPackage: *$plan_name[$i]* %0a%0a%0aType of: $type[$i]%0a: *$selected_number[$i]* %0aPasscode :*$passcode[$i]*%0a%0a";
                    // $a .= "Selected: *$selected_number[$i]*  %0a Password = *$passcode[$i]*
                    // %0a Package : *$plan_name[$i]* %0a";
                }
                $a .= "%0adocuments: $lead_data->additional_document%0aEmirates:$lead_data->emirates($lead_data->area)%0a*Available time*: *" . date('h:i A', strtotime($lead_data->appointment_from)) . "*%0a%0a Cp: MWH45%0aLead No: $lead_data->id";
                return response()->json(['success' => $a]);
            } else {
                // return $SelNumber;
                $plan = \App\Models\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();
                if ($numberd) {
                    $selected_number = $numberd->number;
                    $passcode = $numberd->passcode;
                    $type = $numberd->type;
                } else {
                    $selected_number = $lead_data->selected_number;
                    $passcode = 'MNP LEAD';
                }

                $plan_name = $plan->plan_name;
                $data_gb = $plan->data;
                $selected_number = $selected_number;
                $passcode = $passcode;
                $pay_status = $activation_charge;
                $a = "https://api.whatsapp.com/send?text=*POSTPAID SALE LEAD* %0aDate: $lead_data->date_time%0a%0aCustomer Name: $lead_data->customer_name%0a%0aMobile No $lead_data->customer_number %0a%0a%0aPackage: *$plan_name* %0a%0a%0aType of: $type %0a: $selected_number  %0aPasscode :*$passcode*%0a%0a%0adocuments: $lead_data->additional_document%0aEmirates:$lead_data->emirates($lead_data->area)%0a*Available time*: *" . date('h:i A', strtotime($lead_data->appointment_from)) . "*%0a%0a Cp: MWH45%0aLead No: $lead_data->id";
                return response()->json(['success' => $a]);
            }
        } else {

            if ($audio_file == 'no-audio') {
                $audio_message = 'Pre-Verification %0a **Customer need verification at location**';
            } else {
                $audio_message = 'Pre-Verification:  https://soft.riuman.com/code/' . $a_a->code . '%0a %0a %0a';
            }
            if ($p4->lat == '0') {
                $lead_message = 'Location %0a **Customer will provide current location to agent directly.**';
            } else {
                $lead_message = 'Lead Location: https://maps.google.com?q=' . $p4->lng . ',' . $p4->lat;
            }
            if (strpos($planName, ",") !== false) {
                // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $planName) as $key => $k) {
                    $plan = \App\Models\plan::findorfail($k);
                    $plan_name[] = $plan->plan_name;
                    $data_gb[] = $plan->data;
                }
                foreach (explode(',', $SelNumber) as $key => $k) {
                    $numberd = \App\Models\numberdetail::where('number', $k)->first();
                    $selected_number[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                }
                foreach (explode(',', $activation_charge) as $key => $k) {
                    $ac[] = $k;
                }
                $tag = explode(',', $SelNumber);
                $count = count($tag);

                // $pay_status[] = $activation_rate_new[$key];
                // $plan_name['0'];
                // return $activation_charge;



                $a = "https://api.whatsapp.com/send?text= *TTF0097*  %0a *Assigned Lead*
                %0a Lead No: $lead_data->lead_no %0a
                %0a Generic ID: $lead_data->id %0a
                Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a";
                for ($i = 0; $i < $count; $i++) {
                    $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a";
                }
                $a .= "%0a *APPOINTMENT TIME* %0a *" . date('h:i A', strtotime($lead_data->appointment_from)) . " TO " . date('h:i A', strtotime($lead_data->appointment_to)) . "*%0a";
                $a .= "%0a %0aGender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Area location: $lead_data->area  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a  %0a %0a $lead_message %0a  %0a %0a %0a Note : Please check and confirm all given details with customer before activating the sim as it’s your complete responsibility. %0a Thank You %0a";
                return response()->json(['success' => $a]);
            } else {
                // return $SelNumber;
                $plan = \App\Models\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();
                if ($numberd) {
                    $selected_number = $numberd->number;
                    $passcode = $numberd->passcode;
                } else {
                    $selected_number = $lead_data->selected_number;
                    $passcode = 'MNP LEAD';
                }

                $plan_name = $plan->plan_name;
                $data_gb = $plan->data;
                $selected_number = $selected_number;
                $passcode = $passcode;
                $pay_status = $activation_charge;
                $a = "https://api.whatsapp.com/send?text= *TTF0097*  %0a *Assigned Lead *  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a Number Selected: *$selected_number*  %0a PassCode = *$passcode* %0a Plan selected: *$plan_name* %0a %0a *APPOINTMENT TIME* %0a *" . date('h:i A', strtotime($lead_data->appointment_from)) . " TO " . date('h:i A', strtotime($lead_data->appointment_to)) . "* %0a%0a%0aGender: $lead_data->gender %0a  Emirates location: $lead_data->emirates %0a Area location: $lead_data->area %0a Nationality: $lead_data->nationality  %0aDocument: ID $lead_data->additional_document %0aLanguage: $lead_data->language %0a$lead_message  %0a Note : Please check and confirm all given details with customer before activating the sim as it’s your complete responsibility. %0a Thank You %0a";
                return response()->json(['success' => $a]);
            }
        }
        // return $p4;
        // return $plan2;
        // notify()->success('Lead re assinging done');
        // return redirect()->back();
        // return "1";
        // ->update(['assing_to',$request->assing_to]);

    }
    public function VerifyNum2(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Active';
        $d->save();
        // $de = choosen_number::findorfail($request->cid);
        // $de->status = '2';
        // // $de->delete();
        // $de->save();
        // // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Removed');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    public function ShowGroupLeads($id, $channel)
    {
        $finalRole = auth()->user()->role;
        $myrole = auth()->user()->multi_agentcode;

        if ($id == '1.02') {
            $operation = \App\Models\activation_form::select('activation_forms.*', 'users.name as agent_name')
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    'activation_forms.lead_id'
                )
                ->LeftJoin(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('activation_forms.status', '1.02')
                // ->where('lead_sales.lead_type', '')
                ->when($myrole, function ($query) use ($myrole, $finalRole) {
                    if ($finalRole == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                    } else if ($myrole == '1') {
                        // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                        //
                        return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                    }
                    // else if($myrole == 'KHICordination'){
                    //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                    // }
                    // else {
                    //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                    // }
                })
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                // ->where('users.agent_code', auth()->user()->agent_code)
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)
                ->get();
            // $operation = \App\Models\activation_form::select('activation_forms.*')
            // ->LeftJoin(
            //     'users','users.id','activation_forms.saler_id'
            //     )
            // ->where('status', '1.02')
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month())
            // ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.view-all-active', compact('operation', 'id'));
        } else if ($id == 'followup') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    // ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    // ->OrderBy('lead')
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                //    return "Zoom";
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    // ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'reject') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)


                    // ->OrderBy('lead')
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'verified' || $id == '1.07') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->LeftJoin(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    // ->whereDate('verification_forms.created_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
                // $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*","status_codes.status_name", 'users.name as agent_name')
                // // $user =  DB::table("subjects")->select('subject_name', 'id')
                // ->LeftJoin(
                //     'timing_durations',
                //     'timing_durations.lead_no',
                //     '=',
                //     'lead_sales.id'
                // )
                // ->Join(
                //     'status_codes',
                //     'status_codes.status_code',
                //     '=',
                //     'lead_sales.status'
                // )
                // ->Join(
                //     'users',
                //     'users.id',
                //     '=',
                //     'lead_sales.saler_id'
                // )
                // // ->where('lead_sales.status', '1.06')
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                //     ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                // ->orderBy('lead_sales.updated_at', 'desc')
                // ->get();
                // // $operation = lead_sale::wherestatus('1.01')->get();
                // return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else {
            if (auth()->user()->role == 'Manager' || auth()->user()->role == 'NumberSuperAdmin' || auth()->user()->role == 'Cordination') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole, $finalRole) {
                        if ($finalRole == 'TeamLeader') {
                            return $query->where('users.teamleader', auth()->user()->id);
                        } else if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->where('lead_sales.status', $id)
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            } else if (Auth()->user()->role == 'Elife Manager') {

                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->where('lead_sales.status', $id)
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            }
        }
    }
    //
    public function OurShowGroupLeads($id, $channel, $agent_code)
    {
        if ($id == '1.02') {
            $operation = \App\Models\activation_form::select('activation_forms.*')
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    'activation_forms.lead_id'
                )
                ->LeftJoin(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('activation_forms.status', '1.02')
                // ->where('lead_sales.lead_type', '')
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                ->where('users.agent_code', $agent_code)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->get();
            // $operation = \App\Models\activation_form::select('activation_forms.*')
            // ->LeftJoin(
            //     'users','users.id','activation_forms.saler_id'
            //     )
            // ->where('status', '1.02')
            // ->where('users.agent_code', $agent_code)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month())
            // ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.view-all-active', compact('operation', 'id'));
        } else if ($id == 'followup') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    // ->OrderBy('lead')
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'reject') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    // ->OrderBy('lead')
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'verified' || $id == '1.07') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->LeftJoin(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    // ->whereDate('verification_forms.created_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
                // $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*","status_codes.status_name", 'users.name as agent_name')
                // // $user =  DB::table("subjects")->select('subject_name', 'id')
                // ->LeftJoin(
                //     'timing_durations',
                //     'timing_durations.lead_no',
                //     '=',
                //     'lead_sales.id'
                // )
                // ->Join(
                //     'status_codes',
                //     'status_codes.status_code',
                //     '=',
                //     'lead_sales.status'
                // )
                // ->Join(
                //     'users',
                //     'users.id',
                //     '=',
                //     'lead_sales.saler_id'
                // )
                // // ->where('lead_sales.status', '1.06')
                // ->where('users.agent_code', $agent_code)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                //     ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                // ->orderBy('lead_sales.updated_at', 'desc')
                // ->get();
                // // $operation = lead_sale::wherestatus('1.01')->get();
                // return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else {
            if (auth()->user()->role == 'Manager' || auth()->user()->role == 'NumberSuperAdmin' || auth()->user()->role == 'Cordination') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->where('lead_sales.status', $id)
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            } else if (Auth()->user()->role == 'Elife Manager') {

                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->where('lead_sales.status', $id)
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)

                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            }
        }
    }

    //
    public function ShowGroupLeadsDaily($id, $channel)
    {

        $myrole = auth()->user()->multi_agentcode;

        if ($id == '1.02') {
            $operation = \App\Models\activation_form::select('activation_forms.*', 'users.name as agent_name')
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    'activation_forms.lead_id'
                )
                ->LeftJoin(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('activation_forms.status', '1.02')
                // ->where('lead_sales.lead_type', '')
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                // ->where('users.agent_code', auth()->user()->agent_code)
                ->when($myrole, function ($query) use ($myrole) {
                    if ($myrole == '1') {
                        // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                        //
                        return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                    }
                    // else if($myrole == 'KHICordination'){
                    //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                    // }
                    // else {
                    //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                    // }
                })
                ->whereDate('activation_forms.created_at', Carbon::today())

                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->get();
            // $operation = \App\Models\activation_form::select('activation_forms.*')
            // ->LeftJoin(
            //     'users','users.id','activation_forms.saler_id'
            //     )
            // ->where('status', '1.02')
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month())
            // ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.view-all-active', compact('operation', 'id'));
        } else if ($id == 'followup') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    // ->OrderBy('lead')
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'reject') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'verified' || $id == '1.07') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->LeftJoin(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                    // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereDate('verification_forms.created_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'VUN') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->LeftJoin(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                    ->whereIn('lead_sales.status', ['1.07', '1.09'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                    ->whereYear('verification_forms.created_at', Carbon::now()->year)
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else {
            if (auth()->user()->role == 'Manager' || auth()->user()->role == 'NumberSuperAdmin' || auth()->user()->role == 'Cordination') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->when($myrole, function ($query) use ($myrole) {
                        if ($myrole == '1') {
                            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                            //
                            return $query->where('users.agent_code', auth()->user()->agent_code);
                        } else {
                            return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                        }
                        // else if($myrole == 'KHICordination'){
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                        // else {
                        //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                        // }
                    })
                    // ->where('lead_sales.status', '1.06')
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->where('lead_sales.status', $id)
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            } else if (Auth()->user()->role == 'Elife Manager') {

                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', auth()->user()->agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->where('lead_sales.status', $id)
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            }
        }
    }
    //
    public function OurShowGroupLeadsDaily($id, $channel, $agent_code)
    {
        if ($id == 'followup') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                    ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                    // ->OrderBy('lead')
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.19', '1.20'])
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'reject') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->whereIn('lead_sales.status', ['1.04', '1.15'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else if ($id == 'verified' || $id == '1.07') {
            if (Auth()->user()->role == 'Elife Manager') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    ->LeftJoin(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                    // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02'])
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->whereDate('verification_forms.created_at', Carbon::today())
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            }
        } else {
            if (auth()->user()->role == 'Manager' || auth()->user()->role == 'NumberSuperAdmin' || auth()->user()->role == 'Cordination') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                    ->where('lead_sales.status', $id)
                    ->whereDate('lead_sales.updated_at', Carbon::today())
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                // $operation = lead_sale::wherestatus('1.01')->get();
                return view('dashboard.manager.mygrplead', compact('operation'));
            } else if (Auth()->user()->role == 'Elife Manager') {

                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                    // $user =  DB::table("subjects")->select('subject_name', 'id')
                    ->LeftJoin(
                        'timing_durations',
                        'timing_durations.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->Join(
                        'status_codes',
                        'status_codes.status_code',
                        '=',
                        'lead_sales.status'
                    )
                    ->Join(
                        'users',
                        'users.id',
                        '=',
                        'lead_sales.saler_id'
                    )
                    // ->where('lead_sales.status', '1.06')
                    ->where('users.agent_code', $agent_code)
                    ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                    ->where('lead_sales.status', $id)
                    ->orderBy('lead_sales.updated_at', 'desc')
                    ->get();
                return view('dashboard.manager.mygrpleadElife', compact('operation'));
            }
        }
    }
    //
    public function VerifyNum22(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Active';
        $d->remarks = auth()->user()->id;
        $d->save();
        // $de = choosen_number::findorfail($request->cid);
        // $de->status = '2';
        // // $de->delete();
        // $de->save();
        // // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Removed');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    public function Revert(request $request)
    {
        // return $request;
        // $data = numberdetail::select("numberdetails.id")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->count();
        // if($data == 1){
        $d = numberdetail::findorfail($request->id);
        $d->status = 'Available';
        $d->save();
        $de = choosen_number::findorfail($request->cid);
        $de->status = '3';
        // $de->delete();
        $de->save();
        // $k = choosen_number::create([
        //     'number_id' => $request->id,
        //     'user_id' => auth()->user()->id,
        //     'status' => '1',
        //     'agent_group' => auth()->user()->agent_code,
        // ]);
        notify()->success('Number Succesfully Verified');
        return 1;
        // }
        // notify()->error('Number Already Reserved');
        // return 0;

    }
    public function otp(request $request)
    {
        $data = customerFeedBack::findorfail($request->leadid);
        // $arr_sendRequest['user'] = '20091153';
        $sam = rand(0323, 31231);
        $data->validation_code = $sam;
        $data->save();
        $arr_sendRequest['user'] = '20091153';
        $arr_sendRequest['pwd'] = "Core!5Core";
        $arr_sendRequest['number'] = '971' . substr($data->phone_number, 3);
        $arr_sendRequest['msg'] = "Your One Time OTP IS =>" . $sam;
        $arr_sendRequest['sender'] = "SMS Alert";
        $arr_sendRequest['language'] = "Unicode";
        $k = array($arr_sendRequest);
        $jon = json_encode($k);
        $url = 'https://mshastra.com/sendsms_api_json.aspx';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jon);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = curl_exec($ch);
        notify()->info('OTP RESEND SUCCESS');
    }
    public function vision()
    {
        return view('number.vision');
    }
    public function vision_sr()
    {
        return view('number.vision-sr');
    }
    public function vision_name()
    {
        return view('number.vision-name');
    }
    public function ocr_sr(Request $request)
    {
        // return $request;
        if ($file = $request->file('front_img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('front_img')));
            $image2 = file_get_contents($request->file('front_img'));
            //prepare request
            // AzureCodeStart
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd

            // StartLocalStorage
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $name = $originalFileName;
            $file->move('documents', $name);
            // EndLocalStorage
            // AzureCodeEnd
            Session::put('sr_no', $name); //to put the session value
            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeature("TEXT_DETECTION");
            $gcvRequest = new GoogleCloudVision([$request],  env('GOOGLE_CLOUD_KEY'));
            //send annotation request
            $response = $gcvRequest->annotate();
            //  $string =  json_encode(["description" => $response->responses[0]->textAnnotations[0]->description]);
            // ech
            $string = $response->responses[0]->textAnnotations[0]->description;
            $string = preg_replace('/\s+/', ' ', $string) . '<br>';
            $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
            $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
            $regexJs = '#\\{ame:\\}(.+)\\{/Nationality\\}#s';

            foreach (explode(' ', $string) as $id) {
                echo $id . '<br>';
                // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
                // print_r($matches);
                // }
                // preg_match($regex2, $id, $matches2);
                $expr = '#^[0-9\.:\-\/]+$#';
                if (preg_match($expr, $id, $match) == 1) {
                    echo '###' . $match[0];
                } else {
                    // echo ".";
                }

                // // if match, show VALID
                // if (count($matches2) == 1
                // ) {
                //     echo '###' . $id;
                // } else {
                //     // echo "{$id} INVALID</br>";
                // }
            }
            // echo $string['description'];
            // foreach (explode(' ', $string) as $id) {
            //     // echo $id . '<br>';
            //     preg_match($regex, $id, $matches);

            //     // // if match, show VALID
            //     if (count($matches) == 1) {
            //         // echo "SSS";
            //         // echo $matches['0'];
            //         echo '###' . "{$id}";
            //     }
            //     // else {
            //     //     // echo "{$string} INVALID</br>";
            //     // }
            // }
        }
        // if ($file = $request->file('front_img')) {
        //     $mytime = Carbon::now();
        //     $ext =  $mytime->toDateTimeString();
        //     $name = $ext . '-' . $file->getClientOriginalName();
        //     // $name = Str::slug($name, '-');

        //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
        //     // $name1 = Str::slug($name, '-');

        //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
        //     // $name2 = Str::slug($name, '-');

        //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        //     $file->move('img', $name);
        //     $input['path'] = $name;
        //     $k = (new TesseractOCR('img/' . $name))
        //         // ->digits()
        //         // ->hocr()
        //         // ->quiet()
        //         //
        //         // ->tsv()

        //         // ->pdf()

        //         // ->lang('eng', 'jpn', 'spa')
        //         // ->whitelist(range('A', 'Z'))
        //         // ->whitelist(range(0,9,'-'))
        //         ->whitelist(range(0,9), ' /:')

        //         ->run();
        //     $string = rtrim($k);
        //      $string = preg_replace('/\s+/', ' ', $k);
        //     $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
        //     foreach (explode(' ', $string) as $id) {
        //         // echo $id . '<br>';
        //         // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
        //         // print_r($id);
        //         // }
        //         if(strlen($id) > 4){
        //             echo '###'.$id ;
        //         }
        //         // preg_match($regex2, $id, $matches2);

        //         // if match, show VALID
        //         // if (count($matches2) == 1) {
        //         //     echo '###' . $id;
        //         // } else {
        //         //     // echo "{$id} INVALID</br>";
        //         // }
        //     }

        // }
    }
    public function OnlineStatus(Request $request)
    {
        $type = $request->type;
        $users = User::all();
        return view('dashboard.online-status', compact('users', 'type'));
    }
    public function OCR(Request $request)
    {
        if ($file = $request->file('front_img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('front_img')));
            $image2 = file_get_contents($request->file('front_img'));
            //prepare request
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            //
            $name = $originalFileName;
            //
            $file->move('documents', $name);
            // AzureCodeStart
            // AzureCodeEnd
            Session::put('back_image', $name); //to put the session value
            //
            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeature("TEXT_DETECTION");
            $gcvRequest = new GoogleCloudVision([$request],  'AIzaSyBMeil9pvJHiW-1nxYU54BKyN9I3xM6aYQ');
            //send annotation request
            $response = $gcvRequest->annotate();
            $string =  json_encode(["description" => $response->responses[0]->textAnnotations[0]->description]);
            // ech
            // return $response;
            if (!empty($response)) {

                $string = $response->responses[0]->textAnnotations[0]->description;
                $string = preg_replace('/\s+/', ' ', $string) . '<br>';
                $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
                $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
                $regexJs = '#\\{ame:\\}(.+)\\{/Nationality\\}#s';

                foreach (explode(' ', $string) as $id) {
                    // echo $id . '<br>';
                    // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
                    // print_r($matches);
                    // }
                    preg_match($regex2, $id, $matches2);

                    // if match, show VALID
                    if (count($matches2) == 1) {
                        echo '###' . $id;
                    } else {
                        // echo "{$id} INVALID</br>";
                    }
                }
                // echo $string['description'];
                if (preg_match('/Name:(.*?)Nation/', $string, $match) == 1) {
                    echo '###' . $match[1] . '<br>';
                }
                foreach (explode(' ', $string) as $id) {
                    // echo $id . '<br>';
                    preg_match($regex, $id, $matches);

                    // // if match, show VALID
                    if (count($matches) == 1) {
                        // echo "SSS";
                        // echo $matches['0'];
                        echo '###' . "{$id}";
                    }
                    // else {
                    //     // echo "{$string} INVALID</br>";
                    // }
                }
                echo '###' . "{$name}";
            }
        }
        // return $request;
        // $fileName = time() . '_' . $request->file->getClientOriginalName();
        // if ($file = $request->file('front_img')) {
        // $ext = date('d-m-Y-H-i');
        // $mytime = Carbon::now();
        // $ext =  $mytime->toDateTimeString();
        // $name = $ext . '-' . $file->getClientOriginalName();
        // // $name = Str::slug($name, '-');

        // // $name1 = $ext . '-' . $file1->getClientOriginalName();
        // // $name1 = Str::slug($name, '-');

        // // $name2 = $ext . '-' . $file2->getClientOriginalName();
        // // $name2 = Str::slug($name, '-');

        // // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        // $file->move('img', $name);
        // $input['path'] = $name;
        // $k = (new TesseractOCR('img/'.$name))
        //     // ->digits()
        //     // ->hocr()
        //     // ->quiet()
        //     //
        //     // ->tsv()

        //     // ->pdf()

        //     // ->lang('eng', 'jpn', 'spa')
        //     // ->whitelist(range('A', 'Z'))
        //     // ->whitelist(range(0,9,'-'))
        //     // ->whitelist(range(0,9), '-/@')

        // ->run();
        //   $string = rtrim($k);
        //  echo $string = preg_replace('/\s+/', ' ', $k);

        // // echo $l = str_replace(" ","@",$k);
        // // echo $l['0'];
        // // echo $k .'<br> ' . 'Sa';
        // $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
        // $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
        // // const str = 'The first sentence. Some second sentence. Third sentence and the names are John, Jane, Jen. Here is the fourth sentence about other stuff.'

        // // $regexJs = '/Name: ([^.]+)*(\1)/';
        // $regexJs = '#\\{Name:\\}(.+)\\{/Nationality\\}#s';
        // // $str = 'United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLL';
        // if (preg_match('/Name:(.*?)Nationality/', $string, $match) == 1) {
        //     echo 'Name: ' . $match[1] . '<br>';
        // }
        // // if (preg_match('/Nationality(.*?)/', $string, $match) == 1) {
        // //     echo 'Nationality: ' . $match[1] . '<br>';
        // // }


        // foreach (explode(' ', $string) as $id) {
        //     preg_match($regex, $id, $matches);

        //     // if match, show VALID
        //     if (count($matches) == 1) {
        //         echo 'Emirate ID:' . "{$id} VALID</br>";
        //     } else {
        //         // echo "{$id} INVALID</br>";
        //     }
        // }
        // foreach (explode(' ', $string) as $id) {
        //     // echo $id . '<br>';
        //     // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
        //     // print_r($matches);
        //     // }
        //     preg_match($regex2, $id, $matches2);

        //     // if match, show VALID
        //     if (count($matches2) == 1) {
        //         echo '###' . $id;
        //     } else {
        //         // echo "{$id} INVALID</br>";
        //     }
        // }

        // }
        // return $fileName = time() . '.' . $request->file->extension();
        // return view('number.vision');
        // echo (new TesseractOCR('mixed-languages.png'))
        // ->lang('eng', 'jpn', 'spa')
        // ->run();
        // echo (new TesseractOCR('img/text.png'))
        // ->lang('eng', 'jpn', 'spa')
        // ->run();
        // $ocr = new TesseractOCR();
        // $ocr->image('img/text.png');
        // $ocr->run();
        // return "s";
        // echo $ocr;
        // return $ocr;
        // return IdentityDocuments::parse($request);
    }
    public function ocr_back_new(Request $request)
    {
        if ($file = $request->file('BackNewImg')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('BackNewImg')));
            $image2 = file_get_contents($request->file('BackNewImg'));
            //prepare request
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            //
            $name = $originalFileName;
            //
            // $file->move('documents', $name);
            // AzureCodeStart
            // AzureCodeEnd
            Session::put('back_image', $name); //to put the session value
            echo '###' . $name;
        }
    }
    public function ocr_name(Request $request)
    {
        if ($file = $request->file('front_img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('front_img')));
            $image2 = file_get_contents($request->file('front_img'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $name = $originalFileName;
            $file->move('documents', $name);
            Session::put('front_image', $name);
            //to put the session value

            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeature("TEXT_DETECTION");
            $gcvRequest = new GoogleCloudVision([$request],  'AIzaSyAoPgkL2yzcbaUEoYpanDN9j4t7LjjDukY');
            //send annotation request
            $response = $gcvRequest->annotate();
            // dd($response);
            //  $string =  json_encode(["description" => $response->responses[0]->textAnnotations[0]->description]);
            // ech
            if (!empty($response)) {
                $string = $response->responses[0]->textAnnotations[0]->description;
                $string = preg_replace('/\s+/', ' ', $string) . '<br>';
                $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
                $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
                $regexJs = '#\\{Name:\\}(.+)\\{/Nationality\\}#s';
                // dd($string);
                foreach (explode(' ', $string) as $id) {
                    // echo $id . '<br>';
                    // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
                    // print_r($matches);
                    // }
                    preg_match($regex2, $id, $matches2);

                    // if match, show VALID
                    if (count($matches2) == 1) {
                        echo '###' . $id;
                    } else {
                        // echo "{$id} INVALID</br>";
                    }
                }
                // echo $string['description'];
                if (preg_match('/ame:(.*?)ation/', $string, $match) == 1) {
                    echo '###' . $match[1] . '<br>';
                }
                foreach (explode(' ', $string) as $id) {
                    // echo $id . '<br>';
                    preg_match($regex, $id, $matches);

                    // // if match, show VALID
                    if (count($matches) == 1) {
                        // echo "SSS";
                        // echo $matches['0'];
                        echo '###' . "{$id}";
                    }
                    // else {
                    //     // echo "{$string} INVALID</br>";
                    // }
                }
                echo '###' . "{$name}";
            }
        }
        // return $request;
        // $fileName = time() . '_' . $request->file->getClientOriginalName();
        // if ($file = $request->file('front_img')) {
        //     // $ext = date('d-m-Y-H-i');
        //     $mytime = Carbon::now();
        //     $ext =  $mytime->toDateTimeString();
        //     $name = $ext . '-' . $file->getClientOriginalName();
        //     // $name = Str::slug($name, '-');

        //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
        //     // $name1 = Str::slug($name, '-');

        //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
        //     // $name2 = Str::slug($name, '-');

        //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        //     $file->move('img', $name);
        //     $input['path'] = $name;
        //     $k = (new TesseractOCR('img/'.$name))
        //         // ->digits()
        //         // ->hocr()
        //         // ->quiet()
        //         //
        //         // ->tsv()

        //         // ->pdf()

        //         // ->lang('eng', 'jpn', 'spa')
        //         // ->whitelist(range('A', 'Z'))
        //         // ->whitelist(range(0,9,'-'))
        //         // ->whitelist(range(0,9), '-/@')

        //     ->run();
        //       $string = rtrim($k);
        //      echo $string = preg_replace('/\s+/', ' ', $k);

        //     // echo $l = str_replace(" ","@",$k);
        //     // echo $l['0'];
        //     // echo $k .'<br> ' . 'Sa';
        //     $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
        //     $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
        //     // const str = 'The first sentence. Some second sentence. Third sentence and the names are John, Jane, Jen. Here is the fourth sentence about other stuff.'

        //     // $regexJs = '/Name: ([^.]+)*(\1)/';
        //     $regexJs = '#\\{Name:\\}(.+)\\{/Nationality\\}#s';
        //     // $str = 'United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLL';
        //     // if (preg_match('/Name:(.*?)Nationality/', $string, $match) == 1) {
        //     if (preg_match('/Name:(.*?)Nation/', $string, $match) == 1) {
        //         echo '###'.$match[1];
        //     }
        //     // if (preg_match('/Nationality(.*?)/', $string, $match) == 1) {
        //     //     echo 'Nationality: ' . $match[1] . '<br>';
        //     // }


        //     foreach (explode(' ', $string) as $id) {
        //         preg_match($regex, $id, $matches);

        //         // if match, show VALID
        //         if (count($matches) == 1) {
        //             echo '###'."{$id}";
        //         } else {
        //             // echo "{$id} INVALID</br>";
        //         }
        //     }
        //     // foreach (explode(' ', $string) as $id) {
        //     //     // echo $id . '<br>';
        //     //     // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
        //     //     // print_r($matches);
        //     //     // }
        //     //     preg_match($regex2, $id, $matches2);

        //     //     // if match, show VALID
        //     //     if (count($matches2) == 1) {
        //     //         echo '###' . $id;
        //     //     } else {
        //     //         // echo "{$id} INVALID</br>";
        //     //     }
        //     // }
        //     // preg_match('#\\{FINDME\\}(.+)\\{/FINDME\\}#s', $out, $matches);
        //     // //
        //     //             if(preg_match($regexJs, $string, $matches)){
        //     //                 print_r($matches);
        //     //             }
        //     // if (preg_match("/Name:\s(.*)\Nationality/", $string, $matches1)) {
        //     //     // echo $matches1[1] . "<br />";
        //     //                     print_r($matches);
        //     // }
        //     // $code = preg_quote($string);
        //     //     $k = "United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLLMuhammad Kashif Saleem Uddin";
        //     // if (preg_match("/Name:\s(.*)\sNationality/",$string,$matches1)) {
        //     //     echo $matches1[1] . "<br />";
        //     //                     print_r($matches1);
        //     //                     echo "1";
        //     // }
        //     // return preg_match('/Name:\s(.*)/', $string);


        //     // $startsAt = strpos($string, "{Name:}") + strlen("{Nationality}");
        //     // $endsAt = strpos($string, "{/Nationality}", $startsAt);
        //     // $result = substr($string, $startsAt, $endsAt - $startsAt);

        //     // names = str.match(regex)[1],
        //     //     array = names.split(/,\s*/)

        //     // console.log(array)
        //     // $pattern = '#(?:\G(?!\A)|Name:(?:\s+F)?)\s*\K[\w.]+#';
        //     // // print_r($matches);
        //     // // $txt = "calculated F 15 513 153135 155 125 156 155";
        //     // $txt = "calculated F 15 16 United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number: / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLL";
        //     // echo preg_match_all("/(?:\bName\b|\G(?!^))[^:]*:\K./", $txt, $matches);
        //     // print_r($matches);
        //     // foreach(explode('@', $string) as $info)
        //     // {
        //     // // $str = "http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I";
        //     // foreach (explode('@', $string) as $id) {
        //     //     // echo $id;
        //     //     // $pattern = '#(?:\G(?!\A)|Name:(?:\s+F)?)\s*\K[\w.]+#';
        //     //     // preg_match($pattern, $id, $matches);
        //     //     // print_r($matches);

        //     // }
        //     //     // $string = "SALMAN";
        //     //     // preg_match("/^[a-zA-Z-'\s]+$/", $value);

        //     //     // $rexSafety = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
        //     //     // $rexSafety = "/^[^<,\"@/{}()*$%?=>:|;#]*$/i";
        //     //     if (preg_match('#(?:\G(?!\A)|salmanahmed(?:\s+F)?)\s*\K[\w.]+', $id)) {
        //     //         // var_dump('bad name');
        //     //         echo $id . '<br>';
        //     //     } else {
        //     //         // var_dump('ok');
        //     //     }
        //     // }

        //     //
        // }
        // return $fileName = time() . '.' . $request->file->extension();
        // return view('number.vision');
        // echo (new TesseractOCR('mixed-languages.png'))
        // ->lang('eng', 'jpn', 'spa')
        // ->run();
        // echo (new TesseractOCR('img/text.png'))
        // ->lang('eng', 'jpn', 'spa')
        // ->run();
        // $ocr = new TesseractOCR();
        // $ocr->image('img/text.png');
        // $ocr->run();
        // return "s";
        // echo $ocr;
        // return $ocr;
        // return IdentityDocuments::parse($request);
    }
    // OCR NAME NEW
    public function ocr_name_new(Request $request)
    {
        if ($file = $request->file('front_img')) {
            //convert image to base64
            $image = base64_encode(file_get_contents($request->file('front_img')));
            $image2 = file_get_contents($request->file('front_img'));
            // AzureCodeStart
            $originalFileName = time() . $file->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            //prepare request
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file->getClientOriginalName();
            $name = $originalFileName;
            // $file->move('documents', $name);
            Session::put('front_image', $name);
            //to put the session value

            $request = new AnnotateImageRequest();
            $request->setImage($image);
            $request->setFeature("TEXT_DETECTION");
            $gcvRequest = new GoogleCloudVision([$request],  'AIzaSyBMeil9pvJHiW-1nxYU54BKyN9I3xM6aYQ');
            //send annotation request
            $response = $gcvRequest->annotate();
            // dd($response);
            $string =  json_encode(["description" => $response->responses[0]->textAnnotations[0]->description]);
            // ech
            if (!empty($response)) {
                $string = $response->responses[0]->textAnnotations[0]->description;
                $string = preg_replace('/\s+/', ' ', $string) . '<br>';
                $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
                $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
                $regexJs = '#\\{Name:\\}(.+)\\{/المتحدة\\}#s';
                // dd($string);
                // foreach (explode(' ', $string) as $id) {
                //     // echo $id . '<br>';
                //     // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
                //     // print_r($matches);
                //     // }
                //     preg_match($regex2, $id, $matches2);

                //     // if match, show VALID
                //     if (count($matches2) == 1) {
                //         echo '###' . $id;
                //     } else {
                //         echo "{$id} INVALID</br>";
                //     }
                // }
                // // echo $string['description'];
                // if (preg_match('/ame:(.*?)ation/', $string, $match) == 1) {
                //     echo '###' . $match[1] . '<br>';
                // }

                // Emirate ID Fetching Succesfully
                foreach (explode(' ', $string) as $id) {
                    // echo $id . '<br>';
                    preg_match($regex, $id, $matches);

                    // // if match, show VALID
                    if (count($matches) == 1) {
                        // echo "SSS";
                        // echo $matches['0'];
                        echo '###' . "{$id}";
                    }
                    // else {
                    // echo "{$string} INVALID</br>";
                    // }
                }
                // // Emirate ID Fetching End
                // //// ZOOOM
                // // Name Fetch Done
                if (preg_match('/Name:(.*?)المتحدة/', $string, $match) == 1) {
                    // echo '###' . $match[1] . '<br>';
                    echo '###' . $string_name = preg_replace("/[^a-zA-Z\s]/", "", $match[1]);
                }
                // Name Fetch Done END
                // Emirate Expiry Done
                if (preg_match('/Expiry(.*?)Signature/', $string, $match3) == 1) {
                    // echo '###' . $match[1] . '<br>';
                    // echo $string = preg_replace("/[^a-zA-Z\s]/", "", $match[1]);
                    // $re = '/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
                    // $str = '09/08/2026';
                    // $subst = '';
                    // echo $string = preg_replace($re, "", $match[1]);
                    $expiry_date = preg_replace('/[^0-9\s\.\-\/]/', "", $match3[1]);
                    echo '###' . $replace = str_replace('/4 ', '', $expiry_date);


                    // $result = preg_replace($re, $subst, $match, 1);

                    // echo "The result of the substitution is " . $result;
                } else {
                    echo "failed";
                }
                // Emirate Expiry  Done END
                //// ZOOM END
                // foreach (explode(' ', $string) as $id) {
                //     // echo $id . '<br>';
                //     preg_match($regexJs, $id, $matches);

                //     // // if match, show VALID
                //     if (count($matches) == 1) {
                //         // echo "SSS";
                //         // echo $matches['0'];
                //         echo '###' . "{$id}";
                //     }
                //     // else {
                //     // echo "{$string} INVALID</br>";
                //     // }
                // }
                echo '###' . "{$name}";
            }
        }
        // return $request;
        // $fileName = time() . '_' . $request->file->getClientOriginalName();
        // if ($file = $request->file('front_img')) {
        //     // $ext = date('d-m-Y-H-i');
        //     $mytime = Carbon::now();
        //     $ext =  $mytime->toDateTimeString();
        //     $name = $ext . '-' . $file->getClientOriginalName();
        //     // $name = Str::slug($name, '-');

        //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
        //     // $name1 = Str::slug($name, '-');

        //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
        //     // $name2 = Str::slug($name, '-');

        //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        //     $file->move('img', $name);
        //     $input['path'] = $name;
        //     $k = (new TesseractOCR('img/'.$name))
        //         // ->digits()
        //         // ->hocr()
        //         // ->quiet()
        //         //
        //         // ->tsv()

        //         // ->pdf()

        //         // ->lang('eng', 'jpn', 'spa')
        //         // ->whitelist(range('A', 'Z'))
        //         // ->whitelist(range(0,9,'-'))
        //         // ->whitelist(range(0,9), '-/@')

        //     ->run();
        //       $string = rtrim($k);
        //      echo $string = preg_replace('/\s+/', ' ', $k);

        //     // echo $l = str_replace(" ","@",$k);
        //     // echo $l['0'];
        //     // echo $k .'<br> ' . 'Sa';
        //     $regex = '/^784-[0-9]{4}-[0-9]{7}-[0-9]{1}$/';
        //     $regex2 = '/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/';
        //     // const str = 'The first sentence. Some second sentence. Third sentence and the names are John, Jane, Jen. Here is the fourth sentence about other stuff.'

        //     // $regexJs = '/Name: ([^.]+)*(\1)/';
        //     $regexJs = '#\\{Name:\\}(.+)\\{/Nationality\\}#s';
        //     // $str = 'United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLL';
        //     // if (preg_match('/Name:(.*?)Nationality/', $string, $match) == 1) {
        //     if (preg_match('/Name:(.*?)Nation/', $string, $match) == 1) {
        //         echo '###'.$match[1];
        //     }
        //     // if (preg_match('/Nationality(.*?)/', $string, $match) == 1) {
        //     //     echo 'Nationality: ' . $match[1] . '<br>';
        //     // }


        //     foreach (explode(' ', $string) as $id) {
        //         preg_match($regex, $id, $matches);

        //         // if match, show VALID
        //         if (count($matches) == 1) {
        //             echo '###'."{$id}";
        //         } else {
        //             // echo "{$id} INVALID</br>";
        //         }
        //     }
        //     // foreach (explode(' ', $string) as $id) {
        //     //     // echo $id . '<br>';
        //     //     // if (preg_match_all($regex2, $id, $matches, PREG_PATTERN_ORDER)) {
        //     //     // print_r($matches);
        //     //     // }
        //     //     preg_match($regex2, $id, $matches2);

        //     //     // if match, show VALID
        //     //     if (count($matches2) == 1) {
        //     //         echo '###' . $id;
        //     //     } else {
        //     //         // echo "{$id} INVALID</br>";
        //     //     }
        //     // }
        //     // preg_match('#\\{FINDME\\}(.+)\\{/FINDME\\}#s', $out, $matches);
        //     // //
        //     //             if(preg_match($regexJs, $string, $matches)){
        //     //                 print_r($matches);
        //     //             }
        //     // if (preg_match("/Name:\s(.*)\Nationality/", $string, $matches1)) {
        //     //     // echo $matches1[1] . "<br />";
        //     //                     print_r($matches);
        //     // }
        //     // $code = preg_quote($string);
        //     //     $k = "United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLLMuhammad Kashif Saleem Uddin";
        //     // if (preg_match("/Name:\s(.*)\sNationality/",$string,$matches1)) {
        //     //     echo $matches1[1] . "<br />";
        //     //                     print_r($matches1);
        //     //                     echo "1";
        //     // }
        //     // return preg_match('/Name:\s(.*)/', $string);


        //     // $startsAt = strpos($string, "{Name:}") + strlen("{Nationality}");
        //     // $endsAt = strpos($string, "{/Nationality}", $startsAt);
        //     // $result = substr($string, $startsAt, $endsAt - $startsAt);

        //     // names = str.match(regex)[1],
        //     //     array = names.split(/,\s*/)

        //     // console.log(array)
        //     // $pattern = '#(?:\G(?!\A)|Name:(?:\s+F)?)\s*\K[\w.]+#';
        //     // // print_r($matches);
        //     // // $txt = "calculated F 15 513 153135 155 125 156 155";
        //     // $txt = "calculated F 15 16 United Arab Emirates ay ‘ doa‘Lal Ann Resident Identity Card ID Number: / 42 gli a8) 784-1974-6574140-8 Coa) apls URGAS tame aul! Name: Muhammad Kashif Saleem Uddin Nationality: Pakistan ARAELLLLL";
        //     // echo preg_match_all("/(?:\bName\b|\G(?!^))[^:]*:\K./", $txt, $matches);
        //     // print_r($matches);
        //     // foreach(explode('@', $string) as $info)
        //     // {
        //     // // $str = "http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I";
        //     // foreach (explode('@', $string) as $id) {
        //     //     // echo $id;
        //     //     // $pattern = '#(?:\G(?!\A)|Name:(?:\s+F)?)\s*\K[\w.]+#';
        //     //     // preg_match($pattern, $id, $matches);
        //     //     // print_r($matches);

        //     // }
        //     //     // $string = "SALMAN";
        //     //     // preg_match("/^[a-zA-Z-'\s]+$/", $value);

        //     //     // $rexSafety = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
        //     //     // $rexSafety = "/^[^<,\"@/{}()*$%?=>:|;#]*$/i";
        //     //     if (preg_match('#(?:\G(?!\A)|salmanahmed(?:\s+F)?)\s*\K[\w.]+', $id)) {
        //     //         // var_dump('bad name');
        //     //         echo $id . '<br>';
        //     //     } else {
        //     //         // var_dump('ok');
        //     //     }
        //     // }

        //     //
        // }
        // return $fileName = time() . '.' . $request->file->extension();
        // return view('number.vision');
        // echo (new TesseractOCR('mixed-languages.png'))
        // ->lang('eng', 'jpn', 'spa')
        // ->run();
        // echo (new TesseractOCR('img/text.png'))
        // ->lang('eng', 'jpn', 'spa')
        // ->run();
        // $ocr = new TesseractOCR();
        // $ocr->image('img/text.png');
        // $ocr->run();
        // return "s";
        // echo $ocr;
        // return $ocr;
        // return IdentityDocuments::parse($request);
    }
    // OCR NAME NEW END
    public function manage_cordinator($id)
    {
        // return "bOom";
        // return $id;
        $operation = verification_form::select("lead_sales.id", "verification_forms.id as ver_id", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "users.name as agent_name", "lead_sales.*", "lead_locations.location_url", "lead_locations.lat", "lead_locations.lng")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'remarks',
                'remarks.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'lead_sales.share_with'
            )
            ->where('lead_sales.id', $id)
            ->first();
        // if($remarks)
        // ->where("remarks.user_agent_id", auth()->user()->id)
        // return $operation->lead_id;
        $remarks =  remark::select("remarks.*")
            ->where("remarks.lead_id", $operation->id)
            ->get();
        $countries = \App\Models\country_phone_code::all();
        // $operation = verification_form::wherestatus('1.10')->get();
        $emirates = \App\Models\emirate::all();
        $plans = \App\Models\plan::wherestatus('1')->get();
        $users = \App\Models\User::role('activation')->get();
        $addons = \App\Models\addon::wherestatus('1')->get();
        $device = \App\Models\imei_list::wherestatus('1')->get();

        // $operation = verification_form::whereid($id)->get();
        return view('dashboard.assign-lead-to-activation', compact('operation', 'remarks', 'countries', 'emirates', 'plans', 'users', 'addons', 'device'));
        // return view('dashboar', compact('operation'));
    }
    public function MyLog()
    {
        $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area', 'elife_logs.status', 'elife_logs.remarks')
            ->LeftJoin(
                'elife_bulkers',
                'elife_bulkers.id',
                'elife_logs.number_id'
            )
            ->where('elife_logs.identify', '1')
            // ->where('type', $slug)
            ->where('userid', auth()->user()->id)
            ->get();        //
        return view('dashboard.view-white-log', compact('k'));
    }
    public function ElifeLog()
    {
        $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area', 'elife_logs.status', 'elife_logs.remarks', 'users.name as agent_name', 'elife_logs.updated_at')
            ->LeftJoin(
                'elife_bulkers',
                'elife_bulkers.id',
                'elife_logs.number_id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'elife_logs.userid'
            )
            ->where('elife_logs.identify', '1')
            // ->where('type', $slug)
            // ->where('userid', auth()->user()->id)
            ->get();        //
        return view('dashboard.view-white-log', compact('k'));
    }
    public function ViewElifeUser()
    {
        $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area', 'elife_logs.status', 'elife_logs.remarks', 'users.name as agent_name', 'users.id as userid')
            ->LeftJoin(
                'elife_bulkers',
                'elife_bulkers.id',
                'elife_logs.number_id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'elife_logs.userid'
            )
            ->groupBy('elife_logs.userid')
            // ->where('elife_logs.identify', '0')
            // ->where('type', $slug)
            ->where('users.agent_code', auth()->user()->agent_code)
            ->get();        //
        return view('dashboard.view-user-log', compact('k'));
    }
    public static function ElifeLogWise($userid, $status)
    {
        return $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area', 'elife_logs.status', 'elife_logs.remarks')
            ->LeftJoin(
                'elife_bulkers',
                'elife_bulkers.id',
                'elife_logs.number_id'
            )
            ->where('elife_logs.identify', '1')
            // ->where('type', $slug)
            ->where('elife_logs.userid', $userid)
            ->where('elife_logs.status', $status)
            ->count();        //
    }
    public static function ElifeNoAnswer()
    {
        //
        $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area')
            ->LeftJoin(
                'elife_bulkers',
                'elife_bulkers.id',
                'elife_logs.number_id'
            )
            ->where('elife_logs.identify', '1')
            // ->where('type', $slug)
            ->where('elife_logs.status', 'No Answer')
            ->where('userid', auth()->user()->id)
            ->paginate();        //
        return view('dashboard.add-answer-log', compact('k'));
        // return "B";
        // $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area','elife_logs.status','elife_logs.remarks')
        // ->LeftJoin(
        //     'elife_bulkers',
        //     'elife_bulkers.id',
        //     'elife_logs.number_id'
        // )
        // ->where('elife_logs.identify', '1')
        // ->where('elife_logs.remarks', 'No Answer')
        // ->where('userid', auth()->user()->id)
        // ->get();        //
        // return view('dashboard.view-white-log', compact('k'));
        //
        // $k = elife_log::select('elife_logs.*', 'elife_bulkers.number', 'elife_bulkers.plan', 'elife_bulkers.customer_name as name', 'elife_bulkers.area', 'elife_logs.status', 'elife_logs.remarks')
        // ->LeftJoin(
        //     'elife_bulkers',
        //     'elife_bulkers.id',
        //     'elife_logs.number_id'
        // )
        // ->where('elife_logs.identify', '1')
        //     // ->where('type', $slug)
        // ->where('elife_logs.remarks', 'No Answer')
        // // ->where('userid', auth()->user()->id)

        // ->where('userid', auth()->user()->id)
        // ->get();        //
        // return view('dashboard.view-white-log', compact('k'));
    }
    public function NumberAllCleaner($id, $channel)
    {
        // $data = numberdetail::all();

        $removed = numberdetail::select("numberdetails.*", "users.name", "choosen_numbers.id as cid")
            ->LeftJoin(
                'choosen_numbers',
                'choosen_numbers.number_id',
                '=',
                'numberdetails.id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'choosen_numbers.user_id'
            )
            ->where('numberdetails.status', 'Active')
            ->where('numberdetails.channel_type', $channel)
            ->where('numberdetails.type', $id)
            ->where('numberdetails.remarks', auth()->user()->id)
            ->count();
        $data = numberdetail::select("numberdetails.*", "users.name", "choosen_numbers.id as cid")
            ->LeftJoin(
                'choosen_numbers',
                'choosen_numbers.number_id',
                '=',
                'numberdetails.id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'choosen_numbers.user_id'
            )
            ->where('numberdetails.status', 'Available')
            ->where('numberdetails.channel_type', $channel)
            ->where('numberdetails.type', $id)
            ->get();

        // ->get();
        return view('dashboard.view-all-number-cleaner', compact('data', 'removed'));
    }
    public function AllRemovedNumber()
    {
        // $data = numberdetail::all();

        $removed = numberdetail::select("numberdetails.*", "users.name", "choosen_numbers.id as cid")
            ->LeftJoin(
                'choosen_numbers',
                'choosen_numbers.number_id',
                '=',
                'numberdetails.id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'choosen_numbers.user_id'
            )
            ->where('numberdetails.status', 'Active')
            // ->where('numberdetails.channel_type',$channel)
            // ->where('numberdetails.type',$id)
            ->where('numberdetails.remarks', auth()->user()->id)
            ->get();


        // ->get();
        return view('report.removednumber', compact('removed'));
    }
    //
    public function re_enable(Request $request)
    {
        // return $request;
        $r2 = User::onlyTrashed()->where('email', $request->email)->restore();
        // $r3 = User::findorfail($r2->id);
        // $r3->delete();
        // $r3 = Delete();
        // ->update(['deleted_at',Carbon::now()]);
        $r = \App\Models\request_agent::findorfail($request->id);
        $r->status = '1';
        $r->save();
        notify()->success('User has Been Enabled Succesfully');
        return redirect()->back();
    }
    public function disabled(Request $request)
    {
        // return $request;
        $r2 = User::where('email', $request->email)->first();
        $r3 = User::findorfail($r2->id);
        $r3->delete();
        // $r3 = Delete();
        // ->update(['deleted_at',Carbon::now()]);
        $r = \App\Models\request_agent::where('email', $request->email)->first();
        if ($r) {
            $r->status = '4';
            $r->save();
        }
        notify()->success('User has Been Disabled Succesfully');
        return redirect()->back();
    }
    //
    public function LaterLead(Request $request)
    {
        // return $request;
        //
        $now = Carbon::now();
        $validator = Validator::make($request->all(), [
            'later_date' => 'required|date|before:' . date('Y-m-d', strtotime($now . ' + 30 days')),
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        //
        $lead = lead_sale::findorfail($request->lead_id);
        $lead->status = '1.06';
        $lead->later_date = $request->later_date;
        $lead->save();
        $later = later_leads::create([
            'lead_no' => $request->lead_id,
            'userid' => auth()->user()->id,
            'username' => auth()->user()->name,
            'later_date' => $request->later_date,
            'status' => '1.06',
        ]);
        return response()->json(['success' => 'Number has been updated, Please wait']);
    }
    public function leadstorenew(Request $request)
    {
        // return $request->simtype;
        $ldate = date('h:i A');
        // return $request->remarks_process_new;
        $validator = Validator::make($request->all(), [ // <---
            // 'title' => 'required|unique:posts|max:255',
            // 'body' => 'required',
            'cname' => 'required|string',
            'cnumber' => 'required|string',
            'nation' => 'required',
            'age' => 'required|numeric|min:20|not_in:20',
            'simtype' => 'required',
            'gender' => 'required',
            'emirates' => 'required',
            'emirate_id' => 'required',
            'language' => 'required',
            'plan_new' => 'required',
            'area' => 'required',
            // 'selnumber' => 'required|numeric',
            'activation_charges_new' => 'required',
            'activation_rate_new' => 'required',
            'remarks_process_new' => 'required',
            // 'mytypeval.*' => 'required',
            'selnumber.*' => 'required',
            'plan_new.*' => 'required',
            'activation_charges_new.*' => 'required',
            'activation_rate_new.*' => 'required',
            'assing_to' => 'required',
            'start_date' => 'required',
            // 'start_date' => 'required_if',
            'start_time' => 'required|after:' . $ldate,

        ]);
        $leadchecker = lead_sale::where('customer_number', $request->cnumber)
            ->whereIn('status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21', '1.01', '1.06'])
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.sim_type', 'New')
            ->where('users.agent_code', auth()->user()->agent_code)
            // ->whereDate('lead_sales.created_at',Carbon::now()->today())
            ->first();
        // return $request->emirates;

        // if($request->emirates != 'Sharjah' || $request->emirates != 'Ajman' || $request->emirates != 'Umm ul Quwain'){

        //     $check_num = \App\Models\numberdetail::whereIn('number', [$request->selnumber])->whereIn('identity', ['STDOCTID1', 'SilOct1ID'])->first();
        //     if ($check_num) {
        //         // $channel = 'MWH';
        //         return response()->json(['error' => ['Documents' => ['Number only For Sharjah, Ajman and Umm Ul Quwain.']]], 200);
        //     }
        // }
        // if($request->emirates != 'Sharjah' && $request->emirates != 'Ajman'){

        //     $check_num2 = \App\Models\numberdetail::whereIn('number', [$request->selnumber])->whereIn('identity', ['MWAJSH22JUL2-22'])->first();
        //     if ($check_num2) {
        //         // $channel = 'MWH';
        //         return response()->json(['error' => ['Documents' => ['Number only For Sharjah or Ajman.']]], 200);
        //     }
        // }
        // if($leadchecker){
        //     return response()->json(['error' => ['Documents' => ['Same Customer Number Lead Already Exist, Kindly Contact with IT Admin.']]], 200);
        // }
        // if($request->nation != 'United Arab ')
        // return $request->nation;
        if ($request->nation != 'United Arab Emirates') {
            // return $request->additional_document;
            if ($request->additional_document == 'No Additional Document Required') {
                // return response()->json(['error' => 'Please Choose Documents']);
                return response()->json(['error' => ['Documents' => ['Documents invalid.']]], 200);
            }
            // if($request->plans == 'Gold Plus'){

            // }
            if (strpos(implode(',', $request->plan_new), 'Emirati') !== FALSE) { // Yoshi version
                // echo "Match found";
                // return true;
                return response()->json(['error' => ['Documents' => ['Invalid Package Selection, Selected Package only allowed for UAE Citizen']]], 200);
            }
        }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        // return $planName = $request->plan_new;
        $planName = implode(',', $request->plan_new);
        // return response()->json(['error' => ['Documents' => [$planName]]], 200);

        $SelNumber = implode(",", $request->selnumber);
        $activation_charge = implode(",", $request->activation_charges_new);
        $activation_rate_new = implode(",", $request->activation_rate_new);
        // $test = implode(",", $request->plans);
        if (!empty($request->existing_lead)) {
            $status = '1.14';
            $lead_date = $request->lead_date;
        } else {
            $status = '1.01';
            $lead_date = Carbon::now()->toDateTimeString();
        }
        $choosen_date = $request->start_time;
        $carbon_date = Carbon::parse($choosen_date);
        $second_date  = $carbon_date->addHours(2);
        $last = \App\Models\lead_sale::latest()->first();
        $getfirst = $last->id;
        $lead_no = auth()->user()->agent_code . '-' . $getfirst . '-' . Carbon::now()->format('M') . '-' . now()->year;
        // $lead_no = auth()->user()->agent_code . '-' . $getfirst . '-' . Carbon::now()->format('M') . '-' . now()->year;
        if (auth()->user()->agent_code == 'AAMT') {
            // $channel_type = 'ExpressDial';
            $channel_type = $request->channel_type;
        } else {
            // $channel_type = 'ExpressDial';
            $channel_type = $request->channel_type;
        }
        $data = lead_sale::create([
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'area' => $request->area,
            'nationality' => $request->nation,
            'age' => $request->age,
            'sim_type' => $request->simtype,
            'gender' => $request->gender,
            'lead_type' => 'postpaid',
            'channel_type' => $channel_type,
            'emirates' => $request->emirates,
            'emirate_num' => $request->emirate_number,
            'etisalat_number' => $request->etisalat_number,
            'emirate_id' => $request->emirate_id,
            'language' => $request->language,
            'share_with' => $request->shared_with,
            'additional_document' => $request->additional_document,
            'saler_id' => auth()->user()->id,
            // main
            'selected_number' => $SelNumber,
            'select_plan' => $planName,
            // 'contract_commitment' => $request->status,
            'contract_commitment' => $request->contract_comm_mnp,
            'lead_no' => $lead_no,
            'remarks' => $request->remarks_process_new,
            'status' => $status,
            'saler_name' => auth()->user()->name,
            'pay_status' => $activation_charge,
            'pay_charges' => $activation_rate_new,
            // 'device' => $request->status,
            'date_time' => $lead_date,
            'date_time_follow' => $lead_date,
            'appointment_from' => date('H:i:s', strtotime($choosen_date)),
            'appointment_to' => date('H:i:s', strtotime($second_date)),
            // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
            // 'commitment_period' => $request->status,
        ]);
        if (auth()->user()->agent_code == 'AAMT') {
            $data2 = verification_form::create([
                'cust_id' => $data->id,
                'lead_no' => $data->id,
                'lead_id' => $lead_no,
                'customer_name' => $data->customer_name,
                'customer_number' => $data->customer_number,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_document,
                'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => 'Verified at location',
                'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            $data3 = lead_sale::findorfail($data->id);
            $data3->status = '1.10';
            $data3->save();
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    //

                    //
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Hold';
                    $k->save();
                    // CHANGE LATER
                    $cn = choosen_number::select('choosen_numbers.id')
                        ->where('number_id', $dn->id)
                        ->first();
                    if ($cn) {
                        $cnn = choosen_number::findorfail($cn->id);
                        $cnn->status = '4';
                        $cnn->save();
                    }
                    // CHANGE LATER
                }
                // return $d->id;
                // return "number has been reserved";

            }
            //
            remark::create([
                'remarks' => $request->remarks_process_new,
                'lead_status' => $status,
                'lead_id' => $data->id,
                'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'source' => 'During Lead',
                'user_agent_id' => auth()->user()->id,
            ]);
            timing_duration::create([
                'lead_no' => $data->id,
                'lead_generate_time' => Carbon::now()->toDateTimeString(),
                'sale_agent' => auth()->user()->id,
                'status' => $status,

            ]);
            if (!empty($request->add_lat_lng)) {
                $name = explode(',', $request->add_lat_lng);
                $lat = $name[0];
                $lng = $name[1];
            } else {
                $lat = '';
                $lng = '';
            }
            if ($request->add_location != '') {
                lead_location::create([
                    'lead_id' => $data->id,
                    'location_url' => $request->add_location,
                    'lat' => $lat,
                    'lng' => $lng,
                    'assign_to' => $request->assing_to,
                    // 'number_allowed' => $request->num_allowed,
                    // 'duration' => $request->duration,
                    // 'revenue' => $request->revenue,
                    // 'free_minutes' => $request->free_min,
                    'status' => 1,
                ]);
            } else {
                lead_location::create([
                    'lead_id' => $data->id,
                    'location_url' => 'https://maps.google?q=0,0',
                    'lat' => 0,
                    'lng' => 0,
                    'assign_to' => $request->assing_to,
                    // 'number_allowed' => $request->num_allowed,
                    // 'duration' => $request->duration,
                    // 'revenue' => $request->revenue,
                    // 'free_minutes' => $request->free_min,
                    'status' => 1,
                ]);
            }
        } else {
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $damn = choosen_number::select('numberdetails.id', 'choosen_numbers.id as cid')
                    ->Join(
                        'numberdetails',
                        'numberdetails.id',
                        '=',
                        'choosen_numbers.number_id'
                    )
                    ->where("choosen_numbers.user_id", auth()->user()->id)
                    ->Orwhere('numberdetails.non_c', '1')
                    ->Orwhere('choosen_numbers.status', '1')
                    ->where('numberdetails.number', $val)
                    ->first();

                // $lead_test = lead_sale::select('lead_sales.channel_type')->where('selected_number', $val)->first();
                // if ($lead_test) {
                //     $mmm = lead_sale::findorfail($data->id);
                //     $mmm->channel_type = $lead_test->channel_type;
                //     $mmm->save();
                // }
                // if($damn){
                //     $mycount = $damn->count();
                // }


                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->where('numberdetails.status', 'Available')
                    ->count();
                if ($damn) {
                    $d = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($d->id);
                    $k->status = 'Reserved';
                    $k->book_type = '1';
                    $k->save();
                    $k = choosen_number::findorfail($damn->cid);
                    $k->status = '2';
                    // $k->book_type = '1';
                    $k->save();
                } elseif ($count > 0) {
                    $d = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($d->id);
                    $k->status = 'Reserved';
                    $k->book_type = '1';
                    $k->save();

                    //
                    $k = choosen_number::create([
                        'number_id' => $d->id,
                        'user_id' => auth()->user()->id,
                        'status' => '1',
                        'book_type' => '1',
                        'agent_group' => auth()->user()->agent_code,
                        // 'ip_address' => Request::ip(),
                        'date_time' => Carbon::now()->toDateTimeString(),
                    ]);
                    // return "number has been reserved";
                    $log = choosen_number_log::create([
                        // 'number'
                        'number_id' => $k->id,
                        'user_id' => auth()->user()->id,
                        'agent_group' => auth()->user()->agent_code,
                    ]);
                    //
                } else {
                    $m = lead_sale::findorfail($data->id);
                    $m->status = '1.04';
                    $m->save();
                    return response()->json(['error' => ['Documents' => ['Number Already Selected => ' . $val . ', Please Make New Lead.']]], 200);
                    // $d = numberdetail::select("numberdetails.id")
                    // ->where('numberdetails.number', $val)
                    // ->first();
                    // $k = numberdetail::findorfail($d->id);
                    // $k->status = 'Reserved';
                    // $k->save();
                    // //
                    // $k = choosen_number::create([
                    //         'number_id' => $k->id,
                    //         'user_id' => auth()->user()->id,
                    //         'status' => '1',
                    //         'agent_group' => auth()->user()->agent_code,
                    //         // 'ip_address' => Request::ip(),
                    //         'date_time' => Carbon::now()->toDateTimeString(),
                    //     ]);
                    // // return "number has been reserved";
                    // $log = choosen_number_log::create([
                    //     // 'number'
                    //     'number_id' => $k->id,
                    //     'user_id' => auth()->user()->id,
                    //     'agent_group' => auth()->user()->agent_code,
                    // ]);
                    // return $ch->id;
                }
                // return $d->id;
                // return "number has been reserved";
            }
            \App\Models\remark::create([
                'remarks' => $request->remarks_process_new,
                'lead_status' => $status,
                'lead_id' => $data->id,
                'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'source' => 'During Lead',
                'user_agent_id' => auth()->user()->id,
            ]);
            \App\Models\timing_duration::create([
                'lead_no' => $data->id,
                'lead_generate_time' => Carbon::now()->toDateTimeString(),
                'sale_agent' => auth()->user()->id,
                'status' => $status,

            ]);
            if (!empty($request->add_lat_lng)) {
                $name = explode(',', $request->add_lat_lng);
                $lat = $name[0];
                $lng = $name[1];
            } else {
                $lat = '';
                $lng = '';
            }
            if ($request->add_location != '') {
                \App\Models\lead_location::create([
                    'lead_id' => $data->id,
                    'location_url' => $request->add_location,
                    'lat' => $lat,
                    'lng' => $lng,
                    'assign_to' => $request->assing_to,
                    // 'number_allowed' => $request->num_allowed,
                    // 'duration' => $request->duration,
                    // 'revenue' => $request->revenue,
                    // 'free_minutes' => $request->free_min,
                    'status' => 1,
                ]);
            } else {
                lead_location::create([
                    'lead_id' => $data->id,
                    'location_url' => 'https://maps.google?q=0,0',
                    'lat' => 0,
                    'lng' => 0,
                    'assign_to' => $request->assing_to,
                    // 'number_allowed' => $request->num_allowed,
                    // 'duration' => $request->duration,
                    // 'revenue' => $request->revenue,
                    // 'free_minutes' => $request->free_min,
                    'status' => 1,
                ]);
            }
        }
        //
        $ntc = lead_sale::select('call_centers.numbers')
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->Join(
                'call_centers',
                'call_centers.call_center_code',
                'users.agent_code'
            )
            ->where('lead_sales.id', $data->id)->first();
        //
        $link = route('view.lead', $data->id);

        //
        $details = [
            'language' => $data->language,
            'lead_id' => $data->id,
            'lead_no' => $data->lead_no,
            'customer_name' => $data->customer_name,
            'customer_number' => $data->customer_number,
            'selected_number' => $data->selected_number,
            'remarks' => $request->remarks_process_new . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')',
            'remarks_final' => $request->remarks_process_new,
            'saler_name' => $data->saler_name,
            'link' => $link,
            'agent_code' => auth()->user()->agent_code,
            'number' => $ntc->numbers . ',' . '923121337222' . ',' . '97143789365' . ',' . '917827250250',
            // 'Plan' => $number,
            // 'AlternativeNumber' => $alternativeNumber,
        ];

        // return $details;

        // \App\Models\Http\Controllers\ChatController::SendNewLeadMessage($details);

        //
        notify()->success('New Sale has been submitted succesfully');
        return response()->json(['success' => 'New Sale has been submitted succesfully']);
    }
    public function leadstoremnp(Request $request)
    {
        $ldate = date('h:i A');
        $validator = Validator::make($request->all(), [ // <---
            // 'title' => 'required|unique:posts|max:255',
            // 'body' => 'required',
            'cname' => 'required|string',
            'cnumber' => 'required|string',
            'nation' => 'required',
            'age' => 'required|numeric|min:20|not_in:20',
            'simtype' => 'required',
            'gender' => 'required',
            'emirates' => 'required',
            'emirate_id' => 'required',
            'language' => 'required',
            'area' => 'required',
            'plans' => 'required',
            'plan_mnp' => 'required',
            // 'plan_new' => 'required',
            // 'selnumber' => 'required|numeric',
            // 'activation_charges_new' => 'required',
            // 'activation_rate_new' => 'required',
            'remarks_process_mnp' => 'required',
            'assing_to' => 'required',
            'start_date' => 'required',
            'start_time' => 'required|after:' . $ldate,
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        if (!empty($request->existing_lead)) {
            $status = '1.14';
            $lead_date = $request->lead_date;
        } else {
            $status = '1.01';
            $lead_date = Carbon::now()->toDateTimeString();
        }
        // $activation_rate_new = implode(",", $request->activation_rate_new);
        $test = implode(",", $request->plans);
        $last = \App\Models\lead_sale::latest()->first();
        $getfirst = $last->id;
        $lead_no = auth()->user()->agent_code . '-' . $getfirst . '-' . Carbon::now()->format('M') . '-' . now()->year;
        $data = lead_sale::create([
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'nationality' => $request->nation,
            'age' => $request->age,
            'area' => $request->area,
            'sim_type' => $request->simtype,
            'gender' => $request->gender,
            'lead_type' => $request->lead_type,
            'channel_type' => 'ExpressDial',
            'emirates' => $request->emirates,
            'emirate_num' => $request->emirate_number,
            'etisalat_number' => $request->etisalat_number,
            'emirate_id' => $request->emirate_id,
            'language' => $request->language,
            'share_with' => $request->shared_with,
            'additional_document' => $request->additional_document,
            'saler_id' => auth()->user()->id,
            'selected_number' => $request->cnumber,
            // 'selected_number' => $SelNumber,
            'select_plan' => $request->plan_mnp,
            'contract_commitment' => $request->status,
            'benefits' => $test,
            'lead_no' => $lead_no,
            'remarks' => $request->remarks_process_mnp,
            'status' => $status,
            'saler_name' => auth()->user()->name,
            'pay_status' => $request->activation_charges_mnp,
            // 'pay_charges' => $activation_rate_new,
            // 'device' => $request->status,
            'date_time' => $lead_date,
            'date_time_follow' => $lead_date,
            // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
            // 'commitment_period' => $request->status,
        ]);
        remark::create([
            'remarks' => $request->remarks_process_mnp,
            'lead_status' => $status,
            'lead_id' => $data->id,
            'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            'user_agent' => 'Sale',
            'user_agent_id' => auth()->user()->id,
        ]);
        timing_duration::create([
            'lead_no' => $data->id,
            'lead_generate_time' => Carbon::now()->toDateTimeString(),
            'sale_agent' => auth()->user()->id,
            'status' => $status,

        ]);
        if (!empty($request->add_lat_lng)) {
            $name = explode(',', $request->add_lat_lng);
            $lat = $name[0];
            $lng = $name[1];
        } else {
            $lat = '';
            $lng = '';
        }
        if ($request->add_location != '') {
            lead_location::create([
                'lead_id' => $data->id,
                'location_url' => $request->add_location,
                'lat' => $lat,
                'lng' => $lng,
                'assign_to' => $request->assing_to,
                // 'number_allowed' => $request->num_allowed,
                // 'duration' => $request->duration,
                // 'revenue' => $request->revenue,
                // 'free_minutes' => $request->free_min,
                'status' => 1,
            ]);
        } else {
            lead_location::create([
                'lead_id' => $data->id,
                'location_url' => 'https://maps.google?q=0,0',
                'lat' => 0,
                'lng' => 0,
                'assign_to' => $request->assing_to,
                // 'number_allowed' => $request->num_allowed,
                // 'duration' => $request->duration,
                // 'revenue' => $request->revenue,
                // 'free_minutes' => $request->free_min,
                'status' => 1,
            ]);
        }
        notify()->success('New Sale has been submitted succesfully');

        //
        notify()->success('New Sale has been submitted succesfully');
        return response()->json(['success' => 'New Sale has been submitted succesfully']);
    }
    public function RecordingLead(Request $request)
    {
        // return $request;
        //  $operation = lead_sale::select('lead_sales.id as lead_no','lead_sales.*', "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name')
        // ->LeftJoin(
        //     'audio_recordings','audio_recordings.lead_no','lead_sales.id'
        // )
        //  ->LeftJoin(
        //     'status_codes',
        //     'status_codes.status_code',
        //     '=',
        //     'lead_sales.status'
        // )
        //  ->LeftJoin(
        //     'users',
        //     'users.id',
        //     '=',
        //     'lead_sales.saler_id'
        // )
        // ->LeftJoin(
        //     'lead_locations',
        //     'lead_locations.lead_id',
        //     '=',
        //     'lead_sales.id'
        // )
        // ->whereIn(
        //     'lead_sales.status',
        //     ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21']
        // )
        // ->whereNull('audio_recordings.audio_file')
        // ->get();
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name')
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'remarks',
                'remarks.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'status_codes',
                'status_codes.status_code',
                '=',
                'verification_forms.status'
            )
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'audio_recordings',
                'audio_recordings.lead_no',
                '=',
                'lead_sales.id'
            )
            ->whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21', '1.02']
            )
            ->where('lead_sales.sim_type', 'New')
            ->whereNull('audio_recordings.audio_file')
            ->whereYear('lead_sales.updated_at', Carbon::now()->year)
            ->groupBy('lead_sales.id')
            ->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
    }
    public function LoadAdminData(Request $request)
    {

        $data = \App\Models\User::whereIn('role', ['Verification', 'NumberVerification'])
            ->where('email', '!=', 'verification@verification.com')
            ->get();
        $call_center = \App\Models\call_center::whereStatus('1')->get();
        return view('admin.ajax', compact('data', 'call_center'));
    }
    public function LoadMainCordData(Request $request)
    {
        // $data = \App\Models\User::whereIn('role', ['Verification', 'NumberVerification'])
        // ->where('email', '!=', 'verification@verification.com')
        // ->get();
        // $call_center = \App\Models\call_center::whereStatus('1')->get();
        // return view('admin.ajax',compact('data','call_center'));
        if ($request->Month == 'Daily') {
            return view('coordination.ajax.daily-summary-main-cord');
        } else if ($request->Month == 'Monthly') {
            return view('coordination.ajax.monthly-summary-main-cord');
        } else if ($request->Month == 'CallCenter') {
            $call_center = \App\Models\call_center::whereStatus('1')->get();
            return view('coordination.ajax.callcenter-summary-main-cord', compact('call_center'));
        } else if ($request->Month == 'ActivationAgent') {
            $activation_users = User::role('activation')->get();
            return view('coordination.ajax.ActivationAgent-summary-main-cord', compact('activation_users'));
        } else if ($request->Month == 'EmirateCord') {
            $channel_partner = \App\Models\channel_partner::where('status', '1')->get();
            // $activation_users = User::role('activation')->get();
            return view('coordination.ajax.daily-summary-emirate-cord', compact('channel_partner'));
        }
    }
    public function InitialLeads(Request $request)
    {
        $operation = lead_sale::select("timing_durations.lead_generate_time", 'timing_durations.verify_agent', "lead_sales.lead_no", 'lead_sales.customer_name', 'lead_sales.selected_number', 'lead_sales.select_plan', 'lead_sales.sim_type', 'lead_sales.customer_number', 'lead_sales.id', "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'status_codes',
                'status_codes.status_code',
                '=',
                'lead_sales.status'
            )
            // ->where('lead_sales.lead_type', 'Postpaid')
            ->wherein('lead_sales.status', ['1.01', '1.11'])
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-initial-lead', compact('operation'));
    }
    public function ShowVerificationAgent(Request $request)
    {
        // return $request;
        $data = lead_sale::select('lead_sales.lead_no', 'timing_durations.verify_agent', 'timing_durations.id')
            ->Join(
                'timing_durations',
                'timing_durations.lead_no',
                'lead_sales.id'
            )
            ->where('lead_sales.id', $request->leadid)->first();
        $user = User::select('users.id', 'users.name')->where('role', 'Verification')->get();
        return view('ajax.change-verification-agent', compact('data', 'user'));
    }
    public function ChangeAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $agent_name = $request->agent_name;
        if ($agent_name == 'Null') {
            $agent_name = 0;
            $data = timing_duration::findorfail($request->id);
            $data->verify_agent = floatval($agent_name);
            $data->save();
            // $data->update(['agent_name' => floatval($agent_name)]); // saves null

        } else {
            $agent_name = $request->agent_name;
            $data = timing_duration::findorfail($request->id);
            $data->verify_agent = $request->agent_name;
            $data->save();
        }
    }
    public function assign_me(Request $request)
    {
        $data = timing_duration::select('id', 'verify_agent', 'lead_proceed_time')
            ->where('lead_no', $request->lead_id)
            ->first();
        // $agent_name = $request->agent_name;
        // if($agent_name == 'Null'){
        $agent_name = 0;
        // $data = timing_duration::findorfail($request->id);
        $data->verify_agent = floatval($agent_name);
        $data->save();
        return "1";
        // $data->update(['agent_name' => floatval($agent_name)]); // saves null

        // }
        // else{
        //     $agent_name = $request->agent_name;
        //     $data = timing_duration::findorfail($request->id);
        //     $data->verify_agent = $request->agent_name;
        //     $data->save();
        // }

    }
    //
    public function MNPINP(Request $request)
    {
        // return 'MNP INPROCESS';
        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name", "users.agent_code as agent_code")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'verification_forms.lead_no'
                )
                ->LeftJoin(
                    'remarks',
                    'remarks.lead_no',
                    '=',
                    'verification_forms.lead_no'
                )
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    '=',
                    'verification_forms.lead_no'
                )
                ->LeftJoin(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )

                ->LeftJoin(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.08'])
                // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-mnp', compact('operation'));
        } else {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->Join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                ->LeftJoin(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->where('lead_sales.sim_type', 'MNP')
                // ->where('lead_sales.status', '1.06')
                // ->where('users.aget_code', auth()->user()->agent_code)
                // ->where('lead_sales.channel_type', 'TTF')
                // ->whereIn('lead_sales.status', ['1.10', '1.21'])
                // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                // ->where('lead_sales.status', '1.08')
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                ->orderBy('lead_sales.updated_at', 'desc')
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.manager.mygrpleadmnp', compact('operation'));
            // $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name","lead_locations.assign_to", "users.name as agent_name")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->LeftJoin(
            //     'timing_durations',
            //     'timing_durations.lead_no',
            //     '=',
            //     'verification_forms.lead_no'
            // )
            // ->LeftJoin(
            //     'remarks',
            //     'remarks.lead_no',
            //     '=',
            //     'verification_forms.lead_no'
            // )
            // ->LeftJoin(
            //     'status_codes',
            //     'status_codes.status_code',
            //     '=',
            //     'verification_forms.status'
            // )
            // ->LeftJoin(
            //     'lead_sales',
            //     'lead_sales.id',
            //     '=',
            //     'verification_forms.lead_no'
            // )
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            // ->LeftJoin(
            //     'lead_locations',
            //     'lead_locations.lead_id',
            //     '=',
            //     'lead_sales.id'
            // )
            //     // ->LeftJoin(
            //     //         'lead_locations',
            //     //         'lead_locations.lead_id',
            //     //         '=',
            //     //         'lead_sales.id'
            //     //     )
            //     //     ->where('verification_forms.status', '1.10')
            //     ->where('verification_forms.status', '1.10')
            //     ->where('users.agent_code', auth()->user()->agent_code)
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     // ->where('verification_forms.assing_to', auth()->user()->id)
            //     // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            //     ->groupBy('verification_forms.lead_no')
            //     ->get();
            // // $operation = verification_form::wherestatus('1.10')->get();
            // return view('dashboard.view-proceed-request-only', compact('operation'));
        }
    }
    //
    public function attendmnp(Request $request)
    {
        // return $request->id;
        $operation = verification_form::select("lead_sales.id as lead_id", "verification_forms.id as ver_id", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "users.name as agent_name", "lead_sales.*", "lead_locations.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'remarks',
                'remarks.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'lead_sales.share_with'
            )
            ->where('verification_forms.id', $request->id)
            ->first();

        $remarks =  remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $request->id)
            ->get();
        $countries = \App\Models\country_phone_code::all();
        // $operation = verification_form::wherestatus('1.10')->get();
        $emirates = \App\Models\emirate::all();
        $plans = \App\Models\plan::wherestatus('1')->get();
        $elifes = \App\Models\elife_plan::wherestatus('1')->get();
        $addons = \App\Models\addon::wherestatus('1')->get();
        $device = \App\Models\imei_list::wherestatus('1')->get();
        $users = \App\Models\User::role('activation')->get();

        // $operation = verification_form::whereid($id)->get();
        return view('dashboard.add-activation-mnp', compact('operation', 'remarks', 'countries', 'emirates', 'plans', 'elifes', 'addons', 'device', 'users'));
    }
    //
    public function numbersavereserved(Request $request)
    {
        $validator = Validator::make($request->all(), [ // <---

            // 'number' => 'required|numeric',
            // 'number_category' => 'required',
            // 'number_passcode' => 'required|numeric',
            'call_center' => 'required',
            // 'number_status' => 'required',
            // 'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        // $data = numberdetail::findorfail($request->id)
        // return "zom";
        $data = numberdetail::updateOrCreate(['id' => $request->id], [
            // $data = numberdetail::create([
            // 'number' => $request->number,
            // 'status' => $request->status,
            // 'type' => $request->number_category,
            // 'passcode' => $request->number_passcode,
            'call_center' => $request->call_center,
            // 'identity' => $request->number_status,
            // 'channel_type' => $request->channel_partner,
            // 'channel_type' => 'TTF',
        ]);
        return response()->json(['success' => 'Updated']);
    }
    //
    public function moveleadform(Request $request)
    {
        // $data = activation_form::where
        $data = \App\Models\lead_sale::select('lead_sales.id', 'lead_sales.lead_no', 'lead_sales.status', 'users.name as agent_name')
            ->LeftJoin(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            // ->where('activation_forms.status', '1.02')
            // ->where('users.agent_code', trim($request->id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            // app/Http/Controllers/
            ->get();
        //
        $agentlist = User::whereIn('users.role', ['NumberAdmin', 'Sale'])
            ->get();
        return view('dashboard.move-lead-form', compact('data', 'agentlist'));
    }
    public function moveleadpost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required',
            'agent_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = lead_sale::where('id', $request->lead_id)->first();
        $data->saler_id = $request->agent_id;
        $data->save();
        $act_data = \App\Models\activation_form::where('lead_id', $request->lead_id)->first();
        if ($act_data) {
            $act_data->saler_id = $request->agent_id;
            $act_data->save();
        }
        return "done";
    }
    //
    public function ReadyToAssign(Request $request)
    {
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'remarks',
                'remarks.lead_no',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'status_codes',
                'status_codes.status_code',
                '=',
                'verification_forms.status'
            )
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            ->LeftJoin(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            // ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.10', '1.21', '1.19'])
            ->where('users.id', auth()->user()->id)
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            // ->Orwhere('lead_locations.assign_to', '=', '136')
            // ->when($mychannel, function ($query) use ($mychannel) {
            //     if ($mychannel == 'TTF') {
            //         return $query->whereIn('lead_locations.assign_to', ['136']);
            //     } else if ($mychannel == 'ExpressDial') {
            //         return $query->whereIn('lead_locations.assign_to', ['583']);
            //     } else {
            //         return $query->whereIn('lead_locations.assign_to', ['136', '583']);
            //     }
            // })
            // ->whereDate('lead_sales.updated_at', Carbon::today())


            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
    }
    //
    public function lead_generate(Request $request){
        $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->where('lead_sales.id', $request->id)
            ->first();
        $remarks =
            remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $request->id)
            ->get();
        $countries = country_phone_code::select('name')->get();
        $emirates = emirate::select('name')->get();
        return view('verification.manage-operation-lead', compact('operation', 'remarks','emirates', 'countries'));
    }
    //
    public function verify_store(Request $request)
    {
//
        // return $request;
        if($request->reject_comment_new != ''){
            // return "b";
            // $validator = Validator::make($request->all(), [ // <---
            //     // // 'title' => 'required|unique:posts|max:255',
            //     // // 'body' => 'required',
            //     // // 'cname' => 'required|string|unique:lead_sales,customer_name',
            //     // 'cnumber' => 'required',
            //     // 'nation' => 'required',
            //     // 'age' => 'required|numeric',
            //     // 'sim_type' => 'required',
            //     // 'gender' => 'required',
            //     // 'emirates' => 'required',
            //     // 'emirate_id' => 'required',
            //     // 'language' => 'required',
            //     // 'plan_new' => 'required',
            //     // 'selnumber' => 'required|numeric',
            //     // 'activation_charges_new' => 'required',
            //     // 'activation_rate_new' => 'required',
            //     // 'remarks_process_new' => 'required',
            //     'audio.*' => 'required',

            // ]);
            // if ($validator->fails()) {
            //     return redirect()->back()
            //         ->withErrors($validator)
            //         ->withInput();
            // }
            if($request->reject_comment_new == 'Already Active')
            {
                // return response()->json(['error' => $a]);
            }
            else{

                if (empty($request->audio)) {
                    return response()->json(['error' => 'Please attach Audio']);
                    // notify()->error('Please Submit Audio');
                    // return redirect()->back()
                    // ->withInput();
                }
            }

            if (!empty($request->audio)) {
            foreach ($request->audio as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // AzureCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                    // AzureCodeEnd
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => auth()->user()->name,
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.04',
                'remarks' => $request->reject_comment_new,
                'area' => $request->area,
                // 'later_date' => $request->later_date,
                'pre_check_agent' => auth()->user()->id
                // 'date_time_follow' => $request->call_back_at_new,
            ]);
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Reserved';
                    $k->book_type = '0';
                    $k->save();
                    $cn = choosen_number::select('choosen_numbers.id')
                        ->where('number_id', $dn->id)
                        ->first();
                    $cnn = choosen_number::findorfail($cn->id);
                    $cnn->status = '1';
                    $cnn->save();
                }
                // return $d->id;
                // return "number has been reserved";

            }
            return response()->json(['success' => 'Reject succesfully']);
            // notify()->success('Reject succesfully');
            // return redirect(route('verification.index'));
            // return "D";
        }
        if($request->later_date != ''){
            // return "c";

            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.06',
                'remarks' => $request->remarks_for_cordination,
                'later_date' => $request->later_date,
                'area' => $request->area,
                'pre_check_agent' => auth()->user()->id
                // 'date_time_follow' => $request->call_back_at_new,
            ]);
            //
            // remark::create([
            //     'remarks' => $request->remarks_for_cordination,
            //     'lead_status' => '1.03',
            //     'lead_id' => $request->lead_id,
            //     'lead_no' => $request->lead_id,
            //     'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            //     'user_agent' => auth()->user()->name,
            //     'user_agent_id' => auth()->user()->id,
            // ]);
            // return
            notify()->success('Lead has been Later up now');

            // return redirect()->back()->withInput();
            return redirect(route('verification.index'));
        }
        if ($request->remarks_for_cordination != '') {
            $validatedData = Validator::make($request->all(), [
                'remarks_for_cordination' => 'required|string',
            ]);
            // return "s";
            if ($validatedData->fails()) {
                // return redirect()->back()
                    // ->withErrors($validatedData)
                    // ->withInput();
            return response()->json(['success' => 'Please Fill Remarks First']);
            }
            // return "b";
            // return $request->lead_id;
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.03',
                'area' => $request->area,
                'remarks' => $request->remarks_for_cordination,'pre_check_agent' => auth()->user()->id,
                // 'date_time_follow' => $request->call_back_at_new,
            ]);
            remark::create([
                'remarks' => $request->remarks_for_cordination,
                'lead_status' => '1.03',
                // 'source' =>
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_id,
                'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'user_agent_id' => auth()->user()->id,
            ]);
            $link = route('view.lead', $d->id);

            $ntc = lead_sale::select('call_centers.notify_email', 'users.secondary_email', 'users.agent_code', 'call_centers.numbers', 'users.teamleader')
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->Join(
                'call_centers',
                'call_centers.call_center_code',
                'users.agent_code'
            )
            ->where('lead_sales.id', $d->id)->first();
            //
            $tl = \App\User::where('id', $ntc->teamleader)->first();
            if ($tl) {
                $wapnumber = $tl->phone . ',' .  $ntc->numbers;
            } else {
                $wapnumber = $ntc->numbers;
            }

            if ($ntc) {
                $to = [
                    [
                        'email' =>
                        'Coordinationalert@gmail.com',
                        'name' => 'Junaid',
                    ],
                    // [
                    //     'email' =>
                    //     'Aroojmalikam776@gmail.com',
                    //     'name' => 'Junaid',
                    // ],
                    // [
                    //     'email' => $ntc->secondary_email, 'name' => 'Agent'
                    // ],
                    [
                        'email' => $ntc->notify_email, 'name' => 'Coordinator'
                    ]
                ];
            } else {
                $to = [
                    [
                        'email' =>
                        'Coordinationalert@gmail.com',
                        'name' => 'Junaid',
                    ],
                    // [
                    //     'email' =>
                    //     'Aroojmalikam776@gmail.com',
                    //     'name' => 'Junaid',
                    // ],
                    [
                        'email' =>
                        'cc8alert@gmail.com',
                        'name' => 'CC8 Coordinator',
                    ]
                ];
            }
            //
            $link = route('view.lead', $d->id);
            $agent_code = $ntc->agent_code;

            $details = [
                'lead_id' => $d->id,
                'lead_no' => $d->lead_no,
                'customer_name' => $d->customer_name,
                'customer_number' => $d->customer_number,
                'selected_number' => $d->selected_number,
                'remarks' => $request->remarks_for_cordination . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')',
                'remarks_final' => $request->remarks_for_cordination . ' ' . ' Remarks By ' . auth()->user()->name . ' (' .  auth()->user()->email . ')' . ' => Agent Name: ' . $d->saler_name,
                'saler_name' => $d->saler_name,
                'link' => $link,
                'agent_code' => $agent_code,
                'number' => $wapnumber,
                // 'Plan' => $number,
                // 'AlternativeNumber' => $alternativeNumber,
            ];

            ChatController::SendToWhatsApp($details);

            $time_duration = timing_duration::where('lead_no', $request->lead_id)->first();
            if ($time_duration) {
                $time_duration->verify_agent = '';
                $time_duration->save();
            }
            //
            $lead_type = $d->lead_type;
            //
            if($lead_type == 'HomeWifi' || $lead_type == 'MNP')
            {
                return response()->json(['success' => 'Lead has been Non Verified now']);

                // return "HOME WIFI";
            }
            else{


            //
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                        ->where('numberdetails.number', $val)
                        ->first();
                    $k = numberdetail::findorfail($dn->id);
                    // $k->status = 'Available';
                    $k->non_c = 1;
                    $k->book_type = '0';
                    $k->save();
                    $cn = choosen_number::select('choosen_numbers.id')
                        ->where('number_id', $dn->id)
                        ->where('status',2)
                        ->OrderBy('created_at','desc')
                        ->first();
                    if($cn){
                        $cnn = choosen_number::findorfail($cn->id);
                        $cnn->status = 1;
                        $cnn->personal_status = 'Non Verified at' . Carbon::now();
                        $cnn->save();
                    }else{
                        $d = lead_sale::findOrFail($request->lead_id);

                        $k = choosen_number::create([
                            'number_id' => $dn->id,
                            'user_id' => $d->saler_id,
                            'status' => '1',
                            'book_type' => '1',
                            'agent_group' => $d->saler_id,
                            // 'ip_address' => Request::ip(),
                            'date_time' => Carbon::now()->toDateTimeString(),
                        ]);
                        // return "number has been reserved";
                        $log = choosen_number_log::create([
                            // 'number'
                            'number_id' => $dn->id,
                            'user_id' => $d->saler_id,
                            'agent_group' => $d->saler_id,
                        ]);
                        // $cnn = choosen_number
                    }
                    // $cnn->delete();
                }
                // return $d->id;
                // return "number has been reserved";

            }
            // return
            return response()->json(['success' => 'Lead has been Non Verified now']);
            }

            // notify()->success('Lead has been follow up now');

            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
        }
        else if ($request->sim_type == 'New' && $request->reject_comment_new == '') {
            // return "s";
            // return $request;
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                'cnumber' => 'required',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
                'audio.*' => 'required',

            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);

            }
            if (empty($request->audio)) {
                // return "s";
                return response()->json(['error' => ['Please Attach Audio']]);
                // return response()->json(['error' => ]);
                // notify()->error('Please Submit Audio');
                // return redirect()->back()
                //     ->withInput();
            }
            // $planName = $request->plan_name;
            $planName = implode(',', $request->plan_new);
            $SelNumber = implode(",", $request->selnumber);
            $activation_charge = implode(",", $request->activation_charges_new);
            $activation_rate_new = implode(",",
                $request->activation_rate_new
            );
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id'=>$request->lead_id,
                'lead_no'=>$request->lead_id,
                'lead_id'=>$request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            foreach ($request->selnumber as $key => $val) {
                // return $val;
                $count = numberdetail::select("numberdetails.id")
                ->where('numberdetails.number', $val)
                ->count();
                if ($count > 0) {
                    $dn = numberdetail::select("numberdetails.id")
                    ->where('numberdetails.number', $val)
                    ->first();
                    $k = numberdetail::findorfail($dn->id);
                    $k->status = 'Hold';
                    $k->save();
                    // CHANGE LATER
                    $cn = choosen_number::select('choosen_numbers.id')
                    ->where('number_id',$dn->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                    if($cn){
                        $cnn = choosen_number::findorfail($cn->id);
                        $cnn->status = '4';
                        $cnn->save();
                    }
                    // CHANGE LATER
                }
                // return $d->id;
                // return "number has been reserved";

            }
            // $n = numberdetail::select("numberdetails.id")
            //     ->where('numberdetails.number', $val)
            //     ->first();
            // $k = numberdetail::findorfail($d->id);
            // $k->status = 'Reserved';
            // $k->save();
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            if ($wp->role == 'TTF-SALE') {
                $status_code = '1.10';
            } else {
                $status_code = '1.07';
            }
            if ($d->channel_type == 'TTF') {
                $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'verify_agent' => auth()->user()->id,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                'channel_type' => 'ExpressDial',
                // 'channel_type' => $channel,
            ]);
        }else{
                // return $SelNumber;

                // $check_num = \App\numberdetail::where('number',$SelNumber)->whereIn('identity', ['SILSP2MW'])->first();
                // if($check_num){
                //     $channel = 'ExpressDial';
                // }else{
                    $channel = 'ExpressDial';
                // }

                $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                // 'verify_agent' => auth()->user()->id,
                // main
                'selected_number' => $SelNumber,
                'select_plan' => $planName,
                // 'contract_commitment' => $request->status,
                'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_new,
                'verify_agent' => auth()->user()->id,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $activation_charge,
                'pay_charges' => $activation_rate_new,
                'channel_type' => $channel,
            ]);

            }
            $d = timing_duration::select('id')
            ->where('lead_no', $request->lead_id)
            ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();


            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        // LocalStorageCodeStart
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        //
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                        // AzureCodeStart

                        // AzureCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // $whatsapp = \App\User::select('phone')
            // ->where('users.id',$lead_data->agent_code)
            // ->first();
            // if()
            // foreach (explode(',', $planName) as $k) {
            //     $plan = \App\plan::where('id', $k)->first();
            // }
            // $plan_name = $plan->plan_name;
            // $data = $plan->data;
            // // notify()->success('Sim Type New-Verified succesfully');
            // // return redirect()->back()->withInput();
            // // return redirect(route('verification.index'));
            // // return "VIN";
            // // return $d->customer_name;
            // $a = "https://api.whatsapp.com/send?text= ** New-Verified **  %0a Lead No: $lead_data->lead_no %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: $plan_name %0a Data : $data %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a Verified";
            // return response()->json(['success' => $a]);
            $wp = \App\User::select('agent_code')->where('users.id',$lead_data->saler_id)->first();
            $wp->agent_code;
            if($wp->agent_code == 'CC3'){
                $wp_num = '919599020271';
            }
            else if($wp->agent_code == 'CC2'){
                $wp_num = '917827250250';
            }
            else if($wp->agent_code == 'CC8-POSTPAID'){
                $wp_num = '97148795753';
            }
            else if($wp->agent_code == 'CC9'){
                $wp_num = '917838908219';
            }
            if (strpos($planName, ",") !== false) {
                // list($d, $l) = explode('.', $dm, 2);
                foreach (explode(',', $planName) as $key => $k) {
                    // $plan = \App\plan::where('id',$k)->first();
                    $plan = \App\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    $plan_name[] = $plan->plan_name;
                    $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\plan::where('id',$k)->first();
                }
                foreach (explode(',', $SelNumber) as $key => $k) {
                    // $plan = \App\plan::where('id',$k)->first();
                    //  $plan = \App\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    // $plan_name[] = $plan->plan_name;
                    // $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\plan::where('id',$k)->first();
                    $numberd = \App\numberdetail::where('number', $k)->first();
                    $selected_number[] = $numberd->number;
                    $passcode[] = $numberd->passcode;
                }
                foreach (explode(',', $activation_charge) as $key => $k) {
                    // $plan = \App\plan::where('id',$k)->first();
                    //  $plan = \App\plan::findorfail($k);
                    //  return $SelNumber[$key];
                    // $plan_name[] = $plan->plan_name;
                    // $data_gb[] = $plan->data;
                    // $plan_name = $plan->plan_name;
                    // $plan_name[] = $plan->plan_name;
                    // }
                    // foreach(explode(',', $SelNumber) as $k){
                    // $plan = \App\plan::where('id',$k)->first();
                    // $numberd = \App\numberdetail::where('number', $k)->first();
                    // $selected_number[] = $numberd->number;
                    $ac[] = $k;
                }
                $tag = explode(',', $SelNumber);
                $count = count($tag);
                // $pay_status[] = $activation_rate_new[$key];
                // $plan_name['0'];
                // return $activation_charge;
                // $a = "https://api.whatsapp.com/send?text= *Verified at Location*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a";
                // for ($i = 0; $i < $count; $i++) {
                //     $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
                // }
                // $a .= "%0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a Verified %0a";
                return response()->json(['success' => 'Succesfully Verified']);
            } else {
                // return $SelNumber;
                $plan = \App\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();
                $plan_name = $plan->plan_name;
                $data_gb = $plan->data;
                $selected_number = $numberd->number;
                $passcode = $numberd->passcode;
                $pay_status = $activation_charge;
                // $a = "https://api.whatsapp.com/send?text= *Verified*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a Number Selected: *$selected_number*  %0a PassCode = *$passcode* %0a Plan selected: *$plan_name* %0a Activation: $pay_status  %0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a  %0a";
                return response()->json(['success' => "Successfully Verified"]);
            }


            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        }
        else if ($request->sim_type == 'MNP' && $request->reject_comment_new == '' || $request->sim_type == 'Migration' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:lead_sales,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                'audio.*' => 'required',

                // 'plan_new' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id'=>$request->lead_id,
                'lead_no'=>$request->lead_id,
                'lead_id'=>$request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_mnp,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_mnp,
                'status' => '1.10',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            if ($validator->fails()) {
                // return redirect()->back()
                // ->withErrors($validator)
                // ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);

            }
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            if ($wp->role == 'TTF-SALE') {
                $status_code = '1.10';
            } else {
                $status_code = '1.10';
            }
            $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'area' => $request->area,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'select_plan' => $request->plan_mnp,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_mnp,
                'pay_status' => $request->activation_charges_mnp,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                // 'verify_agent' => auth()->user()->id,
                // main
                // 'selected_number' => $SelNumber,
                // 'select_plan' => $planName,
                // // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // // 'lead_no' => 'Lead No',
                // 'remarks' => $request->remarks_process_new,
                // // 'status' => '1.09',
                // // 'saler_name' => 'Sale',
                // 'pay_status' => $activation_charge,
                // 'pay_charges' => $activation_rate_new,
            ]);
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                        // AzureCodeStart
                        // AzureCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }
            $d = timing_duration::select('id')
            ->where('lead_no', $request->lead_id)
            ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));
            $a = "https://api.whatsapp.com/send?text= ** New-Verified **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a ";
            return response()->json(['success' => $a]);

            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        }
        else if ($request->sim_type == 'HomeWifi' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:verification,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'dob' => 'required',
                'audio' => 'required',
                'order_id' => 'required',
                'selected_number' => 'required',

                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id'=>$request->lead_id,
                'lead_no'=>$request->lead_id,
                'lead_id'=>$request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            if ($wp->role == 'TTF-SALE') {
                $status_code = '1.10';
            } else {
                $status_code = '1.10';
            }
            $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'selected_number' => $request->selected_number,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                'sr_number' => $request->order_id
            ]);
            //
            $d = timing_duration::select('id')
            ->where('lead_no', $request->lead_id)
            ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            // foreach ($request->audio as $key => $val) {
            //     if (!empty($request->audio)) {
            //         // return $request->audio;
            //         // return $request->file();
            //         if ($file = $request->file('audio')) {
            //             $image2 = file_get_contents($file[$key]);
            //             $originalFileName = time() . $file[$key]->getClientOriginalName();
            //             $multi_filePath = 'audio' . '/' . $originalFileName;
            //             \Storage::disk('azure')->put($multi_filePath, $image2);
            //             // LocalStorageCodeStart
            //             $ext = date('d-m-Y-H-i');
            //             $mytime = Carbon::now();
            //             $ext =  $mytime->toDateTimeString();
            //             // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            //             $name = $originalFileName;

            //             $file[$key]->move('audio', $name);
            //             $input['path'] = $name;
            //             // LocalStorageCodeEnd
            //             // AzureCodeStart
            //             // AzureCodeEnd
            //         }
            //         //     $data2 = meeting_std::create([
            //         //         'meeting_id' => $meeting_id,
            //         //         'meeting_title' => $request->course_title,
            //         //         'std_id' => $val,
            //         //         'status' => 1,
            //         //     ]);
            //         // } else {
            //         //     echo "boom";
            //         // }
            //         $data = audio_recording::create([
            //             // 'resource_name' => $request->resource_name,
            //             'audio_file' => $name,
            //             'username' => 'salman',
            //             'lead_no' => $request->lead_id,
            //             // 'teacher_id' => $request->teacher_id,
            //             'status' => 1,
            //         ]);
            //     }
            // }
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                        // AzureCodeStart
                        // AzureCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }
            //


            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));

            $a = "https://api.whatsapp.com/send?text= ** New-Verified **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a";
            return response()->json(['success' => $a]);
            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        }
        else if ($request->sim_type == 'Elife' && $request->reject_comment_new == '') {
            // return "s";
            $validator = Validator::make($request->all(), [ // <---
                // 'title' => 'required|unique:posts|max:255',
                // 'body' => 'required',
                // 'cname' => 'required|string|unique:verification,customer_name',
                // 'cnumber' => 'required|numeric',
                'nation' => 'required',
                'age' => 'required|numeric',
                'sim_type' => 'required',
                'gender' => 'required',
                'emirates' => 'required',
                'emirate_id' => 'required',
                'language' => 'required',
                // 'plan_new' => 'required',
                // 'dob' => 'required',
                'audio.*' => 'required',
                // 'selnumber' => 'required|numeric',
                // 'activation_charges_new' => 'required',
                // 'activation_rate_new' => 'required',
                // 'remarks_process_new' => 'required',
            ]);
            if ($validator->fails()) {
                // return redirect()->back()
                //     ->withErrors($validator)
                //     ->withInput();
                return response()->json(['error' => $validator->errors()->all()]);
            }
            // $planName = $request->plan_name;
            // $planName = implode(',', $request->plan_new);
            // $SelNumber = implode(",", $request->selnumber);
            // $activation_charge = implode(",", $request->activation_charges_new);
            // $activation_rate_new = implode(",", $request->activation_rate_new);
            // return $request->emirate_id;
            // return $test = implode(",", $request->plan_new);
            $data = verification_form::create([
                'cust_id'=>$request->lead_id,
                'lead_no'=>$request->lead_id,
                'lead_id'=>$request->lead_no,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_number' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'original_emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirate_location' => $request->emirates,
                'additional_documents' => $request->additional_documents,
                'verify_agent' => auth()->user()->id,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
                // 'pay_charges' => $activation_rate_new,
                // 'device' => $request->status,
                // 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                // 'date_time_follow' => $current_date_time = Carbon::now()->toDateTimeString(),
                // 'commitment_period' => $request->status,
            ]);
            //
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $wp = \App\User::select('role')->where('users.id', $lead_data->saler_id)->first();
            if ($wp->role == 'TTF-SALE') {
                $status_code = '1.10';
            } else {
                $status_code = '1.10';
            }
            $d->update([
                'status' => $status_code,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'nationality' => $request->nation,
                'age' => $request->age,
                'sim_type' => $request->sim_type,
                'gender' => $request->gender,
                'emirates' => $request->emirates,
                'emirate_num' => $request->emirate_num,
                // 'etisalat_number' => $request->status,
                'emirate_id' => $request->emirate_id,
                'language' => $request->language,
                'emirates' => $request->emirates,
                'additional_document' => $request->additional_documents,
                'dob' => $request->dob,
                // main
                // 'selected_number' => $SelNumber,
                'select_plan' => $request->plan_elife,
                'number_commitment' => $request->elife_makani_number,
                // 'contract_commitment' => $request->status,
                // 'contract_commitment' => $request->contract_comm_mnp,
                // 'lead_no' => 'Lead No',
                'remarks' => $request->remarks_process_elife,
                // 'status' => '1.09',
                // 'saler_name' => 'Sale',
                'pay_status' => $request->activation_charges_mnp,
            ]);
            //
            $d = timing_duration::select('id')
            ->where('lead_no', $request->lead_id)
            ->first();
            $data  = timing_duration::findorfail($d->id);
            $data->lead_proceed_time = Carbon::now()->toDateTimeString();
            $data->verify_agent = auth()->user()->id;
            $data->save();
            foreach ($request->audio as $key => $val) {
                if (!empty($request->audio)) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('audio')) {
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'audio' . '/' . $originalFileName;
                        \Storage::disk('azure')->put($multi_filePath, $image2);
                        // LocalStorageCodeStart
                        $ext = date('d-m-Y-H-i');
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('audio', $name);
                        $input['path'] = $name;
                        // LocalStorageCodeEnd
                        // AzureCodeStart
                        // AzureCodeEnd
                    }
                    //     $data2 = meeting_std::create([
                    //         'meeting_id' => $meeting_id,
                    //         'meeting_title' => $request->course_title,
                    //         'std_id' => $val,
                    //         'status' => 1,
                    //     ]);
                    // } else {
                    //     echo "boom";
                    // }
                    $data = audio_recording::create([
                        // 'resource_name' => $request->resource_name,
                        'audio_file' => $name,
                        'username' => 'salman',
                        'lead_no' => $request->lead_id,
                        // 'teacher_id' => $request->teacher_id,
                        'status' => 1,
                    ]);
                }
            }

            // notify()->success('Verified succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('verification.index'));

            $a = "https://api.whatsapp.com/send?text= ** New-Verified **  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: Salman %0a";
            return response()->json(['success' => $a]);
            // return $planName .'<br>'. $SelNumber . '<br>' . $activation_charge . '<br>' . $activation_rate_new;

        }
    }
}
