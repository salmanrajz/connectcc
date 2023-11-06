<?php

namespace App\Http\Controllers;

use App\Models\activation_form;
use App\Models\audio_recording;
use App\Models\lead_sale;
use App\Models\remark;
use App\Models\TargetAssignerManager;
use App\Models\verification_form;
use App\Models\AssignChannel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoordinaterController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function timeout()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        // $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to",'lead_sales.appointment_to', 'appointment_from')
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
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     ->where('appointment_to', '>=', Carbon::now())
        //     ->whereIn(
        //         'lead_sales.status',
        //         ['1.10','1.21']
        //     )
        //     ->groupBy('verification_forms.lead_no')
        //     ->get();
        $mychannel = AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            ->where('appointment_to', '<', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public function emiratetimeout()
    {

        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->where('appointment_to', '<', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public function appointment_lead()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        $mychannel = AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $timeNow =  Carbon::now();
        $currentTime = \Carbon\Carbon::parse($timeNow);
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            ->where(function ($query) use ($currentTime) {
                $query->where('appointment_from', '>', Carbon::now())
                    ->where('appointment_to', '<=', Carbon::now());
            })
            // ->orWhere(function ($query) use ($currentTime) {

            //     $query->where('appointment_from', '>', Carbon::now())
            //     ->orWhere('appointment_to', '<=', Carbon::now());

            // })

            // ->where('appointment_from', '<', Carbon::now())
            // ->where('appointment_to', '>', Carbon::now())

            // ->where('appointment_to', '<', Carbon::now())
            // ->where('appointment_from', '=<', Carbon::now())
            // ->where('appointment_to', '>=', Carbon::now())
            // ->where('appointment_to', '', Carbon::now())
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.updated_at')
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public function emirate_appointment_lead()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $timeNow =  Carbon::now();
        $currentTime = \Carbon\Carbon::parse($timeNow);
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where(function ($query) use ($currentTime) {
                $query->where('appointment_from', '>', Carbon::now())
                    ->where('appointment_to', '<=', Carbon::now());
            })
            // ->orWhere(function ($query) use ($currentTime) {

            //     $query->where('appointment_from', '>', Carbon::now())
            //     ->orWhere('appointment_to', '<=', Carbon::now());

            // })

            // ->where('appointment_from', '<', Carbon::now())
            // ->where('appointment_to', '>', Carbon::now())

            // ->where('appointment_to', '<', Carbon::now())
            // ->where('appointment_from', '=<', Carbon::now())
            // ->where('appointment_to', '>=', Carbon::now())
            // ->where('appointment_to', '', Carbon::now())
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.updated_at')
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public function mytimeout()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        $myrole = auth()->user()->multi_agentcode;
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            // ->where('users.agent_code',auth()->user()->code)
            ->where('appointment_to', '>=', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public static function timeout_lead()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        return $operation = verification_form::select('verification_forms.id')
            // $user =  DB::table("subjects")->select('subject_name', 'id')
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
            // ->LeftJoin(
            //     'lead_locations',
            //     'lead_locations.lead_id',
            //     '=',
            //     'lead_sales.id'
            // )
            ->where('appointment_to', '<', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public static function emirate_timeout_lead()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        return $operation = verification_form::select('verification_forms.id')

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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->where('appointment_to', '<', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }

    public static function appointment_lead_count()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        // return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to",'lead_sales.appointment_to', 'appointment_from')
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
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     ->where('appointment_to', '>', Carbon::now())
        //     // ->where('appointment_from', '<', Carbon::now())
        //     // ->where('appointment_from', '=<', Carbon::now())
        //     // ->where('appointment_to', '>=', Carbon::now())
        //     // ->where('appointment_from', '<', Carbon::now())
        //     // ->where('appointment_to', '=<', Carbon::now())
        //     // ->where('appointment_to', '>=', Carbon::now())
        //     ->whereIn(
        //         'lead_sales.status',
        //         ['1.10', '1.21']
        //     )
        //     ->groupBy('verification_forms.lead_no')
        //     ->get()->count();
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        $timeNow =  Carbon::now();
        $currentTime = \Carbon\Carbon::parse($timeNow);
        // return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
        // // $user =  DB::table("subjects")->select('subject_name', 'id')
        // ->LeftJoin(
        //     'timing_durations',
        //     'timing_durations.lead_no',
        //     '=',
        //     'verification_forms.lead_no'
        // )
        //     ->LeftJoin(
        //         'remarks',
        //         'remarks.lead_no',
        //         '=',
        //         'verification_forms.lead_no'
        //     )
        //     ->LeftJoin(
        //         'status_codes',
        //         'status_codes.status_code',
        //         '=',
        //         'verification_forms.status'
        //     )
        //     ->LeftJoin(
        //         'lead_sales',
        //         'lead_sales.id',
        //         '=',
        //         'verification_forms.lead_no'
        //     )
        //     ->LeftJoin(
        //         'users',
        //         'users.id',
        //         '=',
        //         'lead_sales.saler_id'
        //     )
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     ->where(function ($query) use ($currentTime) {
        //         $query->where('appointment_from', '>', Carbon::now())
        //         ->where('appointment_to', '<=', Carbon::now());
        //     })->orWhere(function ($query) use ($currentTime) {

        //         $query->where('appointment_from', '>',
        //             Carbon::now()
        //         )
        //         ->orWhere('appointment_to', '<=', Carbon::now());
        //     })

        //     // ->where('appointment_from', '<', Carbon::now())
        //     // ->where('appointment_to', '>', Carbon::now())

        //     // ->where('appointment_to', '<', Carbon::now())
        //     // ->where('appointment_from', '=<', Carbon::now())
        //     // ->where('appointment_to', '>=', Carbon::now())
        //     // ->where('appointment_to', '', Carbon::now())
        //     ->whereIn(
        //         'lead_sales.status',
        //         ['1.10', '1.21']
        //     )
        //     ->whereDate('lead_sales.updated_at', Carbon::today())
        //     // ->where('lead_sales.updated_at')
        //     ->groupBy('verification_forms.lead_no')
        //     ->get()->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
        return    $operation = verification_form::select("verification_forms.lead_no")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
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
            // ->LeftJoin(
            //     'lead_locations',
            //     'lead_locations.lead_id',
            //     '=',
            //     'lead_sales.id'
            // )
            ->where(function ($query) use ($currentTime) {
                $query->where('appointment_from', '>', Carbon::now())
                    ->where('appointment_to', '<=', Carbon::now());
            })
            // ->orWhere(function ($query) use ($currentTime) {

            //     $query->where('appointment_from', '>', Carbon::now())
            //     ->orWhere('appointment_to', '<=', Carbon::now());

            // })

            // ->where('appointment_from', '<', Carbon::now())
            // ->where('appointment_to', '>', Carbon::now())

            // ->where('appointment_to', '<', Carbon::now())
            // ->where('appointment_from', '=<', Carbon::now())
            // ->where('appointment_to', '>=', Carbon::now())
            // ->where('appointment_to', '', Carbon::now())
            ->whereIn('lead_sales.channel_type', $mychannel)

            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.updated_at')
            ->groupBy('verification_forms.lead_no')
            ->get()->count();
    }
    public static function emirate_appointment_lead_count()
    {

        $timeNow =  Carbon::now();
        $currentTime = \Carbon\Carbon::parse($timeNow);
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)

        return    $operation = verification_form::select("verification_forms.lead_no")

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
            // ->LeftJoin(
            //     'lead_locations',
            //     'lead_locations.lead_id',
            //     '=',
            //     'lead_sales.id'
            // )
            ->where(function ($query) use ($currentTime) {
                $query->where('appointment_from', '>', Carbon::now())
                    ->where('appointment_to', '<=', Carbon::now());
            })
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            // ->orWhere(function ($query) use ($currentTime) {

            //     $query->where('appointment_from', '>', Carbon::now())
            //     ->orWhere('appointment_to', '<=', Carbon::now());

            // })

            // ->where('appointment_from', '<', Carbon::now())
            // ->where('appointment_to', '>', Carbon::now())

            // ->where('appointment_to', '<', Carbon::now())
            // ->where('appointment_from', '=<', Carbon::now())
            // ->where('appointment_to', '>=', Carbon::now())
            // ->where('appointment_to', '', Carbon::now())
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.updated_at')
            ->groupBy('verification_forms.lead_no')
            ->get()->count();
    }
    public static function my_timeout_lead()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            ->where('users.agent_code', auth()->user()->agent_code)
            ->where('appointment_to', '>=', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->groupBy('verification_forms.lead_no')
            ->get()->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public static function my_appointment_lead()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.appointment_to', 'appointment_from')
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
            ->where('appointment_to', '>', Carbon::now())

            // ->where('appointment_to', '>', Carbon::now())

            // ->where('appointment_from', '<', Carbon::now())

            // ->where('appointment_from', '>=', Carbon::now())
            // ->where('appointment_to', '=<', Carbon::now())
            ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('appointment_to', '>=', Carbon::now())
            ->whereIn(
                'lead_sales.status',
                ['1.10', '1.21']
            )
            ->groupBy('verification_forms.lead_no')
            ->get()->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    public static function my_appointment()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        //  $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to",'lead_sales.appointment_to', 'appointment_from')
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
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     // ->where('appointment_to', '>', Carbon::now())
        //     ->where('appointment_to', '>', Carbon::now())

        //     // ->where('appointment_from', '<', Carbon::now())

        //     ->where('users.agent_code',auth()->user()->agent_code)
        //     // ->where('appointment_from', '>=', Carbon::now())
        //     // ->where('appointment_from', '>=', Carbon::now())
        //     // ->where('appointment_to', '<=', Carbon::now())
        //     // ->where('appointment_to', '=<', Carbon::now())
        //     ->whereIn(
        //         'lead_sales.status',
        //         ['1.10', '1.21']
        //     )
        //     ->groupBy('verification_forms.lead_no')
        //     ->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));

        // ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    //
    public static function emirate_my_appointment()
    {
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        //  $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to",'lead_sales.appointment_to', 'appointment_from')
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
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     // ->where('appointment_to', '>', Carbon::now())
        //     ->where('appointment_to', '>', Carbon::now())

        //     // ->where('appointment_from', '<', Carbon::now())

        //     ->where('users.agent_code',auth()->user()->agent_code)
        //     // ->where('appointment_from', '>=', Carbon::now())
        //     // ->where('appointment_from', '>=', Carbon::now())
        //     // ->where('appointment_to', '<=', Carbon::now())
        //     // ->where('appointment_to', '=<', Carbon::now())
        //     ->whereIn(
        //         'lead_sales.status',
        //         ['1.10', '1.21']
        //     )
        //     ->groupBy('verification_forms.lead_no')
        //     ->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));

        // ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('dashboard.view-proceed-request-only', compact('operation'));
        // return $data = lead_sale::where('appointment_to','>=',Carbon::now())->get();
        // return            ->whereTime('session_start_date', '>=', Carbon::create($request->start, $request->user()->timezone)->toTimeString())
        // return "TimeOut";
    }
    //
    public static function emirate_group_daily_unassigned($lead_type)
    {
        // return auth()->user()->emirate;
        return $operation = verification_form::select("verification_forms.lead_no")
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->Join(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->where('verification_forms.status', '1.10')
            // ->where('lead_locations.assign_to', '=', 136)
            ->when($mychannel, function ($query) use ($mychannel) {
                if ($mychannel == 'TTF') {
                    return $query->whereIn('lead_locations.assign_to', ['136']);
                } else if ($mychannel == 'ExpressDial') {
                    return $query->whereIn('lead_locations.assign_to', ['583']);
                } else {
                    return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                }
            })
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.lead_type', $lead_type)
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function emirate_my_group_daily_assigned($lead_type)
    {
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
            // ->where('lead_sales.status', '1.06')
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('lead_sales.channel_type', 'TTF')
            ->where('lead_locations.assign_to', '!=', 136)
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->where('lead_sales.status', '1.10')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->orderBy('lead_sales.updated_at', 'desc')
            ->get()->count();
    }
    //
    public static function emirate_total_active_daily($id, $status, $channel)
    {
        // return $id;
        return $active = \App\Models\activation_form::select('activation_forms.id')
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
            ->where('lead_sales.lead_type', $status)
            ->where('lead_sales.channel_type', $channel)
            ->where('users.agent_code', auth()->user()->agent_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
        // ->where('lead_sales.channel_type', $channel)
        // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
        // ->where('users.id', $id)
    }
    //

    public static function emirate_lead($status, $emirate)
    {
        return $tmkoc = lead_sale::select('lead_sales.*')
            ->join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->join(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            ->join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('lead_locations.assign_to', auth()->user()->id)
            ->where('lead_sales.status', $status)
            ->where('lead_sales.emirates', $emirate)
            ->count();
    }
    public static function emirate_lead_all($status)
    {
        return $tmkoc = lead_sale::select('lead_sales.*')
            ->join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->join(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            ->join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('lead_locations.assign_to', auth()->user()->id)
            ->where('lead_sales.status', $status)
            // ->where('lead_sales.emirates', $emirate)
            ->count();
    }
    public static function emirate_lead_assigned_all($status)
    {
        return $tmkoc = lead_sale::select('lead_sales.*')
            ->join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->join(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            ->join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            ->where('lead_sales.status', $status)
            ->get()
            // ->where('lead_sales.emirates', $emirate)
            ->count();
    }
    public static function emirate_lead_assigned($status, $emirate)
    {
        return $tmkoc = lead_sale::select('lead_sales.*')
            ->join(
                'users',
                'users.id',
                '=',
                'lead_sales.saler_id'
            )
            ->join(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            ->join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            ->where('lead_sales.status', $status)
            ->where('lead_sales.emirates', $emirate)
            ->count();
    }
    public function emirate($id)
    {
        if ($id == 'All') {
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
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->where('lead_sales.emirates', $id)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->latest()
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request', compact('operation'));
        } else {


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
                ->where('lead_locations.assign_to', '=', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->where('lead_sales.emirates', $id)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->latest()
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request', compact('operation'));
        }
    }
    public function emirate_assigned($id)
    {
        if ($id == 'All') {
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
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                // ->where('lead_sales.emirates', $id)
                ->latest()

                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request', compact('operation'));
        } else {
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
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->where('lead_sales.emirates', $id)
                ->latest()

                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request', compact('operation'));
        }
    }
    public function agent_assigned($userid)
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_locations.assign_to', $userid)
            ->where('lead_sales.status', '1.10')
            // ->where('lead_sales.emirates', $id)
            ->latest()

            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-agent-request', compact('operation', 'userid'));
        // }
    }
    public static function assign_count($id)
    {
        // return $id;
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        return $operation = lead_sale::select("lead_sales.*")
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.10')
            ->where('lead_locations.assign_to', $id)->get()->count();
        // return $operation = verification_form::select("verification_forms.lead_no")
        //     ->Join(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'verification_forms.lead_no'
        //     )
        //     ->where('verification_forms.status','!=', '1.02')
        //     ->where('lead_locations.assign_to', $id)
        //     ->count();
    }
    public static function active_assigned_count($id)
    {
        // return $id;
        return $operation = activation_form::select("activation_forms.lead_id")
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'activation_forms.lead_id'
            )
            ->where('activation_forms.status', '1.02')
            ->where('lead_locations.assign_to', $id)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            // ->groupBy('verification_forms.lead_no')
            ->count();
    }
    //
    public static function reverify_count()
    {
        return $s = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->leftJoin(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.id', auth()->user()->id)
            ->where('lead_sales.status', '1.05')
            ->where('lead_sales.lead_type', 'postpaid')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.agent_code', auth()->user()->agent_code)
            ->count();
    }
    public static function reverify_count_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $s = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->leftJoin(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)

            // ->where('users.id', auth()->user()->id)
            ->where('lead_sales.status', '1.05')
            ->where('lead_sales.lead_type', 'postpaid')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->count();
    }
    public static function emiratereverify_count_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        return $s = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->leftJoin(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.id', auth()->user()->id)
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.05')
            ->where('lead_sales.lead_type', 'postpaid')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->count();
    }
    public static function revefollowup_count()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $s = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->leftJoin(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)

            // ->where('users.id', auth()->user()->id)
            ->where('lead_sales.status', '1.21')
            ->where('lead_sales.lead_type', 'postpaid')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->count();
    }
    public static function revefollowup_count_daily()
    {
        return $s = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->leftJoin(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.id', auth()->user()->id)
            ->where('lead_sales.status', '1.21')
            ->where('lead_sales.lead_type', 'postpaid')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->count();
    }
    //
    public static function totalrejectleadcount()
    {
        $id = 'Activated Leads';
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
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
            ->where('lead_sales.status', '1.15')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public static function totalrejectleadcount_daily()
    {
        $id = 'Activated Leads';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = lead_sale::select("lead_sales.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->LeftJoin(
            //     'timing_durations',
            //     'timing_durations.lead_no',
            //     '=',
            //     'lead_sales.id'
            // )
            //     ->LeftJoin(
            //         'activation_forms',
            //         'activation_forms.lead_id',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->Join(
            //         'status_codes',
            //         'status_codes.status_code',
            //         '=',
            //         'lead_sales.status'
            //     )
            //     ->Join(
            //         'users',
            //         'users.id',
            //         '=',
            //         'lead_sales.saler_id'
            //     )
            ->whereIn('lead_sales.channel_type', $mychannel)

            ->where('lead_sales.status', '1.15')
            ->whereDate('lead_sales.updated_at', Carbon::today())

            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public static function emiratetotalrejectleadcount_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Activated Leads';
        return $operation = lead_sale::select("lead_sales.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->LeftJoin(
            //     'timing_durations',
            //     'timing_durations.lead_no',
            //     '=',
            //     'lead_sales.id'
            // )
            //     ->LeftJoin(
            //         'activation_forms',
            //         'activation_forms.lead_id',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->Join(
            //         'status_codes',
            //         'status_codes.status_code',
            //         '=',
            //         'lead_sales.status'
            //     )
            //     ->Join(
            //         'users',
            //         'users.id',
            //         '=',
            //         'lead_sales.saler_id'
            //     )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.15')
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    //
    public static function totalfollowleadcount()
    {
        $id = 'Activated Leads';
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->LeftJoin(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
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
            ->where('lead_sales.status', '1.17')
            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    //
    public  static function active_lead_count_daily()
    {
        $id = 'Activated Leads';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = activation_form::where('status', '1.02')
            ->whereIn('activation_forms.channel_type', $mychannel)

            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public  static function emirate_active_lead_count_daily()
    {

        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Activated Leads';
        return $operation = activation_form::whereIn('status', ['1.02', '1.08'])
            ->whereIn('activation_forms.channel_type', $mychannel)
            ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public  static function active_lead_count()
    {
        $id = 'Activated Leads';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = activation_form::where('status', '1.02')
            ->whereIn('activation_forms.channel_type', $mychannel)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    //
    public function reprocess_CordinationLead($id)
    {
        // return $id;
        $users = \App\Models\User::role('Elife Active')->get();
        $audios = audio_recording::wherelead_no($id)->get();

        $operation = verification_form::select('lead_sales.additional_document', "timing_durations.lead_generate_time", "verification_forms.*", "lead_sales.saler_id")
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
            ->where('verification_forms.lead_no', $id)
            ->first();
        $remarks =  remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $id)
            ->get();
        return view('dashboard.add-reprocess-lead', compact('operation', 'users', 'remarks', 'audios'));
    }
    public function CordinationLead($id)
    {
        // return $id;
        $users = \App\Models\User::role('Elife Active')->get();
        $audios = audio_recording::wherelead_no($id)->get();

        $operation = verification_form::select('lead_sales.emirates as emirate_location', 'lead_sales.additional_document', "timing_durations.lead_generate_time", "verification_forms.*", "lead_sales.saler_id", 'lead_sales.channel_type', 'lead_sales.eti_lead_id')
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
            ->where('verification_forms.lead_no', $id)
            ->first();
        $remarks =  remark::select("remarks.*")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            ->where("remarks.lead_id", $id)
            ->get();
        return view('dashboard.add-location-lead', compact('operation', 'users', 'remarks', 'audios'));
    }
    //
    public function p2p_proceed()
    {
        if (auth()->user()->role == 'MainCoordinator') {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "users.name as agent_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->LeftJoin(
                    'remarks',
                    'remarks.lead_no',
                    '=',
                    'lead_sales.id'
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
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                ->where('lead_sales.sim_type', 'P2P')
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                // ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-junaid', compact('operation'));
        } else {
            $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "users.name as agent_name")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->LeftJoin(
                    'remarks',
                    'remarks.lead_no',
                    '=',
                    'lead_sales.id'
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
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->where('lead_sales.sim_type', 'P2P')
                // ->where('lead_sales.updated_at', Carbon::now())
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)


                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                // ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
    }
    //
    public function myproceedlead()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        if (auth()->user()->role == 'MainCoordinator') {
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name")
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
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-junaid', compact('operation'));
        } else {
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name")
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->whereIn('verification_forms.status', ['1.10', '1.21'])
                ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('lead_sales.updated_at', Carbon::now())
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)


                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function ourproceedleaddaily($agent_code)
    {
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
            // ->where('lead_sales.status', '1.06')
            ->where('users.agent_code', $agent_code)
            // ->where('lead_sales.channel_type', 'TTF')
            // ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            ->where('lead_sales.status', '1.10')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->orderBy('lead_sales.updated_at', 'desc')
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.manager.mygrplead', compact('operation'));
    }
    public function myproceedleaddaily()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->id;

        $myrole = auth()->user()->multi_agentcode;

        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name", "users.agent_code as agent_code", 'lead_sales.eti_lead_id')
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
                ->Join(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-junaid', compact('operation'));
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
                // ->where('lead_sales.channel_type', 'TTF')
                // ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->orderBy('lead_sales.updated_at', 'desc')
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.manager.mygrplead', compact('operation'));
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
        // })->name('activation.proceed');
    }
    public function emiratemyproceedleaddaily()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->emirate;
        // return auth()->user()->id;
        // $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name", "users.agent_code as agent_code", 'lead_sales.eti_lead_id')
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
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.channel_type', ['ExpressDial'])
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->where('lead_locations.assign_to', '!=', 136)
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));
    }
    public function myproceedleadyesterday()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name", "users.agent_code as agent_code")
                // $operation = verification_form::select('verification_forms.lead_no','verification_forms.id')
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
                ->Join(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->whereDate('lead_sales.updated_at', Carbon::yesterday())
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // foreach($operation as $data){
            //     // dd($data);
            //     // return $data;
            //     $d = lead_sale::findorfail($data->lead_no);
            //     $d->update([
            //         'status' => '1.19',
            //         'remarks' => 'Follow Up',
            //         // 'date_time_follow' => $request->call_back_at_new,
            //     ]);
            //     $dd = verification_form::findOrFail($data->id);
            //     $dd->update([
            //         'status' => '1.19',
            //         // 'assing_to' => $request->assing_to,
            //         // 'cordination_by' => auth()->user()->id,
            //     ]);
            // }
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-junaid', compact('operation'));
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
                // ->where('lead_sales.status', '1.06')
                ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('lead_sales.channel_type', 'TTF')
                // ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->orderBy('lead_sales.updated_at', 'desc')
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.manager.mygrplead', compact('operation'));
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
        // })->name('activation.proceed');
    }
    public function emiratemyproceedleadyesterday()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name", "users.agent_code as agent_code")
            // $operation = verification_form::select('verification_forms.lead_no','verification_forms.id')
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
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            ->whereDate('lead_sales.updated_at', Carbon::yesterday())
            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // foreach($operation as $data){
        //     // dd($data);
        //     // return $data;
        //     $d = lead_sale::findorfail($data->lead_no);
        //     $d->update([
        //         'status' => '1.19',
        //         'remarks' => 'Follow Up',
        //         // 'date_time_follow' => $request->call_back_at_new,
        //     ]);
        //     $dd = verification_form::findOrFail($data->id);
        //     $dd->update([
        //         'status' => '1.19',
        //         // 'assing_to' => $request->assing_to,
        //         // 'cordination_by' => auth()->user()->id,
        //     ]);
        // }
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));

        // })->name('activation.proceed');
    }
    public function myproceedleadyesterdaytotoday()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        if (auth()->user()->role == 'MainCoordinator') {
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", "users.name as agent_name", "users.agent_code as agent_code")
                // $operation = verification_form::select('verification_forms.lead_no','verification_forms.id')
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
                ->Join(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->whereDate('lead_sales.updated_at', Carbon::yesterday())
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            foreach ($operation as $data) {
                // dd($data);
                // return $data;
                $d = lead_sale::findorfail($data->lead_no);
                $d->update([
                    'status' => '1.19',
                    'remarks' => 'Follow Up',
                    // 'date_time_follow' => $request->call_back_at_new,
                ]);
                $dd = verification_form::findOrFail($data->id);
                $dd->update([
                    'status' => '1.19',
                    // 'assing_to' => $request->assing_to,
                    // 'cordination_by' => auth()->user()->id,
                ]);
            }
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            // return view('dashboard.my-lead-junaid', compact('operation'));
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
                // ->where('lead_sales.status', '1.06')
                ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('lead_sales.channel_type', 'TTF')
                // ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->orderBy('lead_sales.updated_at', 'desc')
                ->get();
            // $operation = lead_sale::wherestatus('1.01')->get();
            return view('dashboard.manager.mygrplead', compact('operation'));
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
        // })->name('activation.proceed');
    }
    public function followupcombine()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        if (auth()->user()->role == 'MainCoordinator') {
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
                ->whereIn('lead_sales.status', ['1.19', '1.20'])
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-junaid', compact('operation'));
        } else {
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->where('verification_forms.status', '1.10')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function followupcombinedaily()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->role;
        if (auth()->user()->role == 'MainCoordinator') {
            //  $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name","lead_locations.assign_to", 'users.name as agent_name')
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->LeftJoin(
            //     'timing_durations',
            //     'timing_durations.lead_no',
            //     '=',
            //     'verification_forms.lead_no'
            // )
            //     ->LeftJoin(
            //         'remarks',
            //         'remarks.lead_no',
            //         '=',
            //         'verification_forms.lead_no'
            //     )
            //     ->LeftJoin(
            //         'status_codes',
            //         'status_codes.status_code',
            //         '=',
            //         'verification_forms.status'
            //     )
            //     ->LeftJoin(
            //         'lead_sales',
            //         'lead_sales.id',
            //         '=',
            //         'verification_forms.lead_no'
            //     )
            //     ->LeftJoin(
            //         'users',
            //         'users.id',
            //         '=',
            //         'lead_sales.saler_id'
            //     )
            //     ->LeftJoin(
            //         'lead_locations',
            //         'lead_locations.lead_id',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.19','1.20'])
            //     ->where('lead_sales.updated_at', Carbon::today())
            //     // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            //     // ->where('users.agent_code', auth()->user()->agent_code)
            //     // ->where('verification_forms.assing_to', auth()->user()->id)
            //     // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            //     ->groupBy('verification_forms.lead_no')
            //     ->get();
            // $operation = lead_sale::whereIn('status', ['1.19', '1.20'])
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->get()->count();
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
            $now =  Carbon::now()->today();
            $current_date =  $now->toDateTimeString();
            $operation = lead_sale::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*",  "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name')
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'timing_durations',
                    'timing_durations.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->LeftJoin(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                // ->Join(
                //     'remarks',
                //     'remarks.lead_no',
                //     '=',
                //     'lead_sales.id'
                // )
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
                ->Join(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.19', '1.20'])
                ->whereDate('lead_sales.updated_at', $current_date)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // ->where('lead_sales.sim_type', 'P2P')
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            // ->groupBy('verification_forms.lead_no')
            // $operation = verification_form::wherestatus('1.10')->get();
            // return view('number.number-list-activation', compact('operation'));
            return view('dashboard.my-lead-junaid', compact('operation'));
        } else {
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->where('verification_forms.status', '1.10')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->where('lead_sales.updated_at', Carbon::today())
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function emiratefollowupcombinedaily()
    {
        // Route::GET('proceed-lead', function () {

        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)

        $now =  Carbon::now()->today();
        $current_date =  $now->toDateTimeString();
        $operation = lead_sale::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*",  "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name')
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'timing_durations',
                'timing_durations.lead_no',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            // ->Join(
            //     'remarks',
            //     'remarks.lead_no',
            //     '=',
            //     'lead_sales.id'
            // )
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
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.19', '1.20'])
            ->whereDate('lead_sales.updated_at', $current_date)
            ->groupBy('verification_forms.lead_no')
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->get();
        // ->where('lead_sales.sim_type', 'P2P')
        // ->where('users.agent_code', auth()->user()->agent_code)
        // ->where('verification_forms.assing_to', auth()->user()->id)
        // ->where('verification_forms.emirate_location', auth()->user()->emirate)
        // ->groupBy('verification_forms.lead_no')
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));

        // })->name('activation.proceed');
    }
    public function agent_proceed_lead()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        if (auth()->user()->role == 'MainCoordinator') {
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
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '=', auth()->user()->id)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)


                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        } else {
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.10', '1.21'])

                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function later_lead_today()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        $myrole = auth()->user()->multi_agentcode;

        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
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
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.06'])
                ->whereDate('lead_sales.later_date', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        } else {
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.06'])
                ->whereDate('lead_sales.later_date', Carbon::today())
                ->groupBy('verification_forms.lead_no')
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
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function emirate_later_lead_today()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =

        // return auth()->user()->agent_code;
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name', 'lead_sales.emirates')
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereIn('lead_sales.status', ['1.06'])
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));

        // })->name('activation.proceed');
    }
    public function later_lead_all()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        $myrole = auth()->user()->multi_agentcode;

        // return auth()->user()->agent_code;
        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name', 'lead_sales.emirates')
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
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.06'])
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        } else {
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'users.name as agent_name', 'lead_sales.emirates')
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.06'])
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')
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
                // ->where('users.agent_code',auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-later-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function our_agent_proceed_lead_daily($agent_code)
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.emirates')
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
            // ->LeftJoin(
            //         'lead_locations',
            //         'lead_locations.lead_id',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->where('verification_forms.status', '1.10')
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->when($mychannel, function ($query) use ($mychannel) {
                if ($mychannel == 'TTF') {
                    return $query->whereIn('lead_locations.assign_to', ['136']);
                } else if ($mychannel == 'ExpressDial') {
                    return $query->whereIn('lead_locations.assign_to', ['583']);
                } else {
                    return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                }
            })
            // ->where('lead_locations.assign_to', '=', '136')
            // ->Orwhere('lead_locations.assign_to', '=', '583')
            ->where('users.agent_code', $agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));
    }
    public function agent_proceed_lead_daily()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        $myrole = auth()->user()->multi_agentcode;
        if (auth()->user()->role == 'MainCoordinator') {

            $mychannel = AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
            $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.emirates')
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
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                // ->where('lead_locations.assign_to', '=', auth()->user()->id)
                // ->Orwhere('lead_locations.assign_to', '=', '136')
                ->when($mychannel, function ($query) use ($mychannel) {
                    if ($mychannel == 'TTF') {
                        return $query->whereIn('lead_locations.assign_to', ['136']);
                    } else if ($mychannel == 'ExpressDial') {
                        return $query->whereIn('lead_locations.assign_to', ['583']);
                    } else {
                        return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                    }
                })
                ->whereDate('lead_sales.updated_at', Carbon::today())


                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        } else {
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '=', '136')
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
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function all_agent_proceed_lead_daily()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        $myrole = auth()->user()->multi_agentcode;
        if (auth()->user()->role == 'MainCoordinator') {

            $mychannel = AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
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
                ->whereIn('lead_sales.channel_type', $mychannel)
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '=', auth()->user()->id)
                // ->Orwhere('lead_locations.assign_to', '=', '136')
                ->when($mychannel, function ($query) use ($mychannel) {
                    if ($mychannel == 'TTF') {
                        return $query->whereIn('lead_locations.assign_to', ['136']);
                    } else if ($mychannel == 'ExpressDial') {
                        return $query->whereIn('lead_locations.assign_to', ['583']);
                    } else {
                        return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                    }
                })
                ->whereDate('lead_sales.updated_at', Carbon::today())


                // ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        } else {
            // return "all";
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
                // ->LeftJoin(
                //         'lead_locations',
                //         'lead_locations.lead_id',
                //         '=',
                //         'lead_sales.id'
                //     )
                //     ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.10', '1.21'])
                ->where('lead_locations.assign_to', '=', '136')
                // ->when($myrole, function ($query) use ($myrole) {
                //     if ($myrole == '1') {
                //         // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                //         //
                //         return $query->where('users.agent_code', auth()->user()->agent_code);
                //     }else{
                //         return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                //     }
                //     // else if($myrole == 'KHICordination'){
                //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                //     // }
                //     // else {
                //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                //     // }
                // })
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                // -> whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('verification_forms.assing_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                ->groupBy('verification_forms.lead_no')
                ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
        }
        // })->name('activation.proceed');
    }
    public function emirate_agent_proceed_lead_daily()
    {
        // Route::GET('proceed-lead', function () {

        // $operation = lead_sale::whereStatus('1.07')->get();
        // $operation =
        // return auth()->user()->agent_code;
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'lead_sales.emirates')
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            // ->where('lead_locations.assign_to', '=', 136)
            ->when($mychannel, function ($query) use ($mychannel) {
                if ($mychannel == 'TTF') {
                    return $query->whereIn('lead_locations.assign_to', ['136']);
                } else if ($mychannel == 'ExpressDial') {
                    return $query->whereIn('lead_locations.assign_to', ['583']);
                } else {
                    return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                }
            })
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))

            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-proceed-request-only', compact('operation'));

        // })->name('activation.proceed');
    }
    public static function mycount()
    {
        return $operation = verification_form::select("verification_forms.lead_no")
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            // ->where('verification_forms.status', '1.10')
            ->where('lead_locations.assign_to', '=', auth()->user()->id)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function mycount_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = verification_form::select("verification_forms.lead_no")
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)

            // ->where('verification_forms.status', '1.10')
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->when($mychannel, function ($query) use ($mychannel) {
                if ($mychannel == 'TTF') {
                    return $query->whereIn('lead_locations.assign_to', ['136']);
                } else if ($mychannel == 'ExpressDial') {
                    return $query->whereIn('lead_locations.assign_to', ['583']);
                } else {
                    return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                }
            })
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function emirate_mycount_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        return $operation = verification_form::select("verification_forms.lead_no")
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                '=',
                'lead_sales.id'
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            // ->where('verification_forms.status', '1.10')
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            // ->where('lead_locations.assign_to', '=', 136)
            ->when($mychannel, function ($query) use ($mychannel) {
                if ($mychannel == 'TTF') {
                    return $query->whereIn('lead_locations.assign_to', ['136']);
                } else if ($mychannel == 'ExpressDial') {
                    return $query->whereIn('lead_locations.assign_to', ['583']);
                } else {
                    return $query->whereIn('lead_locations.assign_to', ['136', '583']);
                }
            })
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function later_today()
    {
        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

            return $operation = verification_form::select("verification_forms.lead_no")
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    '=',
                    'verification_forms.lead_no'
                )
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
                // ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.channel_type', $mychannel)

                ->whereIn('lead_sales.status', ['1.06'])
                // ->where('lead_locations.assign_to', '=', auth()->user()->id)
                ->whereDate('lead_sales.later_date', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()
                ->count();
        } else {

            return $operation = verification_form::select("verification_forms.lead_no")
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
                // ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.06'])
                ->where('users.agent_code', auth()->user()->agent_code)
                // ->where('lead_locations.assign_to', '=', auth()->user()->id)
                ->whereDate('lead_sales.later_date', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()
                ->count();
        }
    }
    public static function emirate_later_today()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        return $operation = verification_form::select("verification_forms.lead_no")
            ->LeftJoin(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
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
            // ->where('verification_forms.status', '1.10')
            ->whereIn('lead_sales.status', ['1.06'])
            ->whereIn('lead_sales.channel_type', $mychannel)
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->groupBy('verification_forms.lead_no')
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->get()
            ->count();
    }
    public static function all_later()
    {
        if (auth()->user()->role == 'MainCoordinator') {
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

            return $operation = verification_form::select("verification_forms.lead_no")
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    '=',
                    'verification_forms.lead_no'
                )
                // ->LeftJoin(
                //     'users',
                //     'users.id',
                //     '=',
                //     'lead_sales.saler_id'
                // )
                ->LeftJoin(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.channel_type', $mychannel)

                // ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.06'])
                // ->where('users.agent_code', auth()->user()->agent_code)

                // ->where('lead_locations.assign_to', '=', auth()->user()->id)
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()
                ->count();
        } else {


            return $operation = verification_form::select("verification_forms.lead_no")
                ->LeftJoin(
                    'lead_sales',
                    'lead_sales.id',
                    '=',
                    'verification_forms.lead_no'
                )
                // ->LeftJoin(
                //     'users',
                //     'users.id',
                //     '=',
                //     'lead_sales.saler_id'
                // )
                ->LeftJoin(
                    'lead_locations',
                    'lead_locations.lead_id',
                    '=',
                    'lead_sales.id'
                )
                // ->where('verification_forms.status', '1.10')
                ->whereIn('lead_sales.status', ['1.06'])
                ->where('users.agent_code', auth()->user()->agent_code)

                // ->where('lead_locations.assign_to', '=', auth()->user()->id)
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()
                ->count();
        }
    }
    public static function agent_count()
    {
        return $operation = verification_form::select("verification_forms.lead_no")
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
            ->whereIn('lead_sales.status', ['1.10', '1.21'])
            ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function agent_count_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = verification_form::select("verification_forms.lead_no")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.10')
            ->where('lead_locations.assign_to', '!=', auth()->user()->id)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function emirate_agent_count_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        return $operation = verification_form::select("verification_forms.lead_no")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('lead_sales.status', '1.10')
            ->where('lead_locations.assign_to', '!=', 136)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function paidlead_daily()
    {
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        return $operation = activation_form::where('status', '1.02')->where('pay_status', 'Paid')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function followup_daily()
    {
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = lead_sale::whereIn('status', ['1.19', '1.20'])
            ->whereIn('lead_sales.channel_type', $mychannel)

            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function emirate_followup_daily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        return $operation = lead_sale::whereIn('status', ['1.19', '1.20'])
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function followup_monthly()
    {
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        return $operation = lead_sale::whereIn('status', ['1.19', '1.20'])
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function paidlead_monthly()
    {
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        return $operation = activation_form::where('status', '1.02')->where('pay_status', 'Paid')
            // ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function freelead_daily()
    {
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        return $operation = activation_form::where('status', '1.02')->where('pay_status', 'Free')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function freelead_monthly()
    {
        // $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        return $operation = activation_form::where('status', '1.02')->where('pay_status', 'Free')
            // ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()->count();
        // $operation = lead_sale::wherestatus('1.01')->get();
        // return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public static function activation_amount()
    {
        // return $userid;
        return $k = activation_form::select('pay_charges')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            // ->where('users.agent_code', $userid)
            // ->where('activation_forms.activation_date', $date)
            // ->where('')
            ->whereMonth('activation_forms.activation_date', Carbon::now()->month)
            ->whereYear('activation_forms.activation_date', Carbon::now()->year)

            ->sum('activation_forms.pay_charges');
    }
    public static function activation_amount_daily()
    {
        // return $userid;
        return $k = activation_form::select('pay_charges')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            // ->where('users.agent_code', $userid)
            // ->where('activation_forms.activation_date', $date)
            // ->where('')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->sum('activation_forms.pay_charges');
    }
    public static function ManagerCompleteTarget()
    {
        $r = TargetAssignerManager::select('target_assigner_managers.target')
            ->LeftJoin(
                'call_centers',
                'call_centers.id',
                'target_assigner_managers.call_center_id'
            )
            // ->where('manager_targets.sim_type', $type)
            ->where('call_centers.call_center_code', auth()->user()->agent_code)
            ->where('target_assigner_managers.month', Carbon::now()->month)

            ->sum('target_assigner_managers.target');
        // return $r->
        // $count =
        if ($r) {
            return $r->target;
        } else {
            return "0";
        }
    }
    //
    //
    public static function monthly_nonverified()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $id = 'Non Verified Lead';
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
            ->LeftJoin(
                'users',
                'users.name',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.status', '1.03')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('lead_sales.saler_id', auth()->user()->id)
            ->get()->count();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    //
    public static function daily_nonverified()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $id = 'Non Verified Lead';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = lead_sale::select('lead_sales.id')
            // $user =  DB::table("subjects")->select('subject_name', 'id')
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
            // ->LeftJoin(
            //     'users',
            //     'users.name',
            //     'lead_sales.saler_id'
            // )
            ->where('lead_sales.status', '1.03')
            ->whereIn('lead_sales.channel_type', $mychannel)

            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('lead_sales.saler_id', auth()->user()->id)
            ->get()->count();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    public static function daily_pending()
    {
        // Route::get('mylead', function () {
        //
        // return auth()->user()->role;

        $id = 'Non Verified Lead';
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
            ->LeftJoin(
                'users',
                'users.name',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.status', '1.07')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('lead_sales.saler_id', auth()->user()->id)
            ->get()->count();
        // return view('dashboard.view-all-lead', compact('operation', 'id'));
        // $operation = lead_sale::wherestatus('1.01')->get();

        // return view('dashboard.mylead', compact('operation'));
        // });
    }
    //
    public static function monthly_followup()
    {
        return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
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
            ->whereIn(
                'lead_sales.status',
                [
                    '1.19', '1.20', '1.21'
                ]
            )
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            ->groupBy('verification_forms.lead_no')
            ->get()->count();
    }
    public static function daily_followup()
    {
        return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to")
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
            ->whereIn(
                'lead_sales.status',
                [
                    '1.19', '1.20', '1.21'
                ]
            )
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->groupBy('verification_forms.lead_no')
            ->get()->count();
    }
    //
    public static function call_center_pending($call_center)
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        return $operation = verification_form::select("verification_forms.lead_no")
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
            ->whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21']
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('users.agent_code', $call_center)
            ->groupBy('verification_forms.lead_no')
            ->get()->count();
    }
    public static function TotalLeadVerifiedDailyBoss($id, $leadtype, $userid)
    {
        // return $id;
        // return "ok";
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')

            ->Join(
                'verification_forms',
                'verification_forms.verify_agent',
                '=',
                'users.id'
            )
            ->Join(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            // ->where('verification_forms.status', $id)
            ->where('lead_sales.lead_type', $leadtype)
            // ->where('lead_sales.channel_type',$channel)
            ->where('users.id', $userid)
            ->whereMonth('verification_forms.created_at', Carbon::now()->month)
            ->whereYear('verification_forms.created_at', Carbon::now()->year)
            // ->get();
            // ->whereDate('verification_forms.created_at', Carbon::today())
            ->count();
    }
    public static function TotalLeadVerifiedDailyBossToday($id, $leadtype, $userid)
    {
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')

            ->Join(
                'verification_forms',
                'verification_forms.verify_agent',
                '=',
                'users.id'
            )
            ->Join(
                'lead_sales',
                'lead_sales.id',
                '=',
                'verification_forms.lead_no'
            )
            // ->where('verification_forms.status', $id)
            ->where('lead_sales.lead_type', $leadtype)
            // ->where('lead_sales.channel_type',$channel)
            ->where('users.id', $userid)
            // ->whereMonth('verification_forms.created_at', Carbon::now()->month)
            // ->whereYear('verification_forms.created_at', Carbon::now()->year)
            // ->get();
            ->whereDate('verification_forms.created_at', Carbon::today())
            ->count();
    }
    public static function TotalPendingLead()
    {
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->LeftJoin(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            ->where('lead_sales.status', 1.01)
            // ->where('lead_sales.lead_type', $type)
            // ->when($channel, function ($query) use ($channel) {
            //     if ($channel == 'Combined') {
            //         return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
            //     } else {
            //         return $query->where('lead_sales.channel_type', $channel);
            //         // return $query->whereIn('partner.id', $deals_in_daily);
            //     }
            // })
            // ->where('users.agent_code', trim($id))
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->whereDate('lead_sales.created_', Carbon::today())
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function MonthlyTotalPendingLead()
    {
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->LeftJoin(
                'verification_forms',
                'verification_forms.lead_no',
                '=',
                'lead_sales.id'
            )
            ->where('lead_sales.status', 1.01)
            // ->where('lead_sales.lead_type', $type)
            // ->when($channel, function ($query) use ($channel) {
            //     if ($channel == 'Combined') {
            //         return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
            //     } else {
            //         return $query->where('lead_sales.channel_type', $channel);
            //         // return $query->whereIn('partner.id', $deals_in_daily);
            //     }
            // })
            // ->where('users.agent_code', trim($id))
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->whereDate('lead_sales.created_', Carbon::today())
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
}
