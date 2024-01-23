<?php

namespace App\Http\Controllers;

use App\Models\lead_sale;
use App\Models\remark;
use App\Models\timing_duration;
use App\Models\verification_form;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class MainController extends Controller
{
    //
    public function p2p_form(){
        $plans = \App\Models\plan::wherestatus('1')->get();
        $last = \App\Models\lead_sale::latest()->first();
        return view('dashboard.add-lead-p2p',compact('plans','last'));
    }
    //
    public function p2p_submit(Request $request){
        // return $request;
        $validator = Validator::make($request->all(), [ // <---
            'cname' => 'required|string',
            'cnumber' => 'required|string',
            'age' => 'required|numeric|min:20|not_in:20',
            'email' => 'required',
            'plan_new' => 'required',
            'selected_number' => 'required',
        ]);
        //
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        //
        $lead_date = Carbon::now()->toDateTimeString();
        //
        $data = lead_sale::create([
            'customer_name' =>$request->cname,
            'customer_number' =>$request->cnumber,
            'age' =>$request->age,
            'email' =>$request->email,
            'select_plan' =>$request->plan_new,
            'selected_number' =>$request->selected_number,
            'sim_type' => 'P2P',
            'channel_type' => 'TTF',
            'lead_type' => 'prepaid',
            'status' => '1.10',
            'pay_status' => 'Free',
            'pay_charges' => 'Free',
            // 'device' => $request->status,
            'date_time' => $lead_date,
            'date_time_follow' => $lead_date,
            'saler_id' => auth()->user()->id,
            'saler_name' => auth()->user()->name,
            'lead_no' => $request->leadnumber,
            'remarks'=> 'Please proceed'
        ]);
        // remark::create([
        //     'remarks' => 'Please proceed',
        //     'lead_status' => '1.10',
        //     'lead_id' => $data->id,
        //     'lead_no' => $data->id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
        //     'user_agent' => 'Sale',
        //     'user_agent_id' => auth()->user()->id,
        // ]);
        timing_duration::create([
            'lead_no' => $data->id,
            'lead_generate_time' => Carbon::now()->toDateTimeString(),
            'sale_agent' => auth()->user()->id,
            'status' => '1.01',

        ]);
        notify()->success('P2P Lead has been created succesfully');
        return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
    }
    public function p2p_status($status){
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
        ->where('lead_sales.saler_id', auth()->user()->agent_code)
        ->where('lead_sales.channel_type', 'TTF')
        ->where('lead_sales.status', $status)
        ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
        // ->OrderBy('lead')
        ->orderBy('lead_sales.updated_at', 'desc')
        ->get();
        return view('dashboard.manager.mygrplead', compact('operation'));
    }
    //
    public function mnp_status($status){
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
        ->where('lead_sales.saler_id', auth()->user()->agent_code)
        ->where('lead_sales.channel_type', ['TTF','ExpressDial'])
        ->where('lead_sales.status', $status)
        ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
        // ->OrderBy('lead')
        ->orderBy('lead_sales.updated_at', 'desc')
        ->get();
        return view('dashboard.manager.mygrplead', compact('operation'));
    }
    //
    public function all_lead_call_center(Request $reqeust){
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
            ->whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21']
            )
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->where('users.agent_code', $reqeust->call_center)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        return view('dashboard.view-pending-request-only', compact('operation'));
    // }
    }
    //
    public function all_lead(){
        if(auth()->user()->role == 'Cordination' || auth()->user()->role == 'Manager' || auth()->user()->role == 'NumberSuperAdmin'){
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
            ->whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21']
            )
            ->where('users.agent_code',auth()->user()->agent_code)
            ->groupBy('verification_forms.lead_no')
            ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-pending-request-only', compact('operation'));
        }
        else{


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
            -> whereIn(
                'lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.16', '1.17', '1.19', '1.20', '1.21']
            )
            ->groupBy('verification_forms.lead_no')
            ->get();
            // $operation = verification_form::wherestatus('1.10')->get();
            return view('dashboard.view-proceed-request-only', compact('operation'));
            // ->whereIn('lead_sales.status', ['1.06'])
            // ->where('lead_sales.saler_id', auth()->user()->id)
            // ->get();
            // return view('dashboard.mylead', compact('operation'));
        }

    }
    public function checknumber(){
        return view('coordination.search-all-number');
    }
    public function NumberMailCategory(){
        return view('coordination.number-mail-category');
    }
    public function checkleadnumber(){
        return view('coordination.search-all-lead');
    }
    public function checknumberoriginal(){
        return view('coordination.search-original-number');
    }
    public function checknumberoriginalreserved(){
        return view('coordination.search-original-number-reserved');
    }
    public function checkreservednumber(){
        return view('coordination.search-reserved-number');
    }
    public function checkcustomernumber(){
        return view('coordination.checkcustomernumber');
    }
}
