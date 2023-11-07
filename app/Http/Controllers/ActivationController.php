<?php

namespace App\Http\Controllers;

use App\Models\activation_document;
use App\Models\activation_form;
use App\Models\lead_sale;
use App\Models\remark;
use App\Models\numberdetail;
use App\Models\choosen_number;
use App\Models\User;
use App\Models\verification_form;
use Carbon\Carbon;
// use App\Models\lead_sale;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ActivationController extends Controller
{
    //
    public function myactive()
    {
        $id = 'Activated Leads';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
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
            ->where('lead_sales.status', '1.02')
            ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public function totalrejectlead()
    {
        $id = 'Activated Leads';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
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
            ->whereIn('lead_sales.status', ['1.15', '1.04'])
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public function totalrejectleaddaily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Activated Leads';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.04', '1.15'])
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public function emiratetotalrejectleaddaily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $id = 'Activated Leads';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
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
            ->whereIn('lead_sales.channel_type', $mychannel)
            ->whereIn('lead_sales.status', ['1.04', '1.15'])
            ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }


    public  function totalfollowlead()
    {
        $id = 'Activated Leads';
        $operation = lead_sale::select("timing_durations.lead_generate_time", "timing_durations.lead_accept_time", "timing_durations.lead_proceed_time", "lead_sales.*", "status_codes.status_name")
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
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-lead', compact('operation', 'id'));
    }
    public  function totalreverifylead()
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
            ->where('verification_forms.status', '1.05')
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));
    }
    public  function totalreverifyleaddaily()
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'users.agent_code')
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
            ->where('lead_sales.status', '1.05')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));
    }
    public  function emiratetotalreverifyleaddaily()
    {
        $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to", 'users.agent_code')
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
            ->where('lead_sales.status', '1.05')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));
    }
    public  function totalrefollowup()
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
            ->where('verification_forms.status', '1.21')
            // ->where('lead_locations.assign_to', '=', auth()->user()->id)
            // ->where('users.agent_code', auth()->user()->agent_code)
            // ->where('verification_forms.assing_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();
        // $operation = verification_form::wherestatus('1.10')->get();
        // return view('number.number-list-activation', compact('operation'));
        return view('dashboard.my-lead-junaid', compact('operation'));
    }
    public function totalactiveleaddaily()
    {
        $id = 'Activated Leads';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = activation_form::where('status', '1.02')
            // ->where('activation_forms.channel_type',)
            ->whereIn('activation_forms.channel_type', $mychannel)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public function emiratetotalactiveleaddaily()
    {
        $id = 'Activated Leads';
        $operation = activation_form::whereIn('status', ['1.02', '1.08'])
            ->where('activation_forms.activation_sold_by', auth()->user()->id)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public function totalactivelead()
    {
        $id = 'Activated Leads';
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        $operation = activation_form::where('status', '1.02')
            ->whereIn('activation_forms.channel_type', $mychannel)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }

    public function paidlead()
    {
        $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        $operation = activation_form::where('status', '1.02')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->where('pay_status', 'Paid')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public function paidleaddaily()
    {
        $id = 'Activated Leads';
        // $operation = activation_form::where('status','1.02')->get();
        $operation = activation_form::where('status', '1.02')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->where('pay_status', 'Paid')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public function freelead()
    {
        $id = 'Activated Leads';
        $operation = activation_form::where('status', '1.02')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->where('pay_status', 'Free')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }
    public function freeleaddaily()
    {
        $id = 'Activated Leads';
        $operation = activation_form::where('status', '1.02')
            ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->where('pay_status', 'Free')->get();
        // $operation = lead_sale::wherestatus('1.01')->get();
        return view('dashboard.view-all-active', compact('operation', 'id'));
    }

    public function ConfirmLead(Request $request)
    {
        // return $request;
        $a = activation_form::findorfail($request->leadid);
        $a->remarks = "Confirmed";
        $a->save();
        notify()->success('Lead has been marked as Confirmed');
        return response()->json(['success' => 'Lead has been marked as Confirmed, please wait meanwhile we are redirecting you....!!!']);
    }
    public function ActiveNew(Request $request)
    {
        // return $request;
        if ($request->simtype == 'New') {
            // s
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date.*' => 'required|date|before:tomorrow',
                'activation_sr_no.*' => 'required|numeric',
                'activation_service_order.*' => 'required|numeric',
                // 'activation_selected_no.*' => 'required|numeric|unique:activation_forms,activation_selected_no',
                'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                'activation_sold' => 'required',
                'activation_sold_by' => 'required',
                'saler_id' => 'required',
                'cname' => 'required|string|max:100',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                'additional_document_activation' => 'required',
                'documents.*' => 'required',
                'sr_photo.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()->all()]);
            }

            foreach ($request->plan_new as $key => $value) {
                // return $value;
                // return $key;
                // echo $key . '<br>';
                // $date = str_replace('/',
                // '-',
                // return $request['sr_photo'][1];
                if ($file = $request->file('sr_photo')) {
                    // AzureCodeStart
                    // $mytime = Carbon::now();
                    // $ext =  $mytime->toDateTimeString();
                    // $image2 = $ext . '-' . $file[$key]->getClientOriginalName();
                    $image2 = file_get_contents($file[$key]);
                    $originalFileName = time() . $file[$key]->getClientOriginalName();
                    $multi_filePath = 'documents' . '/' . $originalFileName;
                    \Storage::disk('azure')->put($multi_filePath, $image2);
                    // AzureCodeEnd
                    // LocalStorageStart
                    $mytime = Carbon::now();
                    $ext =  $mytime->toDateTimeString();
                    // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                    $name = $originalFileName;

                    $file[$key]->move('documents', $name);
                    $input['path'] = $name;
                    // LocalStorageEnd
                }
                // return $request['activation_date'][1];
                // echo $request->activation_date[$key] . '<br>';
                // );

                // $request->activation_date[1];
                // return Session::get('front_image');
                // return $request->front_id;
                // $activation_date = date('Y-m-d', strtotime($date));
                // if (empty(Session::get('front_image')) || empty($request->front_id)) {
                if (empty($request->front_id)) {
                    // return response()->json(['error' => 'Please Choose Documents']);
                    $front_id = 'default.png';
                    // return response()->json(['error' => ['Documents' => ['Front Document is Invalid or Missing, Please Reupload.']]], 200);
                }
                if (empty($request->back_id)) {
                    $back_id = 'default.png';
                    // if (empty(Session::get('back_image'))|| empty($request->back_id)) {
                    // return response()->json(['error' => 'Please Choose Documents']);
                    // return response()->json(['error' => ['Documents' => ['Back Document is Invalid or Missing, Please Reupload.']]], 200);
                }
                $k = activation_form::create([
                    'cust_id' => $request->lead_id,
                    'lead_no' => $request->lead_no,
                    'lead_id' => $request->lead_id,
                    'verification_id' => $request->verification_id,
                    'customer_name' => $request->cname,
                    'customer_number' => $request->cnumber,
                    'age' => $request->age,
                    'gender' => $request->gender,
                    'nationality' => $request->nation,
                    'language' => $request->language,
                    'original_emirate_id' => $request->emirate_id,
                    // 'emirate_number' => $request->customer_name,
                    'additional_documents' => $request->additional_document,
                    'sim_type' => $request->simtype,
                    'number_commitment' => $request->numcommit,
                    // 'contract_commitment' => $request->customer_name,
                    'select_plan' => $value,
                    'benefits' => $request->customer_name,
                    // 'benefits' => $request->customer_name,
                    // 'total' => $request->customer_name,
                    'emirate_location' => $request->emirates,
                    'verify_agent' => $request->activation_sold_by,
                    // 'remarks' => $request->customer_name,
                    // 'pay_status' => $request->customer_name,
                    // 'pay_charges' => $request->customer_name,
                    'activation_date' => $request['activation_date'][$key],
                    'activation_sr_no' => $request['activation_sr_no'][$key],
                    'activation_service_order' => $request['activation_service_order'][$key],
                    'pay_charges' => $request['activation_rate_new'][$key],
                    'pay_status' => $request['activation_charges_new'][$key],
                    'activation_selected_no' => $request['activation_selected_no'][$key],
                    'activation_sim_serial_no' => $request->activation_sim_serial_no,
                    'activation_emirate_expiry' => Carbon::now(),
                    'activation_sold_by' => auth()->user()->id,

                    // 'emirate_id_front' => Session::get('front_image'),
                    // 'emirate_id_back' => Session::get('back_image'),
                    'emirate_id_front' => $front_id,
                    'emirate_id_back' => $back_id,
                    'activation_screenshot' => $name,

                    'saler_id' => $request->saler_id,
                    'channel_type' => $request->channel_type,
                    // 'later' => $request->customer_name,
                    // 'recording' => $request->customer_name,
                    // 'assing_to' => $request->customer_name,
                    // 'backoffice_by' => $request->customer_name,
                    // 'cordination_by' => $request->customer_name,
                    'date_time' => Carbon::now()->toDateTimeString(),
                    'status' => '1.02',
                    // 'selected_number' => $request->customer_name,
                    // 'flexible_minutes' => $request->customer_name,
                    // 'data' => $request->customer_name,
                ]);
                $ntc = lead_sale::select('call_centers.notify_email', 'call_centers.numbers')
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

                $link = route('view.lead', $request->lead_id);

                $details = [
                    'lead_id' => $request->lead_id,
                    'lead_no' => $request->lead_no,
                    'customer_name' =>  $request->cname,
                    'customer_number' => $request->cnumber,
                    'selected_number' => $request['activation_selected_no'][$key],
                    'sim_type' => $request->simtype,
                    'numbers' => $ntc->numbers,
                    'link' => $link,
                    // 'Plan' => $number,
                    // 'AlternativeNumber' => $alternativeNumber,
                ];
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
                    ->cc(['salmanahmed334@gmail.com'])
                    // ->queue(new App\Models\Mail\ActivationMail($details));
                    ->queue(new \App\Models\Mail\ActivationMail($details, $subject));
                \App\Models\Http\Controllers\WhatsAppController::ActivationWhatsApp($details);
            }


            if (!empty($request->selnumber)) {
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
                        $k->status = 'Active';
                        $k->save();
                        // CHANGE LATER
                        $cn = choosen_number::select('choosen_numbers.id')
                            ->where('number_id', $dn->id)
                            ->first();
                        if ($cn) {
                            $cnn = choosen_number::findorfail($cn->id);
                            $cnn->status = '2';
                            $cnn->save();
                        }
                        // CHANGE LATER
                    }
                    // return $d->id;
                    // return "number has been reserved";
                }
            }
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];



            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // AzureCodeStart
                        // $image2 = file_get_contents($request->file('documents'));
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'documents' . '/' . $originalFileName;
                        \Storage::disk('azure')->put(
                            $multi_filePath,
                            $image2
                        );
                        // AzureCodeEnd
                        // LocalStorageStart
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
                        // LocalStorageEnd
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }

            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            $lead_data =  $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.02',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.02',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            //
            //


            // if(auth()->user()->role != 'sale')
            //
            notify()->success('New Activation has been created succesfully');
            return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
        } else if ($request->simtype == 'HomeWifi') {

            // return $request;
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required|date|before:tomorrow',
                'activation_sr_no' => 'required',
                'activation_selected_no' => 'required',
                'sr_photo' => 'required',
                'cname' => 'required|string|max:100',
                // 'activation_service_order' => 'required',
                // 'activation_selected_no' => 'required',
                // 'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                // 'activation_sold' => 'required',
                // 'activation_sold_by' => 'required',
                // 'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                // 'additional_document_activation' => 'required',
                // 'additional_document_activation.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                // return redirect()->back()
                //     ->withErrors($validatedData)
                //     ->withInput();
                return response()->json(['error' => $validatedData->errors()->all()]);
            }
            // if ($file = $request->file('emirate_id_front')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $name = Str::slug($name, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file->move('documents', $name);
            //     $input['path'] = $name;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file1 = $request->file('emirate_id_back')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     $name1 = Str::slug($name1, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file1->move('documents', $name1);
            //     $input['path'] = $name1;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file2 = $request->file('activation_screenshot')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name2 = $ext . '-' . $file->getClientOriginalName();
            //     $name2 = Str::slug($name2, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file2->move('documents', $name2);
            //     $input['path'] = $name2;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            $activation_date = $request->activation_date;
            // $activation_date = date(
            //     'Y-m-d',
            //     strtotime($date)
            // );
            if ($file = $request->file('sr_photo')) {
                // AzureCodeStart
                // $mytime = Carbon::now();
                // $ext =  $mytime->toDateTimeString();
                // $image2 = $ext . '-' . $file[$key]->getClientOriginalName();
                $image2 = file_get_contents($file);
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'documents' . '/' . $originalFileName;
                \Storage::disk('azure')->put(
                    $multi_filePath,
                    $image2
                );
                // AzureCodeEnd
                // LocalStorageStart
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                $name = $originalFileName;

                $file->move('documents', $name);
                $input['path'] = $name;
                // LocalStorageEnd
            }
            // if ($file = $request->file('sr_photo')) {
            //     // AzureCodeStart
            //     $image2 = file_get_contents($request->file('front_img'));
            //     $originalFileName = time() . $file->getClientOriginalName();
            //     $multi_filePath = 'documents' . '/' . $originalFileName;
            //     \Storage::disk('azure')->put($multi_filePath, $image2);
            //         // AzureCodeEnd
            //     // LocalStorageStart
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $file->move('documents', $name);
            //     $input['path'] = $name;
            //     // LocalStorageEnd
            // }
            else {
                $name = '';
            }
            // return $name;
            // if (empty(Session::get('front_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Front Document is Missing, Please Reupload.']]], 200);
            // }
            // if (empty(Session::get('back_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Back Document is Missing, Please Reupload.']]], 200);
            // }
            if (empty($request->front_id)) {
                $front_id = 'default.png';
                // return response()->json(['error' => 'Please Choose Documents']);
                // return response()->json(['error' => ['Documents' => ['Front Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            if (empty($request->back_id)) {
                // if (empty(Session::get('back_image'))|| empty($request->back_id)) {
                // return response()->json(['error' => 'Please Choose Documents']);
                $back_id = 'default.png';
                // return response()->json(['error' => ['Documents' => ['Back Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->cnumber,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_mnp,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => $request->activation_date,
                'activation_sr_no' => $request->activation_sr_no,
                'activation_service_order' => $request->activation_sr_no,
                'pay_charges' => '0',
                'pay_status' => 'Free',
                'activation_selected_no' => $request->activation_selected_no,
                'activation_sim_serial_no' => $request->activation_sr_no,
                'activation_emirate_expiry' => Carbon::now(),
                'activation_sold_by' => auth()->user()->id,
                // 'emirate_id_front' => Session::get('front_image'),
                // 'emirate_id_back' => Session::get('back_image'),
                'emirate_id_front' => $front_id,
                'emirate_id_back' => $back_id,
                'activation_screenshot' => $name,
                'saler_id' => $request->saler_id,
                'channel_type' => $request->channel_type,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '1.02',
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);


            // $k = activation_form::create([
            //     'cust_id' => $request->lead_id,
            //     'lead_no' => $request->lead_no,
            //     'lead_id' => $request->lead_id,
            //     'verification_id' => $request->verification_id,
            //     'customer_name' => $request->cname,
            //     'customer_number' => $request->cnumber,
            //     'age' => $request->age,
            //     'gender' => $request->gender,
            //     'nationality' => $request->nation,
            //     'language' => $request->language,
            //     'original_emirate_id' => $request->emirate_id,
            //     // 'emirate_number' => $request->customer_name,
            //     'additional_documents' => $request->additional_document_activation,
            //     'sim_type' => $request->simtype,
            //     'number_commitment' => $request->numcommit,
            //     // 'contract_commitment' => $request->customer_name,
            //     'select_plan' => $request->plan_elife,
            //     'benefits' => $request->customer_name,
            //     // 'benefits' => $request->customer_name,
            //     // 'total' => $request->customer_name,
            //     'emirate_location' => $request->emirates,
            //     'verify_agent' => $request->activation_sold_by,
            //     // 'remarks' => $request->customer_name,
            //     // 'pay_status' => $request->customer_name,
            //     // 'pay_charges' => $request->customer_name,
            //     'activation_date' => $activation_date,
            //     'activation_sr_no' => $request->activation_sr_no,
            //     'activation_service_order' =>'MNP',
            //     'activation_selected_no' => 'MNP',
            //     'activation_sim_serial_no' => 'MNP',
            //     'activation_emirate_expiry' => Carbon::now(),
            //     'activation_sold_by' => auth()->user()->id,
            //     'date_time' => Carbon::now()->toDateTimeString(),
            //     'status' => '1.02',
            //     'emirate_id_front' => Session::get('front_image'),
            //     'emirate_id_back' => Session::get('back_image'),
            //     'activation_screenshot' => $name,
            //     'saler_id' => $request->saler_id,

            // ]);
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];

            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }


            // if (!empty($request->additional_document_activation)) {
            //     foreach ($request->additional_document_activation as $key => $val) {
            //         // return $request->audio;
            //         // return $request->file();
            //         if ($file = $request->file('additional_document_activation')) {
            //             // return "a";
            //             // }
            //             $ext = date('d-m-Y-H-i');
            //             // return $file;
            //             $mytime = Carbon::now();
            //             $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            // $name = $originalFileName;

            //             $name = Str::slug($name, '-');

            //             // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //             $file[$key]->move('documents', $name);
            //             $input['path'] = $name;
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
            //         $data = activation_document::create([
            //             // 'resource_name' => $request->resource_name,
            //             'username' => 'activation',
            //             'document_name' => $name,
            //             'lead_id' => $request->lead_id,
            //             'activation_id' => $activation_id,
            //             'status' => '1.02',
            //             // 'batch_id' => $batch_id,
            //         ]);
            //         $d = lead_sale::findOrFail($request->lead_id);
            //         $d->update([
            //             'status' => '1.02',
            //         ]);
            //         $d = verification_form::findOrFail($request->ver_id);
            //         $d->update([
            //             'status' => '1.02',
            //             'assing_to' => $request->assing_to,
            //             'cordination_by' => auth()->user()->id,
            //         ]);
            //     }
            // }
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.02',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.02',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');

            $ntc = lead_sale::select('call_centers.notify_email', 'call_centers.numbers')
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
            $link = route('view.lead', $request->lead_id);
            //
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'customer_name' =>  $request->cname,
                'customer_number' => $request->cnumber,
                'selected_number' => $request->activation_selected_no,
                'sim_type' => $request->simtype,
                'numbers' => $ntc->numbers,
                'link' => $link,
                // 'Plan' => $number,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
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
            // $details = "";
            $subject = "";

            \Mail::to($to)
                ->cc(['salmanahmed334@gmail.com'])
                ->queue(new \App\Models\Mail\ActivationMail($details, $subject));
            //
            // $token = env('FACEBOOK_TOKEN');
            \App\Models\Http\Controllers\WhatsAppController::ActivationWhatsApp($details);
            //
            notify()->success('New Activation has been created succesfully');
            return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
        } else {
            // return $request;
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required|date|before:tomorrow',
                'activation_sr_no' => 'required',
                'sr_photo' => 'required',
                'cname' => 'required|string|max:100',
                // 'activation_service_order' => 'required',
                // 'activation_selected_no' => 'required',
                // 'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                // 'activation_sold' => 'required',
                // 'activation_sold_by' => 'required',
                // 'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                // 'additional_document_activation' => 'required',
                // 'additional_document_activation.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                // return redirect()->back()
                //     ->withErrors($validatedData)
                //     ->withInput();
                return response()->json(['error' => $validatedData->errors()->all()]);
            }
            // if ($file = $request->file('emirate_id_front')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $name = Str::slug($name, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file->move('documents', $name);
            //     $input['path'] = $name;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file1 = $request->file('emirate_id_back')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     $name1 = Str::slug($name1, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file1->move('documents', $name1);
            //     $input['path'] = $name1;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file2 = $request->file('activation_screenshot')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name2 = $ext . '-' . $file->getClientOriginalName();
            //     $name2 = Str::slug($name2, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file2->move('documents', $name2);
            //     $input['path'] = $name2;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            $activation_date = $request->activation_date;
            // $activation_date = date(
            //     'Y-m-d',
            //     strtotime($date)
            // );
            if ($file = $request->file('sr_photo')) {
                // AzureCodeStart
                // $mytime = Carbon::now();
                // $ext =  $mytime->toDateTimeString();
                // $image2 = $ext . '-' . $file[$key]->getClientOriginalName();
                $image2 = file_get_contents($file);
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'documents' . '/' . $originalFileName;
                \Storage::disk('azure')->put(
                    $multi_filePath,
                    $image2
                );
                // AzureCodeEnd
                // LocalStorageStart
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                $name = $originalFileName;

                $file->move('documents', $name);
                $input['path'] = $name;
                // LocalStorageEnd
            }
            // if ($file = $request->file('sr_photo')) {
            //     // AzureCodeStart
            //     $image2 = file_get_contents($request->file('front_img'));
            //     $originalFileName = time() . $file->getClientOriginalName();
            //     $multi_filePath = 'documents' . '/' . $originalFileName;
            //     \Storage::disk('azure')->put($multi_filePath, $image2);
            //         // AzureCodeEnd
            //     // LocalStorageStart
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $file->move('documents', $name);
            //     $input['path'] = $name;
            //     // LocalStorageEnd
            // }
            else {
                $name = '';
            }
            // return $name;
            // if (empty(Session::get('front_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Front Document is Missing, Please Reupload.']]], 200);
            // }
            // if (empty(Session::get('back_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Back Document is Missing, Please Reupload.']]], 200);
            // }
            if (empty($request->front_id)) {
                $front_id = 'default.png';
                // return response()->json(['error' => 'Please Choose Documents']);
                // return response()->json(['error' => ['Documents' => ['Front Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            if (empty($request->back_id)) {
                // if (empty(Session::get('back_image'))|| empty($request->back_id)) {
                // return response()->json(['error' => 'Please Choose Documents']);
                $back_id = 'default.png';
                // return response()->json(['error' => ['Documents' => ['Back Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->cnumber,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_mnp,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => $request->activation_date,
                'activation_sr_no' => $request->activation_sr_no,
                'activation_service_order' => $request->activation_sr_no,
                'pay_charges' => '0',
                'pay_status' => 'Free',
                'activation_selected_no' => $request->activation_selected_no,
                'activation_sim_serial_no' => $request->activation_sr_no,
                'activation_emirate_expiry' => Carbon::now(),
                'activation_sold_by' => auth()->user()->id,
                // 'emirate_id_front' => Session::get('front_image'),
                // 'emirate_id_back' => Session::get('back_image'),
                'emirate_id_front' => $front_id,
                'emirate_id_back' => $back_id,
                'activation_screenshot' => $name,
                'saler_id' => $request->saler_id,
                'channel_type' => $request->channel_type,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '1.02',
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);


            // $k = activation_form::create([
            //     'cust_id' => $request->lead_id,
            //     'lead_no' => $request->lead_no,
            //     'lead_id' => $request->lead_id,
            //     'verification_id' => $request->verification_id,
            //     'customer_name' => $request->cname,
            //     'customer_number' => $request->cnumber,
            //     'age' => $request->age,
            //     'gender' => $request->gender,
            //     'nationality' => $request->nation,
            //     'language' => $request->language,
            //     'original_emirate_id' => $request->emirate_id,
            //     // 'emirate_number' => $request->customer_name,
            //     'additional_documents' => $request->additional_document_activation,
            //     'sim_type' => $request->simtype,
            //     'number_commitment' => $request->numcommit,
            //     // 'contract_commitment' => $request->customer_name,
            //     'select_plan' => $request->plan_elife,
            //     'benefits' => $request->customer_name,
            //     // 'benefits' => $request->customer_name,
            //     // 'total' => $request->customer_name,
            //     'emirate_location' => $request->emirates,
            //     'verify_agent' => $request->activation_sold_by,
            //     // 'remarks' => $request->customer_name,
            //     // 'pay_status' => $request->customer_name,
            //     // 'pay_charges' => $request->customer_name,
            //     'activation_date' => $activation_date,
            //     'activation_sr_no' => $request->activation_sr_no,
            //     'activation_service_order' =>'MNP',
            //     'activation_selected_no' => 'MNP',
            //     'activation_sim_serial_no' => 'MNP',
            //     'activation_emirate_expiry' => Carbon::now(),
            //     'activation_sold_by' => auth()->user()->id,
            //     'date_time' => Carbon::now()->toDateTimeString(),
            //     'status' => '1.02',
            //     'emirate_id_front' => Session::get('front_image'),
            //     'emirate_id_back' => Session::get('back_image'),
            //     'activation_screenshot' => $name,
            //     'saler_id' => $request->saler_id,

            // ]);
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];

            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }


            // if (!empty($request->additional_document_activation)) {
            //     foreach ($request->additional_document_activation as $key => $val) {
            //         // return $request->audio;
            //         // return $request->file();
            //         if ($file = $request->file('additional_document_activation')) {
            //             // return "a";
            //             // }
            //             $ext = date('d-m-Y-H-i');
            //             // return $file;
            //             $mytime = Carbon::now();
            //             $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            // $name = $originalFileName;

            //             $name = Str::slug($name, '-');

            //             // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //             $file[$key]->move('documents', $name);
            //             $input['path'] = $name;
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
            //         $data = activation_document::create([
            //             // 'resource_name' => $request->resource_name,
            //             'username' => 'activation',
            //             'document_name' => $name,
            //             'lead_id' => $request->lead_id,
            //             'activation_id' => $activation_id,
            //             'status' => '1.02',
            //             // 'batch_id' => $batch_id,
            //         ]);
            //         $d = lead_sale::findOrFail($request->lead_id);
            //         $d->update([
            //             'status' => '1.02',
            //         ]);
            //         $d = verification_form::findOrFail($request->ver_id);
            //         $d->update([
            //             'status' => '1.02',
            //             'assing_to' => $request->assing_to,
            //             'cordination_by' => auth()->user()->id,
            //         ]);
            //     }
            // }
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.02',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.02',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'customer_name' =>  $request->cname,
                'customer_number' => $request->cnumber,
                'selected_number' => $request->cnumber,
                'sim_type' => $request->simtype,
                // 'Plan' => $number,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
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
            // $details = "";
            $subject = "";

            \Mail::to($to)
                ->cc(['salmanahmed334@gmail.com'])
                ->queue(new \App\Models\Mail\ActivationMail($details, $subject));
            notify()->success('New Activation has been created succesfully');
            return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
            // notify()->success('New Activation has been created succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('activation.index'));
        }
        // return redirect()->back()->withInput();
        // return redirect(route('activation.index'));
        // SSS END
    }
    public function ActiveNewNonVerified(Request $request)
    {
        // return $request;
        if ($request->simtype == 'New') {
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date.*' => 'required|date|before:tomorrow',
                'activation_sr_no.*' => 'required|numeric',
                'activation_service_order.*' => 'required|numeric',
                // 'activation_selected_no.*' => 'required|numeric',
                // 'activation_selected_no.*' => 'required|numeric|unique:activation_forms,activation_selected_no',
                'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                'activation_sold' => 'required',
                'activation_sold_by' => 'required',
                'saler_id' => 'required',
                'cname' => 'required|string|max:100',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                'additional_document_activation' => 'required',
                'documents.*' => 'required',
                'sr_photo.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()->all()]);
            }
            // return "zz";
            foreach ($request->plan_new as $key => $value) {
                // return $value;
                // return $key;
                // echo $key . '<br>';
                // $date = str_replace('/',
                // '-',
                // return $request['sr_photo'][1];
                if ($file = $request->file('sr_photo')) {
                    // $ext = date('d-m-Y-H-i');
                    // AzureCodeStart
                    // $image2 = file_get_contents($file[$key]);
                    // $originalFileName = time() . $file[$key]->getClientOriginalName();
                    // $multi_filePath = 'documents' . '/' . $originalFileName;
                    // \Storage::disk('azure')->put($multi_filePath, $image2);

                    // // AzureCodeEnd
                    // // LocalStorageStart
                    // $mytime = Carbon::now();
                    // $ext =  $mytime->toDateTimeString();
                    // $name = $ext . '-' . $file->getClientOriginalName();
                    // $file->move('documents', $name);
                    // $input['path'] = $name;
                    // LocalStorageEnd
                    $image2 = file_get_contents($file[$key]);
                    $originalFileName = time() . $file[$key]->getClientOriginalName();
                    $multi_filePath = 'documents' . '/' . $originalFileName;
                    \Storage::disk('azure')->put($multi_filePath, $image2);
                    // AzureCodeEnd
                    // LocalStorageStart
                    // $mytime = Carbon::now();
                    // $ext =  $mytime->toDateTimeString();
                    // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                    $name = $originalFileName;

                    // $file[$key]->move('documents', $name);
                    // $input['path'] = $name;
                    // LocalStorageEnd
                } else {
                }
                // return $request['activation_date'][1];
                // echo $request->activation_date[$key] . '<br>';
                // );
                // $request->activation_date[1];
                // $activation_date = date('Y-m-d', strtotime($date));
                if (empty(Session::get('front_image'))) {
                    $front_id = 'default.png';
                    // return response()->json(['error' => 'Please Choose Documents']);
                    // return response()->json(['error' => ['Documents' => ['Front Document is Missing, Please Reupload.']]], 200);
                }
                if (empty(Session::get('back_image'))) {
                    $back_id = 'default.png';
                    // return response()->json(['error' => 'Please Choose Documents']);
                    // return response()->json(['error' => ['Documents' => ['Back Document is Missing, Please Reupload.']]], 200);
                }
                $k = activation_form::create([
                    'cust_id' => $request->lead_id,
                    'lead_no' => $request->lead_no,
                    'lead_id' => $request->lead_id,
                    'verification_id' => $request->verification_id,
                    'customer_name' => $request->cname,
                    'customer_number' => $request->cnumber,
                    'age' => $request->age,
                    'gender' => $request->gender,
                    'nationality' => $request->nation,
                    'language' => $request->language,
                    'original_emirate_id' => $request->emirate_id,
                    // 'emirate_number' => $request->customer_name,
                    'additional_documents' => $request->additional_document,
                    'sim_type' => $request->simtype,
                    'number_commitment' => $request->numcommit,
                    // 'contract_commitment' => $request->customer_name,
                    'select_plan' => $value,
                    'benefits' => $request->customer_name,
                    // 'benefits' => $request->customer_name,
                    // 'total' => $request->customer_name,
                    'emirate_location' => $request->emirates,
                    'verify_agent' => $request->activation_sold_by,
                    // 'remarks' => $request->customer_name,
                    // 'pay_status' => $request->customer_name,
                    // 'pay_charges' => $request->customer_name,
                    'activation_date' => $request['activation_date'][$key],
                    'activation_sr_no' => $request['activation_sr_no'][$key],
                    'activation_service_order' => $request['activation_service_order'][$key],
                    'pay_charges' => $request['activation_rate_new'][$key],
                    'pay_status' => $request['activation_charges_new'][$key],
                    'activation_selected_no' => $request['activation_selected_no'][$key],
                    'activation_sim_serial_no' => $request->activation_sim_serial_no,
                    'activation_emirate_expiry' => Carbon::now(),
                    'activation_sold_by' => auth()->user()->id,

                    // 'emirate_id_front' => Session::get('front_image'),
                    // 'emirate_id_back' => Session::get('back_image'),
                    'emirate_id_front' => $front_id,
                    'emirate_id_back' => $back_id,
                    'activation_screenshot' => $name,

                    'saler_id' => $request->saler_id,
                    'channel_type' => $request->channel_type,
                    // 'later' => $request->customer_name,
                    // 'recording' => $request->customer_name,
                    // 'assing_to' => $request->customer_name,
                    // 'backoffice_by' => $request->customer_name,
                    // 'cordination_by' => $request->customer_name,
                    'date_time' => Carbon::now()->toDateTimeString(),
                    'status' => '1.02',
                    // 'selected_number' => $request->customer_name,
                    // 'flexible_minutes' => $request->customer_name,
                    // 'data' => $request->customer_name,
                ]);
                $ntc = lead_sale::select('call_centers.notify_email', 'call_centers.numbers')
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

                $link = route('view.lead', $request->lead_id);

                $details = [
                    'lead_id' => $request->lead_id,
                    'lead_no' => $request->lead_no,
                    'customer_name' =>  $request->cname,
                    'customer_number' => $request->cnumber,
                    'selected_number' => $request->cnumber,
                    'sim_type' => $request->simtype,
                    'numbers' => $ntc->numbers,
                    'link' => $link,
                    // 'Plan' => $number,
                    // 'AlternativeNumber' => $alternativeNumber,
                ];
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
                    ->cc(['salmanahmed334@gmail.com'])
                    // ->queue(new App\Models\Mail\ActivationMail($details));
                    ->queue(new \App\Models\Mail\ActivationMail($details, $subject));
                            \App\Models\Http\Controllers\WhatsAppController::ActivationWhatsApp($details);
            }

            // return "zom";

            if (!empty($request->selnumber)) {
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
                        $k->status = 'Active';
                        $k->save();
                        // CHANGE LATER
                        $cn = choosen_number::select('choosen_numbers.id')
                            ->where('number_id', $dn->id)
                            ->first();
                        if ($cn) {
                            $cnn = choosen_number::findorfail($cn->id);
                            $cnn->status = '2';
                            $cnn->save();
                        }
                        // CHANGE LATER
                    }
                    // return $d->id;
                    // return "number has been reserved";
                }
            }
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];



            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }

            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            $lead_data =  $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.11',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.11',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            //
            //


            // if(auth()->user()->role != 'sale')
            // \App\Models\Http\Controllers\WhatsAppController::ActivationWhatsApp($details);

            //
            notify()->success('New Activation has been created succesfully');
            return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
        } else if ($request->simtype == 'HomeWifi') {

            // return $request;
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required|date|before:tomorrow',
                'activation_sr_no' => 'required',
                'activation_selected_no' => 'required',
                'sr_photo' => 'required',
                'cname' => 'required|string|max:100',
                // 'activation_service_order' => 'required',
                // 'activation_selected_no' => 'required',
                // 'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                // 'activation_sold' => 'required',
                // 'activation_sold_by' => 'required',
                // 'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                // 'additional_document_activation' => 'required',
                // 'additional_document_activation.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                // return redirect()->back()
                //     ->withErrors($validatedData)
                //     ->withInput();
                return response()->json(['error' => $validatedData->errors()->all()]);
            }
            // if ($file = $request->file('emirate_id_front')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $name = Str::slug($name, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file->move('documents', $name);
            //     $input['path'] = $name;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file1 = $request->file('emirate_id_back')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     $name1 = Str::slug($name1, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file1->move('documents', $name1);
            //     $input['path'] = $name1;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file2 = $request->file('activation_screenshot')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name2 = $ext . '-' . $file->getClientOriginalName();
            //     $name2 = Str::slug($name2, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file2->move('documents', $name2);
            //     $input['path'] = $name2;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            $activation_date = $request->activation_date;
            // $activation_date = date(
            //     'Y-m-d',
            //     strtotime($date)
            // );
            if ($file = $request->file('sr_photo')) {
                // AzureCodeStart
                // $mytime = Carbon::now();
                // $ext =  $mytime->toDateTimeString();
                // $image2 = $ext . '-' . $file[$key]->getClientOriginalName();
                $image2 = file_get_contents($file);
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'documents' . '/' . $originalFileName;
                \Storage::disk('azure')->put(
                    $multi_filePath,
                    $image2
                );
                // AzureCodeEnd
                // LocalStorageStart
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                $name = $originalFileName;

                $file->move('documents', $name);
                $input['path'] = $name;
                // LocalStorageEnd
            }
            // if ($file = $request->file('sr_photo')) {
            //     // AzureCodeStart
            //     $image2 = file_get_contents($request->file('front_img'));
            //     $originalFileName = time() . $file->getClientOriginalName();
            //     $multi_filePath = 'documents' . '/' . $originalFileName;
            //     \Storage::disk('azure')->put($multi_filePath, $image2);
            //         // AzureCodeEnd
            //     // LocalStorageStart
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $file->move('documents', $name);
            //     $input['path'] = $name;
            //     // LocalStorageEnd
            // }
            else {
                $name = '';
            }
            // return $name;
            // if (empty(Session::get('front_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Front Document is Missing, Please Reupload.']]], 200);
            // }
            // if (empty(Session::get('back_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Back Document is Missing, Please Reupload.']]], 200);
            // }
            if (empty($request->front_id)) {
                $front_id = 'default.png';
                // return response()->json(['error' => 'Please Choose Documents']);
                // return response()->json(['error' => ['Documents' => ['Front Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            if (empty($request->back_id)) {
                // if (empty(Session::get('back_image'))|| empty($request->back_id)) {
                // return response()->json(['error' => 'Please Choose Documents']);
                $back_id = 'default.png';
                // return response()->json(['error' => ['Documents' => ['Back Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->cnumber,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_mnp,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => $request->activation_date,
                'activation_sr_no' => $request->activation_sr_no,
                'activation_service_order' => $request->activation_sr_no,
                'pay_charges' => '0',
                'pay_status' => 'Free',
                'activation_selected_no' => $request->activation_selected_no,
                'activation_sim_serial_no' => $request->activation_sr_no,
                'activation_emirate_expiry' => Carbon::now(),
                'activation_sold_by' => auth()->user()->id,
                // 'emirate_id_front' => Session::get('front_image'),
                // 'emirate_id_back' => Session::get('back_image'),
                'emirate_id_front' => $front_id,
                'emirate_id_back' => $back_id,
                'activation_screenshot' => $name,
                'saler_id' => $request->saler_id,
                'channel_type' => $request->channel_type,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '1.02',
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);


            // $k = activation_form::create([
            //     'cust_id' => $request->lead_id,
            //     'lead_no' => $request->lead_no,
            //     'lead_id' => $request->lead_id,
            //     'verification_id' => $request->verification_id,
            //     'customer_name' => $request->cname,
            //     'customer_number' => $request->cnumber,
            //     'age' => $request->age,
            //     'gender' => $request->gender,
            //     'nationality' => $request->nation,
            //     'language' => $request->language,
            //     'original_emirate_id' => $request->emirate_id,
            //     // 'emirate_number' => $request->customer_name,
            //     'additional_documents' => $request->additional_document_activation,
            //     'sim_type' => $request->simtype,
            //     'number_commitment' => $request->numcommit,
            //     // 'contract_commitment' => $request->customer_name,
            //     'select_plan' => $request->plan_elife,
            //     'benefits' => $request->customer_name,
            //     // 'benefits' => $request->customer_name,
            //     // 'total' => $request->customer_name,
            //     'emirate_location' => $request->emirates,
            //     'verify_agent' => $request->activation_sold_by,
            //     // 'remarks' => $request->customer_name,
            //     // 'pay_status' => $request->customer_name,
            //     // 'pay_charges' => $request->customer_name,
            //     'activation_date' => $activation_date,
            //     'activation_sr_no' => $request->activation_sr_no,
            //     'activation_service_order' =>'MNP',
            //     'activation_selected_no' => 'MNP',
            //     'activation_sim_serial_no' => 'MNP',
            //     'activation_emirate_expiry' => Carbon::now(),
            //     'activation_sold_by' => auth()->user()->id,
            //     'date_time' => Carbon::now()->toDateTimeString(),
            //     'status' => '1.02',
            //     'emirate_id_front' => Session::get('front_image'),
            //     'emirate_id_back' => Session::get('back_image'),
            //     'activation_screenshot' => $name,
            //     'saler_id' => $request->saler_id,

            // ]);
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];

            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }


            // if (!empty($request->additional_document_activation)) {
            //     foreach ($request->additional_document_activation as $key => $val) {
            //         // return $request->audio;
            //         // return $request->file();
            //         if ($file = $request->file('additional_document_activation')) {
            //             // return "a";
            //             // }
            //             $ext = date('d-m-Y-H-i');
            //             // return $file;
            //             $mytime = Carbon::now();
            //             $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            // $name = $originalFileName;

            //             $name = Str::slug($name, '-');

            //             // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //             $file[$key]->move('documents', $name);
            //             $input['path'] = $name;
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
            //         $data = activation_document::create([
            //             // 'resource_name' => $request->resource_name,
            //             'username' => 'activation',
            //             'document_name' => $name,
            //             'lead_id' => $request->lead_id,
            //             'activation_id' => $activation_id,
            //             'status' => '1.02',
            //             // 'batch_id' => $batch_id,
            //         ]);
            //         $d = lead_sale::findOrFail($request->lead_id);
            //         $d->update([
            //             'status' => '1.02',
            //         ]);
            //         $d = verification_form::findOrFail($request->ver_id);
            //         $d->update([
            //             'status' => '1.02',
            //             'assing_to' => $request->assing_to,
            //             'cordination_by' => auth()->user()->id,
            //         ]);
            //     }
            // }
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.11',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.11',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');

            $ntc = lead_sale::select('call_centers.notify_email', 'call_centers.numbers')
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
            $link = route('view.lead', $request->lead_id);
            //
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'customer_name' =>  $request->cname,
                'customer_number' => $request->cnumber,
                'selected_number' => $request->cnumber,
                'sim_type' => $request->simtype,
                'numbers' => $ntc->numbers,
                'link' => $link,
                // 'Plan' => $number,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
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
            // $details = "";
            $subject = "";

            \Mail::to($to)
                ->cc(['salmanahmed334@gmail.com'])
                ->queue(new \App\Models\Mail\ActivationMail($details, $subject));
            //
            // $token = env('FACEBOOK_TOKEN');
            \App\Models\Http\Controllers\WhatsAppController::ActivationWhatsApp($details);
            //
            notify()->success('New Activation has been created succesfully');
            return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
        } else {
            // return $request;
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required|date|before:tomorrow',
                'activation_sr_no' => 'required',
                'sr_photo' => 'required',
                'cname' => 'required|string|max:80',
                // 'activation_service_order' => 'required',
                // 'activation_selected_no' => 'required',
                // 'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                // 'activation_sold' => 'required',
                // 'activation_sold_by' => 'required',
                // 'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                // 'additional_document_activation' => 'required',
                // 'additional_document_activation.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                // return redirect()->back()
                //     ->withErrors($validatedData)
                //     ->withInput();
                return response()->json(['error' => $validatedData->errors()->all()]);
            }
            // if ($file = $request->file('emirate_id_front')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $name = Str::slug($name, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file->move('documents', $name);
            //     $input['path'] = $name;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file1 = $request->file('emirate_id_back')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     $name1 = Str::slug($name1, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file1->move('documents', $name1);
            //     $input['path'] = $name1;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file2 = $request->file('activation_screenshot')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name2 = $ext . '-' . $file->getClientOriginalName();
            //     $name2 = Str::slug($name2, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file2->move('documents', $name2);
            //     $input['path'] = $name2;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            $activation_date = $request->activation_date;
            // $activation_date = date(
            //     'Y-m-d',
            //     strtotime($date)
            // );
            if ($file = $request->file('sr_photo')) {
                // AzureCodeStart
                // $mytime = Carbon::now();
                // $ext =  $mytime->toDateTimeString();
                // $image2 = $ext . '-' . $file[$key]->getClientOriginalName();
                $image2 = file_get_contents($file);
                $originalFileName = time() . $file->getClientOriginalName();
                $multi_filePath = 'documents' . '/' . $originalFileName;
                \Storage::disk('azure')->put(
                    $multi_filePath,
                    $image2
                );
                // AzureCodeEnd
                // LocalStorageStart
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                $name = $originalFileName;

                $file->move('documents', $name);
                $input['path'] = $name;
                // LocalStorageEnd
            }
            // if ($file = $request->file('sr_photo')) {
            //     // AzureCodeStart
            //     $image2 = file_get_contents($request->file('front_img'));
            //     $originalFileName = time() . $file->getClientOriginalName();
            //     $multi_filePath = 'documents' . '/' . $originalFileName;
            //     \Storage::disk('azure')->put($multi_filePath, $image2);
            //         // AzureCodeEnd
            //     // LocalStorageStart
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $file->move('documents', $name);
            //     $input['path'] = $name;
            //     // LocalStorageEnd
            // }
            else {
                $name = '';
            }
            //
            // if (empty(Session::get('front_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Front Document is Missing, Please Reupload.']]], 200);
            // }
            // if (empty(Session::get('back_image'))) {
            //     // return response()->json(['error' => 'Please Choose Documents']);
            //     return response()->json(['error' => ['Documents' => ['Back Document is Missing, Please Reupload.']]], 200);
            // }
            if (empty($request->front_id)) {
                $front_id = 'default.png';

                // return response()->json(['error' => 'Please Choose Documents']);
                // return response()->json(['error' => ['Documents' => ['Front Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            if (empty($request->back_id)) {
                // if (empty(Session::get('back_image'))|| empty($request->back_id)) {
                $back_id = 'default.png';

                // return response()->json(['error' => 'Please Choose Documents']);
                // return response()->json(['error' => ['Documents' => ['Back Document is Invalid or Missing, Please Reupload.']]], 200);
            }
            // return $name;
            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->cnumber,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_mnp,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => $request->activation_date,
                'activation_sr_no' => $request->activation_sr_no,
                'activation_service_order' => $request->activation_sr_no,
                'pay_charges' => '0',
                'pay_status' => 'Free',
                'activation_selected_no' => $request->cnumber,
                'activation_sim_serial_no' => $request->activation_sr_no,
                'activation_emirate_expiry' => Carbon::now(),
                'activation_sold_by' => auth()->user()->id,
                // 'emirate_id_front' => Session::get('front_image'),
                // 'emirate_id_back' => Session::get('back_image'),
                'emirate_id_front' => $front_id,
                'emirate_id_back' => $back_id,
                'activation_screenshot' => $name,
                'saler_id' => $request->saler_id,
                'channel_type' => $request->channel_type,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '1.08', // <= MNP IN PROCESS
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);


            // $k = activation_form::create([
            //     'cust_id' => $request->lead_id,
            //     'lead_no' => $request->lead_no,
            //     'lead_id' => $request->lead_id,
            //     'verification_id' => $request->verification_id,
            //     'customer_name' => $request->cname,
            //     'customer_number' => $request->cnumber,
            //     'age' => $request->age,
            //     'gender' => $request->gender,
            //     'nationality' => $request->nation,
            //     'language' => $request->language,
            //     'original_emirate_id' => $request->emirate_id,
            //     // 'emirate_number' => $request->customer_name,
            //     'additional_documents' => $request->additional_document_activation,
            //     'sim_type' => $request->simtype,
            //     'number_commitment' => $request->numcommit,
            //     // 'contract_commitment' => $request->customer_name,
            //     'select_plan' => $request->plan_elife,
            //     'benefits' => $request->customer_name,
            //     // 'benefits' => $request->customer_name,
            //     // 'total' => $request->customer_name,
            //     'emirate_location' => $request->emirates,
            //     'verify_agent' => $request->activation_sold_by,
            //     // 'remarks' => $request->customer_name,
            //     // 'pay_status' => $request->customer_name,
            //     // 'pay_charges' => $request->customer_name,
            //     'activation_date' => $activation_date,
            //     'activation_sr_no' => $request->activation_sr_no,
            //     'activation_service_order' =>'MNP',
            //     'activation_selected_no' => 'MNP',
            //     'activation_sim_serial_no' => 'MNP',
            //     'activation_emirate_expiry' => Carbon::now(),
            //     'activation_sold_by' => auth()->user()->id,
            //     'date_time' => Carbon::now()->toDateTimeString(),
            //     'status' => '1.02',
            //     'emirate_id_front' => Session::get('front_image'),
            //     'emirate_id_back' => Session::get('back_image'),
            //     'activation_screenshot' => $name,
            //     'saler_id' => $request->saler_id,

            // ]);
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];

            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        // $ext = date('d-m-Y-H-i');
                        // // return $file;
                        // $mytime = Carbon::now();
                        // $ext =  $mytime->toDateTimeString();
                        // // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        // $name = $originalFileName;

                        // $name = Str::slug($name, '-');

                        // // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        // $file[$key]->move('documents', $name);
                        // $input['path'] = $name;
                        $image2 = file_get_contents($file[$key]);
                        $originalFileName = time() . $file[$key]->getClientOriginalName();
                        $multi_filePath = 'documents' . '/' . $originalFileName;
                        \Storage::disk('azure')->put(
                            $multi_filePath,
                            $image2
                        );
                        // AzureCodeEnd
                        // LocalStorageStart
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = $originalFileName;

                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }


            // if (!empty($request->additional_document_activation)) {
            //     foreach ($request->additional_document_activation as $key => $val) {
            //         // return $request->audio;
            //         // return $request->file();
            //         if ($file = $request->file('additional_document_activation')) {
            //             // return "a";
            //             // }
            //             $ext = date('d-m-Y-H-i');
            //             // return $file;
            //             $mytime = Carbon::now();
            //             $ext =  $mytime->toDateTimeString();
            // $name = $ext . '-' . $file[$key]->getClientOriginalName();
            // $name = $originalFileName;

            //             $name = Str::slug($name, '-');

            //             // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //             $file[$key]->move('documents', $name);
            //             $input['path'] = $name;
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
            //         $data = activation_document::create([
            //             // 'resource_name' => $request->resource_name,
            //             'username' => 'activation',
            //             'document_name' => $name,
            //             'lead_id' => $request->lead_id,
            //             'activation_id' => $activation_id,
            //             'status' => '1.02',
            //             // 'batch_id' => $batch_id,
            //         ]);
            //         $d = lead_sale::findOrFail($request->lead_id);
            //         $d->update([
            //             'status' => '1.02',
            //         ]);
            //         $d = verification_form::findOrFail($request->ver_id);
            //         $d->update([
            //             'status' => '1.02',
            //             'assing_to' => $request->assing_to,
            //             'cordination_by' => auth()->user()->id,
            //         ]);
            //     }
            // }
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.11',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.11',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            // $details = [
            //     'lead_id' => $request->lead_id,
            //     'lead_no' => $request->lead_no,
            //     'customer_name' =>  $request->cname,
            //     'customer_number' => $request->cnumber,
            //     'selected_number' => $request->cnumber,
            //     'sim_type' => $request->simtype,
            //     // 'Plan' => $number,
            //     // 'AlternativeNumber' => $alternativeNumber,
            // ];
            $ntc = lead_sale::select('call_centers.notify_email', 'call_centers.numbers')
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
            //
            $link = route('view.lead', $request->lead_id);

            //
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'customer_name' =>  $request->cname,
                'customer_number' => $request->cnumber,
                'selected_number' => $request->cnumber,
                'sim_type' => $request->simtype,
                'numbers' => $ntc->numbers,
                'link' => $link,
                // 'Plan' => $number,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
            // $details = "";
            $subject = "";

            \Mail::to($to)
                ->cc(['salmanahmed334@gmail.com'])
                ->queue(new \App\Models\Mail\ActivationMail($details, $subject));
            \App\Models\Http\Controllers\WhatsAppController::ActivationWhatsApp($details);
            notify()->success('New Activation has been created succesfully');
            return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);
            // notify()->success('New Activation has been created succesfully');
            // return redirect()->back()->withInput();
            // return redirect(route('activation.index'));
        }
        // return redirect()->back()->withInput();
        // return redirect(route('activation.index'));
        // SSS END
    }
    public function ActiveElife(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            // 'plan_name' => 'required | string | unique',
            // 'plan_name' => 'required|string|unique:plans,plan_name',
            // 'activation_date' => 'required',
            'activation_sr_no' => 'required|numeric|min:9',
            'imei' => 'required',
            // 'activation_service_order' => 'required|numeric',
            // 'activation_selected_no' => 'required|numeric',
            // 'activation_sim_serial_no' => 'required',
            'activation_emirate_expiry' => 'required',
            'activation_sold' => 'required',
            'activation_sold_by' => 'required',
            'saler_id' => 'required',
            // 'emirate_id_front' => 'required',
            // 'emirate_id_back' => 'required',
            // 'activation_screenshot' => 'required',
            // 'additional_document_activation' => 'required',
            // 'documents.*' => 'required',
        ]);
        if ($validatedData->fails()) {
            // return redirect()->back()
            //     ->withErrors($validatedData)
            //     ->withInput();
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        // if ($file = $request->file('emirate_id_front')) {
        //     // $ext = date('d-m-Y-H-i');
        //     $mytime = Carbon::now();
        //     $ext =  $mytime->toDateTimeString();
        //     $name = $ext . '-' . $file->getClientOriginalName();
        //     $name = Str::slug($name, '-');

        //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
        //     // $name1 = Str::slug($name, '-');

        //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
        //     // $name2 = Str::slug($name, '-');

        //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        //     $file->move('documents', $name);
        //     $input['path'] = $name;

        //     // $file1->move('documents', $name1);
        //     // $input['path'] = $name1;

        //     // $file2->move('documents', $name2);
        //     // $input['path'] = $name2;
        // }
        // if ($file1 = $request->file('emirate_id_back')) {
        //     // $ext = date('d-m-Y-H-i');
        //     $mytime = Carbon::now();
        //     $ext =  $mytime->toDateTimeString();
        //     $name1 = $ext . '-' . $file1->getClientOriginalName();
        //     $name1 = Str::slug($name1, '-');

        //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
        //     // $name1 = Str::slug($name, '-');

        //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
        //     // $name2 = Str::slug($name, '-');

        //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
        //     $file1->move('documents', $name1);
        //     $input['path'] = $name1;

        //     // $file1->move('documents', $name1);
        //     // $input['path'] = $name1;

        //     // $file2->move('documents', $name2);
        //     // $input['path'] = $name2;
        // }
        if ($file2 = $request->file('activation_screenshot')) {
            // AzureCodeStart
            $image2 = file_get_contents($request->file('activation_screenshot'));
            $originalFileName = time() . $file2->getClientOriginalName();
            $multi_filePath = 'documents' . '/' . $originalFileName;
            \Storage::disk('azure')->put($multi_filePath, $image2);
            // AzureCodeEnd
            // $ext = date('d-m-Y-H-i');
            $mytime = Carbon::now();
            $ext =  $mytime->toDateTimeString();
            $name2 = $ext . '-' . $file2->getClientOriginalName();
            $file2->move('documents', $name2);
            $input['path'] = $name2;
            // LocalStorageEnd

            // $file1->move('documents', $name1);
            // $input['path'] = $name1;

            // $file2->move('documents', $name2);
            // $input['path'] = $name2;
        }
        if (empty(Session::get('front_image'))) {
            // return response()->json(['error' => 'Please Choose Documents']);
            return response()->json(['error' => ['Documents' => ['Front Document is Missing, Please Reupload.']]], 200);
        }
        if (empty(Session::get('back_image'))) {
            // return response()->json(['error' => 'Please Choose Documents']);
            return response()->json(['error' => ['Documents' => ['Back Document is Missing, Please Reupload.']]], 200);
        }
        $k = activation_form::create([
            'cust_id' => $request->lead_id,
            'lead_no' => $request->lead_no,
            'lead_id' => $request->lead_id,
            'verification_id' => $request->verification_id,
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'age' => $request->age,
            'gender' => $request->gender,
            'nationality' => $request->nation,
            'language' => $request->language,
            'original_emirate_id' => $request->emirate_id,
            // 'emirate_number' => $request->customer_name,
            'additional_documents' => $request->additional_document,
            'sim_type' => $request->simtype,
            'number_commitment' => $request->numcommit,
            // 'contract_commitment' => $request->customer_name,
            'select_plan' => $request->plan_elife,
            'benefits' => $request->customer_name,
            // 'benefits' => $request->customer_name,
            // 'total' => $request->customer_name,
            'emirate_location' => $request->emirates,
            'verify_agent' => $request->activation_sold_by,
            // 'remarks' => $request->customer_name,
            // 'pay_status' => $request->customer_name,
            // 'pay_charges' => $request->customer_name,
            'activation_date' => Carbon::now()->toDateTimeString(),
            'activation_sr_no' => $request->activation_sr_no,
            // 'activation_service_order' => $request->activation_service_order,
            // 'activation_selected_no' => $request->activation_selected_no,
            // 'activation_sim_serial_no' => $request->activation_sim_serial_no,
            // 'activation_emirate_expiry' => Carbon::now(),
            'activation_sold_by' => auth()->user()->id,
            'imei' => $request->imei,
            'emirate_id_front' => Session::get('front_image'),
            'emirate_id_back' => Session::get('back_image'),
            'activation_screenshot' => $name2,

            'saler_id' => $request->saler_id,
            // 'later' => $request->customer_name,
            // 'recording' => $request->customer_name,
            // 'assing_to' => $request->customer_name,
            // 'backoffice_by' => $request->customer_name,
            // 'cordination_by' => $request->customer_name,
            'date_time' => Carbon::now()->toDateTimeString(),
            'status' => '0',
            // 'selected_number' => $request->customer_name,
            // 'flexible_minutes' => $request->customer_name,
            // 'data' => $request->customer_name,
        ]);
        $activation_id = $k->id;
        $teacher_id = $request->documents;
        $book_records = [];



        if (!empty($request->documents)) {
            foreach ($request->documents as $key => $val) {
                // return $request->audio;
                // return $request->file();
                if ($file = $request->file('documents')) {
                    // return "a";
                    // }
                    $ext = date('d-m-Y-H-i');
                    // return $file;
                    $mytime = Carbon::now();
                    $ext =  $mytime->toDateTimeString();
                    // $name = $ext . '-' . $file[$key]->getClientOriginalName();
                    $name = $originalFileName;

                    $name = Str::slug($name, '-');

                    // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                    $file[$key]->move('documents', $name);
                    $input['path'] = $name;
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
                $data = activation_document::create([
                    // 'resource_name' => $request->resource_name,
                    'username' => auth()->user()->name,
                    'document_name' => $name,
                    'lead_id' => $request->lead_id,
                    'activation_id' => $activation_id,
                    'status' => '1',
                    // 'batch_id' => $batch_id,
                ]);
            }
        }
        $d = lead_sale::findOrFail($request->lead_id);
        $d->update([
            'status' => '1.02',
        ]);
        $d = verification_form::findOrFail($request->ver_id);
        $d->update([
            'status' => '1.02',
            'assing_to' => $request->assing_to,
            'cordination_by' => auth()->user()->id,
        ]);

        // Insert book records
        activation_document::insert($book_records);
        Session::forget('front_image');
        Session::forget('back_image');
        Session::forget('sr_no');
        notify()->success('New Sale has been created succesfully');
        return response()->json(['success' => 'Added new records, please wait meanwhile we are redirecting you....!!!']);

        // return redirect()->back()->withInput();
        // return redirect(route('activation.index'));
    }
    public function ActiveElifePlan(Request $request)
    {
        // return $request;
        $k = activation_form::select('activation_forms.*')
            ->where('activation_forms.lead_id', $request->lead_id)->first();
        $k = activation_form::findorfail($k->id);
        $k->status = '1.18';
        $k->date_time = $request->later_date;
        $k->save();
        $k = verification_form::select('verification_forms.*')
            ->where('verification_forms.lead_no', $request->lead_id)->first();
        $k = verification_form::findorfail($k->id);
        $k->status = '1.18';
        $k->save();
        $k = lead_sale::findorfail($request->lead_id);
        $k->status = '1.18';
        $k->save();
    }
    public function RejectElifePlan(Request $request)
    {
        // return $request;
        $k = activation_form::select('activation_forms.*')
            ->where('activation_forms.lead_id', $request->lead_id)->first();
        $k = activation_form::findorfail($k->id);
        $k->status = '1.15';
        $k->remarks = $request->remarks;
        // $k->date_time = $request->later_date;
        $k->save();
        $k = verification_form::select('verification_forms.*')
            ->where('verification_forms.lead_no', $request->lead_id)->first();
        $k = verification_form::findorfail($k->id);
        $k->status = '1.15';
        $k->remarks = $request->remarks;
        $k->save();
        $k = lead_sale::findorfail($request->lead_id);
        $k->status = '1.15';
        $k->remarks = $request->remarks;
        $k->save();
    }
    public function daily_collection()
    {
        // return "S";
        $operation = User::role('activation')->get();
        return view('dashboard.daily-collection', compact('operation'));
    }
    public static function daily_collection_count($userid)
    {
        // return "S";
        return $k = activation_form::select('pay_charges')
            ->LeftJoin(
                'lead_locations',
                'lead_locations.lead_id',
                'activation_forms.lead_id'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'lead_locations.assign_to'
            )
            ->where('users.id', $userid)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->sum('activation_forms.pay_charges');
        // ->where('activation_forms.activation_date', $date)
        // ->where('')
        // SELECT a.pay_status FROM `activation_forms` a
        // INNER JOIN lead_locations l on l.lead_id = a.lead_id
        // WHERE a.pay_status = 'Paid'
        // $operation = User::role('activation')->get();
        // return view('dashboard.daily-collection',compact('operation'));
    }
    //
    public function AddActivation(activation_form $plan, $id)
    {
        return phpinfo();
        //
        // return $plan;

            $data = verification_form::select("lead_sales.id as lead_id", "verification_forms.id as ver_id", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "users.name as agent_name", "lead_sales.*", "lead_locations.*")
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
                ->where('verification_forms.id', $id)
                ->first();

            $remarks =  remark::select("remarks.*")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                ->where("remarks.lead_id", $data->lead_id)
                ->get();

            $audios = \App\Models\audio_recording::wherelead_no($data->lead_id)->get();

            $countries = \App\Models\country_phone_code::all();
            // $operation = verification_form::wherestatus('1.10')->get();
            $emirates = \App\Models\emirate::all();
            $plans = \App\Models\plan::wherestatus('1')->get();
            $elifes = \App\Models\elife_plan::wherestatus('1')->get();
            $addons = \App\Models\addon::wherestatus('1')->get();
            // $device = \App\Models\imei_list::wherestatus('1')->get();
            $users = \App\Models\User::role('activation')->get();

            // $operation = verification_form::whereid($id)->get();
            return view('coordination.add-activation', compact('data', 'remarks', 'countries', 'emirates', 'plans', 'elifes', 'addons', 'users','audios'));
        // return view('dashboar', compact('operation'));

        // return $activation_form;
    }
    //
    public function ActivationStore(Request $request)
    {
        //
        return $request;
        if ($request->reverify_remarks != '') {
            // return $request;
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.13',
                'remarks' => $request->reverify_remarks,
                // 'date_time_follow' => $request->call_back_at_new,
            ]);
            $dd = verification_form::findOrFail($request->ver_id);
            $dd->update([
                'status' => '1.13',
                // 'assing_to' => $request->assing_to,
                // 'cordination_by' => auth()->user()->id,
            ]);
            remark::create([
                'remarks' => $request->reverify_remarks,
                'lead_status' => '1.13',
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'user_agent_id' => auth()->user()->id,
            ]);
            // return
            // notify()->success('Lead has been ReVerification now');

            // return redirect()->back()->withInput();
            return response()->json(['success' => 'Succesfully send to Reverified, Please wait']);
            // return redirect(route('verification.final-cord-lead'));
        } else if ($request->remarks_for_cordination != '') {
            // return "ok";
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
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.19',
                'remarks' => $request->remarks_for_cordination,
                // 'date_time_follow' => $request->call_back_at_new,
            ]);
            $dd = verification_form::findOrFail($request->ver_id);
            $dd->update([
                'status' => '1.19',
                // 'assing_to' => $request->assing_to,
                // 'cordination_by' => auth()->user()->id,
            ]);
            // return $request->lead_id;
            // $lead_location = \App\lead_location::where('lead_id',$request->lead_id)->first();
            // $lead_loc = \App\lead_location::findorfail($lead_location->id);
            // $lead_location->delete();
            remark::create([
                'remarks' => $request->remarks_for_cordination,
                'lead_status' => '1.19',
                'lead_id' => $request->lead_id,
                'lead_no' => $request->lead_id, 'date_time' => $current_date_time = Carbon::now()->toDateTimeString(), // Produces something like "2019-03-11 12:25:00"
                'user_agent' => auth()->user()->name,
                'user_agent_id' => auth()->user()->id,
            ]);
            //
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
            // return
            $details = [
                'lead_id' => $request->lead_id,
                'lead_no' => $d->lead_no,
                'customer_name' =>  $d->customer_name,
                'customer_number' => $d->customer_number,
                'selected_number' => $d->selected_number,
                'sim_type' => $request->simtype,
                'saler_name' => $d->saler_name,
                'remarks' => $request->remarks_for_cordination,
                // 'AlternativeNumber' => $alternativeNumber,
            ];
            // $to = [
            //     [
            //         'email' =>
            //         'parhakooo@gmail.com',
            //         'name' => 'Manager',
            //     ]
            // ];
            // $details = "";
            $subject = "";

            \Mail::to($to)
            ->cc(['salmanahmed334@gmail.com'])
            ->queue(new \App\Mail\FollowUpMail($details, $subject));
            //
            return response()->json(['success' => 'Succesfully send to follow up, Please wait']);
            // notify()->success('Lead has been follow up now');

            // return redirect()->back()->withInput();
            // return redirect(route('verification.final-cord-lead'));
        } else if ($request->simtype == 'Elife') {
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required',
                'activation_sr_no' => 'required|numeric|min:9',
                'imei' => 'required',
                // 'activation_service_order' => 'required|numeric',
                // 'activation_selected_no' => 'required|numeric',
                // 'activation_sim_serial_no' => 'required',
                // 'activation_emirate_expiry' => 'required',
                'activation_sold' => 'required',
                'activation_sold_by' => 'required',
                'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                // 'additional_document_activation' => 'required',
                // 'documents.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            if ($file = $request->file('emirate_id_front')) {
                // $ext = date('d-m-Y-H-i');
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                $name = $ext . '-' . $file->getClientOriginalName();
                $name = Str::slug($name, '-');

                // $name1 = $ext . '-' . $file1->getClientOriginalName();
                // $name1 = Str::slug($name, '-');

                // $name2 = $ext . '-' . $file2->getClientOriginalName();
                // $name2 = Str::slug($name, '-');

                // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                $file->move('documents', $name);
                $input['path'] = $name;

                // $file1->move('documents', $name1);
                // $input['path'] = $name1;

                // $file2->move('documents', $name2);
                // $input['path'] = $name2;
            }
            if ($file1 = $request->file('emirate_id_back')) {
                // $ext = date('d-m-Y-H-i');
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                $name1 = $ext . '-' . $file1->getClientOriginalName();
                $name1 = Str::slug($name1, '-');

                // $name1 = $ext . '-' . $file1->getClientOriginalName();
                // $name1 = Str::slug($name, '-');

                // $name2 = $ext . '-' . $file2->getClientOriginalName();
                // $name2 = Str::slug($name, '-');

                // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                $file1->move('documents', $name1);
                $input['path'] = $name1;

                // $file1->move('documents', $name1);
                // $input['path'] = $name1;

                // $file2->move('documents', $name2);
                // $input['path'] = $name2;
            }
            if ($file2 = $request->file('activation_screenshot')) {
                // $ext = date('d-m-Y-H-i');
                $mytime = Carbon::now();
                $ext =  $mytime->toDateTimeString();
                $name2 = $ext . '-' . $file->getClientOriginalName();
                $name2 = Str::slug($name2, '-');

                // $name1 = $ext . '-' . $file1->getClientOriginalName();
                // $name1 = Str::slug($name, '-');

                // $name2 = $ext . '-' . $file2->getClientOriginalName();
                // $name2 = Str::slug($name, '-');

                // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                $file2->move('documents', $name2);
                $input['path'] = $name2;

                // $file1->move('documents', $name1);
                // $input['path'] = $name1;

                // $file2->move('documents', $name2);
                // $input['path'] = $name2;
            }
            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->numcommit,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_elife,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => Carbon::now()->toDateTimeString(),
                'activation_sr_no' => $request->activation_sr_no,
                // 'activation_service_order' => $request->activation_service_order,
                // 'activation_selected_no' => $request->activation_selected_no,
                // 'activation_sim_serial_no' => $request->activation_sim_serial_no,
                // 'activation_emirate_expiry' => $request->activation_emirate_expiry,
                'activation_sold_by' => auth()->user()->id,
                'imei' => $request->imei,
                'emirate_id_front' => $name,
                'emirate_id_back' => $name1,
                'activation_screenshot' => $name2,

                'saler_id' => $request->saler_id,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '0',
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];



            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '0',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.02',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.02',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);

            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            notify()->success('New Sale has been created succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('activation.index'));
        } else if ($request->simtype == 'New') {
            // return $request;
            foreach ($request->plan_new as $key => $value) {
                return $value;
            }
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required',
                'activation_sr_no' => 'required|numeric',
                'activation_service_order' => 'required|numeric',
                'activation_selected_no' => 'required|numeric',
                'activation_sim_serial_no' => 'required',
                'activation_emirate_expiry' => 'required',
                'activation_sold' => 'required',
                'activation_sold_by' => 'required',
                'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                'additional_document_activation' => 'required',
                'documents.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }

            $date = str_replace('/', '-', $request->activation_date);
            $activation_date = date('Y-m-d', strtotime($date));

            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->numcommit,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_elife,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => $activation_date,
                'activation_sr_no' => $request->activation_sr_no,
                'activation_service_order' => $request->activation_service_order,
                'activation_selected_no' => $request->activation_selected_no,
                'activation_sim_serial_no' => $request->activation_sim_serial_no,
                'activation_emirate_expiry' => $request->activation_emirate_expiry,
                'activation_sold_by' => auth()->user()->id,

                'emirate_id_front' => Session::get('front_image'),
                'emirate_id_back' => Session::get('front_image'),
                'activation_screenshot' => Session::get('sr_no'),

                'saler_id' => $request->saler_id,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '1.02',
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);
            if (!empty($request->selnumber)) {
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
                        $k->status = 'Active';
                        $k->save();
                        // CHANGE LATER
                        $cn = choosen_number::select('choosen_numbers.id')
                        ->where('number_id', $dn->id)
                        ->first();
                        if ($cn) {
                            $cnn = choosen_number::findorfail($cn->id);
                            $cnn->status = '2';
                            $cnn->save();
                        }
                        // CHANGE LATER
                    }
                    // return $d->id;
                    // return "number has been reserved";
                }
            }
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];



            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                }
            }

            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            $d = lead_sale::findOrFail($request->lead_id);
            $d->update([
                'status' => '1.02',
            ]);
            $d = verification_form::findOrFail($request->ver_id);
            $d->update([
                'status' => '1.02',
                'assing_to' => $request->assing_to,
                'cordination_by' => auth()->user()->id,
            ]);
            notify()->success('New Activation has been created succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('activation.index'));
        } else if ($request->simtype == 'MNP' || $request->simtype == 'Migration') {
            // return $request;
            $validatedData = Validator::make($request->all(), [
                // 'plan_name' => 'required | string | unique',
                // 'plan_name' => 'required|string|unique:plans,plan_name',
                'activation_date' => 'required',
                'activation_sr_no' => 'required|numeric',
                'activation_service_order' => 'required|numeric',
                'activation_selected_no' => 'required|numeric',
                'activation_sim_serial_no' => 'required',
                'activation_emirate_expiry' => 'required',
                'activation_sold' => 'required',
                'activation_sold_by' => 'required',
                'saler_id' => 'required',
                // 'emirate_id_front' => 'required',
                // 'emirate_id_back' => 'required',
                // 'activation_screenshot' => 'required',
                // 'additional_document_activation' => 'required',
                'documents.*' => 'required',
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            // if ($file = $request->file('emirate_id_front')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name = $ext . '-' . $file->getClientOriginalName();
            //     $name = Str::slug($name, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file->move('documents', $name);
            //     $input['path'] = $name;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file1 = $request->file('emirate_id_back')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     $name1 = Str::slug($name1, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file1->move('documents', $name1);
            //     $input['path'] = $name1;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            // if ($file2 = $request->file('activation_screenshot')) {
            //     // $ext = date('d-m-Y-H-i');
            //     $mytime = Carbon::now();
            //     $ext =  $mytime->toDateTimeString();
            //     $name2 = $ext . '-' . $file->getClientOriginalName();
            //     $name2 = Str::slug($name2, '-');

            //     // $name1 = $ext . '-' . $file1->getClientOriginalName();
            //     // $name1 = Str::slug($name, '-');

            //     // $name2 = $ext . '-' . $file2->getClientOriginalName();
            //     // $name2 = Str::slug($name, '-');

            //     // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
            //     $file2->move('documents', $name2);
            //     $input['path'] = $name2;

            //     // $file1->move('documents', $name1);
            //     // $input['path'] = $name1;

            //     // $file2->move('documents', $name2);
            //     // $input['path'] = $name2;
            // }
            $date = str_replace('/', '-', $request->activation_date);
            $activation_date = date(
                'Y-m-d',
                strtotime($date)
            );
            $k = activation_form::create([
                'cust_id' => $request->lead_id,
                'lead_no' => $request->lead_no,
                'lead_id' => $request->lead_id,
                'verification_id' => $request->verification_id,
                'customer_name' => $request->cname,
                'customer_number' => $request->cnumber,
                'age' => $request->age,
                'gender' => $request->gender,
                'nationality' => $request->nation,
                'language' => $request->language,
                'original_emirate_id' => $request->emirate_id,
                // 'emirate_number' => $request->customer_name,
                'additional_documents' => $request->additional_document,
                'sim_type' => $request->simtype,
                'number_commitment' => $request->numcommit,
                // 'contract_commitment' => $request->customer_name,
                'select_plan' => $request->plan_elife,
                'benefits' => $request->customer_name,
                // 'benefits' => $request->customer_name,
                // 'total' => $request->customer_name,
                'emirate_location' => $request->emirates,
                'verify_agent' => $request->activation_sold_by,
                // 'remarks' => $request->customer_name,
                // 'pay_status' => $request->customer_name,
                // 'pay_charges' => $request->customer_name,
                'activation_date' => $activation_date,
                'activation_sr_no' => $request->activation_sr_no,
                'activation_service_order' => $request->activation_service_order,
                'activation_selected_no' => $request->activation_selected_no,
                'activation_sim_serial_no' => $request->activation_sim_serial_no,
                'activation_emirate_expiry' => $request->activation_emirate_expiry,
                'activation_sold_by' => auth()->user()->id,

                'emirate_id_front' => Session::get('front_image'),
                'emirate_id_back' => Session::get('front_image'),
                'activation_screenshot' => Session::get('sr_no'),
                'saler_id' => $request->saler_id,
                // 'later' => $request->customer_name,
                // 'recording' => $request->customer_name,
                // 'assing_to' => $request->customer_name,
                // 'backoffice_by' => $request->customer_name,
                // 'cordination_by' => $request->customer_name,
                'date_time' => Carbon::now()->toDateTimeString(),
                'status' => '1.02',
                // 'selected_number' => $request->customer_name,
                // 'flexible_minutes' => $request->customer_name,
                // 'data' => $request->customer_name,
            ]);
            $activation_id = $k->id;
            $teacher_id = $request->documents;
            $book_records = [];



            if (!empty($request->documents)) {
                foreach ($request->documents as $key => $val) {
                    // return $request->audio;
                    // return $request->file();
                    if ($file = $request->file('documents')) {
                        // return "a";
                        // }
                        $ext = date('d-m-Y-H-i');
                        // return $file;
                        $mytime = Carbon::now();
                        $ext =  $mytime->toDateTimeString();
                        $name = $ext . '-' . $file[$key]->getClientOriginalName();
                        $name = Str::slug($name, '-');

                        // $name = str_replace($ext, date('d-m-Y-H-i') . $ext, $request->image->getClientOriginalName());
                        $file[$key]->move('documents', $name);
                        $input['path'] = $name;
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
                    $data = activation_document::create([
                        // 'resource_name' => $request->resource_name,
                        'username' => 'activation',
                        'document_name' => $name,
                        'lead_id' => $request->lead_id,
                        'activation_id' => $activation_id,
                        'status' => '1.02',
                        // 'batch_id' => $batch_id,
                    ]);
                    $d = lead_sale::findOrFail($request->lead_id);
                    $d->update([
                        'status' => '1.02',
                    ]);
                    $d = verification_form::findOrFail($request->ver_id);
                    $d->update([
                        'status' => '1.02',
                        'assing_to' => $request->assing_to,
                        'cordination_by' => auth()->user()->id,
                    ]);
                }
            }

            // Insert book records
            activation_document::insert($book_records);
            Session::forget('front_image');
            Session::forget('back_image');
            Session::forget('sr_no');
            notify()->success('New Activation has been created succesfully');
            // return redirect()->back()->withInput();
            return redirect(route('activation.index'));
        }
    }

}
