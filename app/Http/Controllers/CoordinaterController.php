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
        // return auth()->user()->id;
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
        return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));

        // ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('coordination.view-proceed-request-only', compact('operation'));

        // ->count();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('coordination.view-proceed-request-only', compact('operation'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
    }
    //
    public function reprocess_CordinationLead($id)
    {
        // return $id;
        $users = \App\Models\User::role('Elife Active')->get();
        $audios = audio_recording::wherelead_no($id)->get();

        $data = verification_form::select('lead_sales.additional_document', "timing_durations.lead_generate_time", "verification_forms.*", "lead_sales.saler_id",'lead_sales.area')
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
        $countries = \App\Models\country_phone_code::select('name')->get();
        $emirates = \App\Models\emirate::select('name')->get();
        $countries = \App\Models\country_phone_code::select('name')->get();
        return view('coordination.add-reprocess-lead', compact('data', 'users', 'remarks', 'audios', 'countries', 'emirates'));
    }
    public function CordinationLead($id)
    {
        // return $id;
        $users = \App\Models\User::role('Elife Active')->get();
        $audios = audio_recording::wherelead_no($id)->get();

        $data = verification_form::select('lead_sales.emirates as emirate_location', 'lead_sales.additional_document', "timing_durations.lead_generate_time", "verification_forms.*", "lead_sales.saler_id", 'lead_sales.channel_type', 'lead_sales.eti_lead_id')
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

                $countries = \App\Models\country_phone_code::select('name')->get();
        $emirates = \App\Models\emirate::select('name')->get();

        return view('coordination.add-location-lead', compact('data', 'users', 'remarks', 'audios','countries','emirates'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('manager.mygrplead', compact('operation'));
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
            return view('coordination.my-lead-junaid', compact('operation'));
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
            return view('manager.mygrplead', compact('operation'));
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
            // return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('manager.mygrplead', compact('operation'));
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
            // return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('manager.mygrplead', compact('operation'));
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
            // return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));

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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
            return view('coordination.view-proceed-request-only', compact('operation'));
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
        return view('coordination.view-proceed-request-only', compact('operation'));

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
        // return auth()->user()->id;

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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
        // return view('manager.view-all-active', compact('operation', 'id'));
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
    //
        public function leadlocationstore(Request $request){
        //
        // return $request;
        // return salman
        if ($request->reverify_remarks != '') {
            $validatedData = Validator::make($request->all(), [
                'reverify_remarks' => 'required|string',
            ]);
            // return "s";
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            // return "b";
            // return $request->lead_id;
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.01',
                'remarks' => $request->reverify_remarks,
                'date_time_follow' => $request->call_back_at_new,
                'emirates' => $request->emirates,
            ]);
            $dd = verification_form::findOrFail($request->ver_id);
            $dd->update([
                'status' => '1.01',
                'emirate_location' => $request->emirates,
            ]);
            // remark::create([
            //     'remarks' => $request->reverify_remarks,
            //     'lead_status' => '1.01',
            //     'source' => 'Lead Forward to Reviewe'
            //     'lead_id' => $request->lead_id,
            //     'lead_no' => $request->lead_id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            //     'user_agent' => auth()->user()->name,
            //     'user_agent_id' => auth()->user()->id,
            // ]);
            return response()->json(['success' => 'lead has been forwarded to reverify']);
            // return "Boom Reverify";
            // notify()->success('Lead has been forward to re verification');

            // return redirect()->back()->withInput();
            // return redirect(route('verification.final-cord-lead'));
        }
        if ($request->call_back_at_new != '') {
            $validatedData = Validator::make($request->all(), [
                'remarks_for_cordination' => 'required|string',
            ]);
            // return "s";
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            // return "b";
            // return $request->lead_id;
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.19',
                'remarks' => $request->remarks_for_cordination,
                'date_time_follow' => $request->call_back_at_new,
                'emirates' => $request->emirates,
            ]);
            $dd = verification_form::findOrFail($request->ver_id);
            $dd->update([
                'status' => '1.19',
                'emirate_location' => $request->emirates,
            ]);
            // remark::create([
            //     'remarks' => $request->call_back_at_new,
            //     'lead_status' => '1.03',
            //     'lead_id' => $request->lead_id,
            //     'lead_no' => $request->lead_id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
            //     'user_agent' => auth()->user()->name,
            //     'user_agent_id' => auth()->user()->id,
            // ]);
            // return
            // notify()->success('Lead has been follow up now');
            // return "Boom CallBack";
            $a = "whatsapp://send?text=🔃   %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name  %0a Follow Up";
            return response()->json(['success' => $a]);
            // return redirect()->back()->withInput();
            // return redirect(route('verification.final-cord-lead'));
        } else {
            // return $request;
            $ldate = date('h:i A');
            $validatedData = Validator::make($request->all(), [
                'add_location' => 'required|string',
                // 'add_lat_lng' => 'required',
                'assing_to' => 'required',
                'start_date' => 'required',
                'start_time' => 'required|after:' . $ldate,
                // 'end_date' => 'required',
                // 'end_time' => 'required|after:start_time',
                // 'lng' => 'required|numeric',
            ]);
            if ($validatedData->fails()) {
                // return redirect()->back()
                //     ->withErrors($validatedData)
                //     ->withInput();
                return response()->json(['error' => $validatedData->errors()->all()]);
            }
            if (!empty($request->add_lat_lng)) {

                $name = explode(',', $request->add_lat_lng);
                $lat = $name[0];
                $lng = $name[1];
            } else {
                $lat = '';
                $lng = '';
            }
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            if ($lead_data->status == '1.15') {
                return response()->json(['error' => ['Documents' => ['Rejected leads cannot be proceed, Please make new lead']]], 200);
            }
            $ld = lead_location::where('lead_id', $request->lead_id)->first();
            if ($ld) {
                $ld->update([
                    'assign_to' => $request->assing_to,
                    'location_url' => $request->add_location,
                    'lat' => $lat,
                    'lng' => $lng,
                ]);
            } else {
                lead_location::create([
                    'lead_id' => $request->lead_id,
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
            }
            // $start_date = Carbon::today()->toDateString();
            // $choosen_date = strtr($choosen_date, '/', '-');
            // $choosen_date =  date('Y-d-m H:i:s', strtotime($choosen_date));
            $choosen_date = $request->start_time;
            $carbon_date = Carbon::parse($choosen_date);
            $second_date  = $carbon_date->addHours(2);
            // return "choosen date: ". $choosen_date . ' Return Date' . $second_date->toTimeString;
            $lead_data = $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.10',
                'appointment_from' => date('H:i:s', strtotime($choosen_date)),
                'appointment_to' => date('H:i:s', strtotime($second_date)),
            ]);
            $dd = verification_form::findOrFail($request->ver_id);
            $dd->update([
                'status' => '1.10',
                'assing_to' => '1',
                'cordination_by' => auth()->user()->id,
                'emirate_location' => $request->emirates,
            ]);
            $planName = $lead_data->select_plan;
            $SelNumber = $lead_data->selected_number;
            $activation_charge = $lead_data->pay_status;
            // return "LocationLead";
            // var encodedURL = encodeURIComponent(some_url);
            //
            // $a = "whatsapp://send?text=New  %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a Number Selected: $lead_data->selected_number %0a Plan selected: FE125 %0a Data : 4GB %0a  Activation: $lead_data->pay_status  %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a Lead Location https://maps.google.com?q=$lng,$lat %0a %0a Customer Location";
            // $a = "https://api.whatsapp.com/send?text= *Verified at Location*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a";
            // for ($i = 0; $i < $count; $i++) {
            //     $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
            // }
            // $a .= "%0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a Verified %0a &phone=$wp_num";
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
                $a = "https://api.whatsapp.com/send?text= *New Verified Lead*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a";
                for ($i = 0; $i < $count; $i++) {
                    $a .= "Number Selected: *$selected_number[$i]*  %0a PassCode = *$passcode[$i]* %0a Plan selected: *$plan_name[$i]* %0a  Activation: $ac[$i] %0a";
                }
                $a .= "%0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a Lead Location https://maps.google.com?q=$lng,$lat %0a &phone=971556323867";
                return response()->json(['success' => $a]);
            } else {
                // return $SelNumber;
                $plan = \App\plan::findorfail($planName);
                $numberd = numberdetail::where('number', $SelNumber)->first();

                $plan_name = $plan->plan_name;
                $data_gb = $plan->data;
                if ($numberd) {
                    $selected_number = $numberd->number;
                    $passcode = $numberd->passcode;
                } else {
                    $selected_number = $lead_data->customer_number;
                    $passcode = 'MNP';
                }
                $pay_status = $activation_charge;
                $a = "https://api.whatsapp.com/send?text= **New Verified Lead*  %0a Lead No: $lead_data->lead_no %0a Date: $lead_data->date_time %0a Customer Name: $lead_data->customer_name %0a Customer Number $lead_data->customer_number %0a %0a %0a *Sim Type $lead_data->sim_type* %0a Number Selected: *$selected_number*  %0a PassCode = *$passcode* %0a Plan selected: *$plan_name* %0a Activation: $pay_status  %0a %0a %0a Gender: $lead_data->gender  %0a  Emirates location: $lead_data->emirates  %0a Nationality: $lead_data->nationality  %0a Document: ID $lead_data->additional_document %0a  Language: $lead_data->language  %0a Sales person: $lead_data->saler_name %0a Lead Location https://maps.google.com?q=$lng,$lat %0a &phone=971556323867";
                return response()->json(['success' => $a]);
            }
            // return response()->json(['success' => $a]);
            // notify()->success('Location Added succesfully');

            // return redirect()->back()->withInput();
            // return redirect(route('verification.final-cord-lead'));


    }
        }
}
