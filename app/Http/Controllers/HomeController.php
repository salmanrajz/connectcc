<?php

namespace App\Http\Controllers;

// use Spatie\Permission\Contracts\Role;

// use App\Models\Http\Controllers\Roles;
// use App\Models\Role;

use App\Models\activation_form;
use App\Models\channel_partner;
use App\Models\user;
use App\Models\TargetAssignerManager;
use App\Models\TargetAssignerUser;
use App\Models\emirate;
use App\Models\numberdetail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use  Spatie\Permission\Traits\HasRoles;
use Auth;
// use Roles;
use Illuminate\Http\Request;
// use App\Models\Permission;
// use Carbon;
use Carbon\Carbon;
// app/Http/Controllers/
use App\Models\CustomerFeedBack;
use App\Models\lead_sale;
use App\Models\verification_form;
// use App\Models\;
use App\Models\pageauth;
// use Illuminate\Support\Facades\Session;

use Session;
use DataTables;
use Illuminate\Support\Facades\Validator;



// use App\Models\DataTables\UsersDataTable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    // public function index(UsersDataTable $dataTable)
    // {
    //     return $dataTable->render('users.index');
    // }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //
    public function test(){
        // return "Test";
        $salesData = \App\Models\lead_sale::selectRaw("COUNT(*) as count, lead_sales.saler_id")
            ->LeftJoin(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            // ->whereBetween('created_at', [Carbon::createFromDate($year, $month, 1), Carbon::createFromDate($year, $month, $daysCount)])
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            // ->where('users.agent_code', 'CC3')
            ->groupBy('users.id')
            ->get()->pluck('saler_id');
        $a = array();
       return $data =  \App\Models\User::where('role', 'Sale')
        ->whereNotIn('id',[$salesData])
        ->get();
        foreach ($data as $k => $v) {
            $a[$k]['name'] = $v['name'];
            $a[$k]['email'] = $v['email'];
            $a[$k]['call_center'] = $v['agent_code'];
            $last_sale =     \Carbon\Carbon::parse(\App\Http\Controllers\HomeController::LastSaleCounter($v->id))->diffForHumans();
            if($last_sale == '1 second ago'){
                $a[$k]['last_sale'] = 'No Sale Made Yet';
            }

        }
        return view('email.FailureReminder',compact('a'));
        // foreach($a as $k){
        //     return "ok";
        // }
        // return $a;

        // $user = \App\Models\User::where('id',1)->get();
        // return $user;
    }
    //
    public static function days_test($now){
        $startDate = \Carbon\Carbon::now(); //returns current day
        $f = $startDate->firstOfMonth();
        $date = Carbon::parse($f);
        // $now = Carbon::now();

        return $diff = $date->diffInDays($now);
    }
    public static function days($month){
        // $month = '2020-11';
        $start = \Carbon\Carbon::parse($month)->startOfMonth();
        $end = \Carbon\Carbon::parse($month)->endOfMonth();

        $dates = [];
        while ($start->lte($end)) {
            $carbon = \Carbon\Carbon::parse($start);
            if ($carbon->isWeekend() != true) {
                $dates[] = $start->copy()->format('Y-m-d');
            }
            $start->addDay();
        }
        return count($dates);
        // foreach ($dates as $key => $dateval) {
        //     echo "<br>" . $dateval;
        // }
    }
    public static function MyCount($id)
    {
        return $id;
    }
    public static function ActivationCallAgentGrandTotal($userid)
    {
        // return $userid;
        return $k = activation_form::select('id')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            ->where('users.agent_code', $userid)
            // ->where('activation_forms.activation_date', $date)
            // ->where('')
            ->whereMonth('activation_forms.activation_date', Carbon::now()->month)
            ->count();
    }
    public static function ReservCounter($id,$channel)
    {
        return $data = \App\Models\choosen_number::select("choosen_numbers.*")
                ->LeftJoin(
                    'numberdetails','numberdetails.id','=','choosen_numbers.number_id','numberdetails.id'
                )
                ->where("choosen_numbers.status", 1)
                ->where("choosen_numbers.user_id", $id)
                ->whereIn("numberdetails.channel_type", ['ConnectCC'])
                ->count();

        // $data = choosen_number::selectRaw('users.*, COUNT(choosen_numbers.user_id) as total_posts')
        // // ->Join(
        // //     'choosen_numbers',
        // //     'choosen_numbers.number_id',
        // //     '=',
        // //     'numberdetails.id'
        // // )
        // ->Join(
        //     'users',
        //     'users.id',
        //     '=',
        //     'choosen_numbers.user_id'
        // )
        //     ->where("choosen_numbers.status", 1)
        //     ->groupBy('choosen_numbers.user_id')
        //     ->get();
    }
    public function NumberSystem(request $request){
        return $request;
    }
    // public static function NotificationCount($id,$type){
    //     return $count = \App\Models\customer_notification::select("customer_notifications.id")
    //             ->where('userid',auth()->user()->id)
    //             ->where('type',$type)
    //             ->where('status',1)
    //             ->count();
    // }
    public static function FullPlan($id,$simType){
        if($simType == 'New' || $simType == 'MNP' || $simType == 'Migration'){
            $postpaid = \App\Models\plan::select("plans.plan_name")
            ->where('plans.id', $id)
            ->first();
            return $postpaid->plan_name;
        }
        else if($simType == 'Elife'){
            // $postpaid_verified = \App\Models\plan::select("plans.plan_name")
            // ->where('plans.id', $id)
            // ->first();
             $plan = \App\Models\elife_plan::findorfail($id);
            return $plan->plan_name;
         }
         else{
             return "ITP";
         }
    }
    public static function PlanName($id){
        return $postpaid_verified = \App\Models\plan::select("plans.plan_name")
            ->where('plans.id', $id)
            ->first();
    }
    public static function ElifePlanName($id){
        return $plan_name = \App\Models\plan::findorfail($id);
    }
    // POSTPAID START
    public static function TotalLead($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', '1.07')
            // ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    // POSTPAID START
    public static function TotalLeadFloor($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('lead_sales.status', '1.07')
            // ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function TotalLeadFloorDaily($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereDate('lead_sales.created_at', Carbon::today())
            // ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('lead_sales.status', '1.07')
            // ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function TotalLeadFloorDailyCount($id){
        // return $id;
        // if(auth()->user()->role == 'TeamLeader'){
        //     return $postpaid_verified = \App\Models\User::select("users.id")
        //     // $user =  DB::table("subjects")->select('subject_name', 'id')
        //     ->Join(
        //         'lead_sales',
        //         'lead_sales.saler_id',
        //         '=',
        //         'users.id'
        //     )
        //     ->whereDate('lead_sales.created_at', Carbon::today())
        //     // ->whereYear('lead_sales.created_at', Carbon::now()->year)

        //     // ->where('lead_sales.status', '1.07')
        //     // ->where('lead_sales.lead_type', 'postpaid')
        //     ->where('users.teamleader', auth()->user()->id)
        //     ->count();

        // }else{
        //     return $postpaid_verified = \App\Models\User::select("users.id")
        //     // $user =  DB::table("subjects")->select('subject_name', 'id')
        //     ->Join(
        //         'lead_sales',
        //         'lead_sales.saler_id',
        //         '=',
        //         'users.id'
        //     )
        //     ->whereDate('lead_sales.created_at', Carbon::today())
        //     // ->whereYear('lead_sales.created_at', Carbon::now()->year)

        //     // ->where('lead_sales.status', '1.07')
        //     // ->where('lead_sales.lead_type', 'postpaid')
        //     ->where('users.agent_code', $id)
        //     ->count();
        // }
        //
        $myrole = auth()->user()->role;
        //  $mycc = \App\Models\User::where('agent_code')->where('id',$id)->first();
        //  return $id;

        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($myrole, function ($query) use ($myrole,$id) {
            if ($id == 'CC3') {
                if ($myrole == 'FloorManagerHead') {
                            return $query->where('users.agent_code', $id);

                    // return $query->where('users.teamleader', auth()->user()->tlid);
                } else {
                    return $query->where('users.agent_code', $id);
                }
            } else if ($myrole == 'TeamLeader') {
                return $query->where('users.teamleader', auth()->user()->id);
                // return $query->where('users.agent_code', auth()->user()->agent_code);
            } else {
                return $query->where('users.agent_code', $id);
            }
            })
            ->whereDate('lead_sales.created_at', Carbon::today())
            // ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('lead_sales.status', '1.07')
            // ->where('lead_sales.lead_type', 'postpaid')
            // ->where('users.agent_code', $id)
            ->count();
        //
    }
    public static function TotalLeadFloorCount($id){
        // return $id;
        $role = auth()->user()->role;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where('users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
            })
            // ->where('lead_sales.status', '1.07')
            // ->where('lead_sales.lead_type', 'postpaid')
            // ->where('users.agent_code', $id)
            ->count();
    }
    // public static function TotalLeadFloorCountDaily($id){
    //     // return $id;
    //     return $postpaid_verified = \App\Models\User::select("users.id")
    //         // $user =  DB::table("subjects")->select('subject_name', 'id')
    //         ->Join(
    //             'lead_sales',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         ->whereDate('lead_sales.created_at', Carbon::today())

    //         // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
    //         // ->whereYear('lead_sales.created_at', Carbon::now()->year)

    //         // ->where('lead_sales.status', '1.07')
    //         // ->where('lead_sales.lead_type', 'postpaid')
    //         ->where('users.agent_code', $id)
    //         ->count();
    // }
    // POSTPAID START
    public static function TotalLeadManager(){
        // return $id;
       return $tmkoc = lead_sale::select('lead_sales.*')
       ->join(
           'users',
           'users.id',
           '=',
           'lead_sales.saler_id'
       )
       ->where('users.agent_code',auth()->user()->agent_code)
       ->count();

    }
    public static function TotalLeadManagerChannel($channel){
        // return $id;
       return $tmkoc = lead_sale::select('lead_sales.*')
       ->join(
           'users',
           'users.id',
           '=',
           'lead_sales.saler_id'
       )
       ->where('users.agent_code',auth()->user()->agent_code)
       ->where('lead_sales.channel_type',$channel)
       ->count();

    }
    public static function TotalLeadManagerChannelStatus($channel,$status){
        // return $id;
       return $tmkoc = lead_sale::select('lead_sales.*')
       ->join(
           'users',
           'users.id',
           '=',
           'lead_sales.saler_id'
       )
        ->Join(
            'status_codes',
            'status_codes.status_code',
            '=',
            'lead_sales.status'
        )
       ->where('users.agent_code',auth()->user()->agent_code)
       ->where('lead_sales.channel_type',$channel)
       ->where('lead_sales.status',$status)
       ->count();
    }
    public static function TotalLeadManagerChannelStatusVerified($channel,$status){
        // return $id;
        if ($status == 'verified') {
            return $tmkoc = lead_sale::select('lead_sales.*')
                ->join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->whereIn('lead_sales.status', ['1.07','1.10','1.02'])
                ->count();
        }
        elseif($status == 'rejected'){
            return $tmkoc = lead_sale::select('lead_sales.*')
                ->join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->whereIn('lead_sales.status', ['1.15','1.04'])
                ->count();
        }

    }
    public static function TotalLeadManagerChannelStatusVerifiedElife($channel,$status){
        // return $id;
        if ($status == 'verified') {
            // return $tmkoc = lead_sale::select('lead_sales.*')
            //     ->join(
            //         'users',
            //         'users.id',
            //         '=',
            //         'lead_sales.saler_id'
            //     )
            //     ->Join(
            //         'status_codes',
            //         'status_codes.status_code',
            //         '=',
            //         'lead_sales.status'
            //     )
            //     ->where('users.agent_code', auth()->user()->agent_code)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->whereIn('lead_sales.status', ['1.07','1.16'])
            //     ->count();
            return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
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
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.status', ['1.07', '1.16'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
        }
        elseif($status == 'rejected'){
            return $tmkoc = lead_sale::select('lead_sales.*')
                ->join(
                    'users',
                    'users.id',
                    '=',
                    'lead_sales.saler_id'
                )
                ->Join(
                    'status_codes',
                    'status_codes.status_code',
                    '=',
                    'lead_sales.status'
                )
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->whereIn('lead_sales.status', ['1.15','1.04'])
                ->count();
        }

    }
    //
    public static function TotalLeadStatus($id,$status,$channel){
        // return $id;
        $myrole = auth()->user()->multi_agentcode;
        $finalRole = auth()->user()->role;

        if($id == 'followup'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.19','1.20'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->where('users.agent_code', auth()->user()->agent_code)
                ->when($myrole, function ($query) use ($myrole, $finalRole) {
                    if ($finalRole == 'TeamLeader') {
                        return $query->where('users.teamleader',auth()->user()->id);
                    }
                    else if ($myrole == '1') {
                        // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                        //
                        return $query->where('users.agent_code', auth()->user()->agent_code);
                    }
                    else {
                        return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                    }
                    // else if($myrole == 'KHICordination'){
                    //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                    // }
                    // else {
                    //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                    // }
                })
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->where('users.id', $id)
                ->count();
        }
        if ($id == '1.07') {
            // else
            return$operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                ->orderBy('lead_sales.updated_at', 'desc')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->get()->count();
            // return $postpaid_verified = \App\Models\User::select("users.id")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->Join(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     // ->where('lead_sales.status', $id)
            //     ->where('lead_sales.lead_type', $status)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', auth()->user()->agent_code)
            //     ->whereMonth('verification_forms.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else {
            return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
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
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('users.id', $id)
            ->count();
        }
    }
    public static function CombineTotalLeadStatus($id,$status,$channel,$agent_code){
        // return $id;
        if($id == 'followup'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.19','1.20'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', $agent_code)
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->where('users.id', $id)
                ->count();
        }
        if ($id == '1.07') {
            // else
            return$operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", 'users.name as agent_name')
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
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                ->orderBy('lead_sales.updated_at', 'desc')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->get()->count();
            // return $postpaid_verified = \App\Models\User::select("users.id")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->Join(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     // ->where('lead_sales.status', $id)
            //     ->where('lead_sales.lead_type', $status)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', auth()->user()->agent_code)
            //     ->whereMonth('verification_forms.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else {
            return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code', $agent_code)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('users.id', $id)
            ->count();
        }
    }
    // public static function
    public static function TotalLeadStatus_daily($id,$status,$channel){
        // return $id;
        if($id == 'pending_followup'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.19','1.20'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == 'reprocess'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.10','1.21'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == '1.07'){
            // else
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.status', $id)
                // ->where('lead_sales.lead_type', $status)
                                // {{$HomeCount::TotalLeadStatus_daily('1.07','postpaid',$cp->name)}}
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code',auth()->user()->agent_code)
                ->whereDate('verification_forms.created_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else{

        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',auth()->user()->agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.id', $id)
            ->count();
        }

    }
    public static function TotalLeadStatus_dailyVU($id,$status,$channel){
        // return $id;
        $myrole = auth()->user()->multi_agentcode;
        if($id == 'pending_followup'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.19','1.20'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == 'reprocess'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.10','1.21'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == '1.07'){
            // else
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                ->whereIn('lead_sales.status', ['1.07','1.09'])
                // ->where('lead_sales.status', $id)
                // ->where('lead_sales.lead_type', $status)
                                // {{$HomeCount::TotalLeadStatus_daily('1.07','postpaid',$cp->name)}}
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->where('users.agent_code',auth()->user()->agent_code)
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
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->WhereMonth('verification_forms.created_at', Carbon::now()->month)
                ->WhereYear('verification_forms.created_at', Carbon::now()->year)
                // -> whereMonth('lead_sales.updated_at', Carbon::now()->month)

                // ->where('users.id', $id)
                ->count();
        }
        else{

        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',auth()->user()->agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.id', $id)
            ->count();
        }

    }
    public static function CombineTotalLeadStatus_daily($id,$status,$channel,$agent_code){
        // return $id;
        if($id == 'pending_followup'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.19','1.20'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', $agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == 'main_cord_pending'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', $agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == 'reprocess'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.10','1.21'])
                ->where('lead_sales.lead_type', $status)
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code', $agent_code)
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else if($id == '1.07'){
            // else
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->Join(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                // ->whereIn(
                //     'lead_sales.status',
                //     ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.13']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
                // )
                // ->where('lead_sales.status', $id)
                // ->where('lead_sales.lead_type', $status)
                                // {{$HomeCount::TotalLeadStatus_daily('1.07','postpaid',$cp->name)}}
                ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.agent_code',$agent_code)
                ->whereDate('verification_forms.created_at', Carbon::today())
                // ->where('users.id', $id)
                ->count();
        }
        else{

        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',$agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.id', $id)
            ->count();
        }

    }
    public static function total_reject_daily($id,$status,$channel){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.status', ['1.04','1.15'])
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',auth()->user()->agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function Combinetotal_reject_daily($id,$status,$channel,$agent_code){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.status', ['1.04','1.15'])
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',$agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function total_reject_monthly($id,$status,$channel){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.status', ['1.04','1.15'])
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',auth()->user()->agent_code)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('users.id', $id)
            ->count();
    }
    public static function Combinetotal_reject_monthly($id,$status,$channel,$agent_code){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.status', ['1.04','1.15'])
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code',$agent_code)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            // ->where('users.id', $id)
            ->count();
    }
    public static function total_active_daily($id,$status,$channel){
        // return $id;
        $myrole = auth()->user()->multi_agentcode;

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
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
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
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function total_non_active_daily($id,$status,$channel){
        // return $id;
        $myrole = auth()->user()->multi_agentcode;

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
            ->where('activation_forms.status', '1.04')
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
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
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function combine_total_active_daily($id,$status,$channel,$agent_code){
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
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code', $agent_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function total_active_monthly($id,$status,$channel){
        // return $id;
        $myrole = auth()->user()->multi_agentcode;

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
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
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
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at',Carbon::now()->year)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function Combinetotal_active_monthly($id,$status,$channel,$agent_code){
        // return $id;
        $myrole = auth()->user()->multi_agentcode;

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
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
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
            // ->where('users.agent_code', $agent_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function total_active_monthly_agent($id,$status,$channel){
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
            ->whereIn('activation_forms.status', ['1.02','1.11','1.08'])
            ->whereIn('lead_sales.lead_type', ['postpaid','HomeWifi'])
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', auth()->user()->id)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get()
            ->count();
        // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function LastSaleCounter($id){
        // return $id;
         $active = \App\Models\activation_form::select('activation_forms.created_at')
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
            ->whereIn('lead_sales.lead_type', ['postpaid','HomeWifi'])
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->orderBy('activation_forms.created_at','desc')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->first();
            if($active){
                return $active->created_at;

            }
            // ->count();
        // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function userwise_target($id,$month){
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
            ->whereIn('activation_forms.status', ['1.02','1.11','1.08'])
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->whereMonth('activation_forms.created_at', $month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function userwise_totaltarget($id,$month){
        // return $id;
        $r = TargetAssignerUser::select('target_assigner_users.target')
        ->LeftJoin(
            'users',
            'users.id',
            'target_assigner_users.user'
        )
        // ->where('agent_targets.sim_type', $type)
        ->where('users.id',
            $id
        )
        ->where('target_assigner_users.month', $month)
        ->first();
        // return $r->
        // $count =
        if ($r) {
            return $r->target;
        } else {
            return "0";
        }
        //
    }
    public static function userwise_target_paid($id,$month){
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
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->whereMonth('activation_forms.created_at', $month)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function userwise_target_free($id,$month){
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
            ->where('activation_forms.pay_status', 'Free')
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->whereMonth('activation_forms.created_at', $month)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function userwise_all($id,$month){
        // return $id;
            return $active = \App\Models\lead_sale::select('lead_sales.id')
            ->LeftJoin(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            // ->where('activation_forms.status', '1.02')
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->whereMonth('lead_sales.created_at', $month)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function userwise_non_verified($id,$month){
        // return $id;
            return $active = \App\Models\lead_sale::select('lead_sales.id')
            ->LeftJoin(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.status','1.03')
            // ->where('activation_forms.status', '1.02')
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->whereMonth('lead_sales.created_at', $month)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function userwise_verified($id,$month){
        // return $id;
            return $active = \App\Models\lead_sale::select('lead_sales.id')
            ->LeftJoin(
                'users',
                'users.id',
                'lead_sales.saler_id'
            )
            ->where('lead_sales.status','!=', '1.03')
            ->where('lead_sales.status','!=', '1.04')
            ->where('lead_sales.status','!=', '1.15')
            // ->where('activation_forms.status', '1.02')
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.id', $id)
            ->whereMonth('lead_sales.created_at', $month)
            ->get()
            ->count();
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                // ->where('users.id', $id)
    }
    public static function TotalLeadStatusZ($id,$status,$channel){
        // return $id;
        if($id == '1.03'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', $id)
                ->where('lead_sales.lead_type', $status)
                // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('lead_sales.pre_check_agent',auth()->user()->agent_code)
                ->whereDate('lead_sales.verify_date', Carbon::today())
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->count();
        }
        else{


        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            // ->where('lead_sales.pre_check_agent',auth()->user()->agent_code)
            // ->whereDate('lead_sales.verify_date', Carbon::today())
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->count();
        }
    }
    public static function TotalLeadStatusZDaily($id,$status,$channel){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            // ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            // ->where('lead_sales.pre_check_agent',auth()->user()->agent_code)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->count();
    }
    // public static function TotalLeadStatusPostpaid($id,$status,$channel){
    //     // return $id;
    //     return $postpaid_verified = \App\Models\User::select("users.id")
    //         // $user =  DB::table("subjects")->select('subject_name', 'id')
    //         ->Join(
    //             'lead_sales',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         ->where('lead_sales.status', $id)
    //         ->where('lead_sales.lead_type', $status)
    //         ->whereIn('lead_sales.channel_type', ['ConnectCC'])
    //         // ->where('users.id', $id)
    //         ->count();
    // }
    public static function TotalLeadStatusElife($id,$status){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $id)
            ->where('lead_sales.lead_type', $status)
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            // ->where('users.id', $id)
            ->count();
    }
    public static function TotalLeadVerified($id,$leadtype,$channel)
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
            ->where('lead_sales.lead_type',$leadtype)
            // ->where('lead_sales.channel_type',$channel)
            ->where('users.id', auth()->user()->id)
            ->whereMonth('verification_forms.created_at', Carbon::now()->month)
            ->whereYear('verification_forms.created_at', Carbon::now()->year)
            ->count();
    }
    public static function TotalLeadVerifiedDaily($id,$leadtype,$channel)
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
            // ->where('lead_sales.lead_type',$leadtype)
            // ->where('lead_sales.channel_type',$channel)
            ->where('users.id', auth()->user()->id)
            ->whereDate('verification_forms.created_at', Carbon::today())
            ->count();
    }

    public static function TotalLeadNonVerified($id,$leadtype,$channel)
    {
        // return $id;
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
        ->where('lead_sales.status', $id)
        ->where('lead_sales.lead_type',$leadtype)
        // ->where('lead_sales.channel_type',$channel)
        // ->where('lead_sales.pre_check_agent', auth()->user()->id)
        ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
        ->whereYear('lead_sales.created_at', Carbon::now()->year)

        ->get()->count();

    }
    public static function TotalLeadActiveNonVerified($id,$leadtype,$channel)
    {
        // return $channel;
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
        ->where('lead_sales.status', $id)
            ->whereIn('lead_sales.channel_type', $mychannel)

        ->where('lead_sales.lead_type',$leadtype)
        // ->where('lead_sales.channel_type',$channel)
        // ->where('lead_sales.pre_check_agent', auth()->user()->id)
        // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
        ->get()
        ->count();

    }
    public static function TotalLeadActiveNonVerifiedAll($id,$leadtype,$channel)
    {
        // return $channel;
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
        ->where('lead_sales.status', $id)
        ->where('lead_sales.lead_type',$leadtype)
        // ->where('lead_sales.channel_type',$channel)
        // ->where('lead_sales.pre_check_agent', auth()->user()->id)
        ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
        ->whereYear('lead_sales.updated_at', Carbon::now()->year)
        ->get()
        ->count();

    }
    public static function TotalLeadNonVerifiedDaily($id,$leadtype,$channel)
    {
        // return $id;
        return $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name")
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
        ->where('lead_sales.status', $id)
        ->where('lead_sales.lead_type',$leadtype)
        // ->where('lead_sales.channel_type',$channel)
        // ->where('lead_sales.pre_check_agent', auth()->user()->id)
        ->whereDate('lead_sales.updated_at', Carbon::today())
        ->get()->count();

    }
    public static function TotalLeadActiveNonVerifiedDaily($id,$leadtype,$channel)
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        // return $id;
        return $operation = lead_sale::select("lead_sales.id")
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
            ->whereIn('lead_sales.channel_type', $mychannel)

        ->where('lead_sales.status', $id)
        ->where('lead_sales.lead_type',$leadtype)
        // ->where('lead_sales.channel_type',$channel)
        // ->where('lead_sales.pre_check_agent', auth()->user()->id)
        ->whereDate('lead_sales.updated_at', Carbon::today())
        ->get()->count();

    }
    public static function EmirateTotalLeadActiveNonVerifiedDaily($id,$leadtype,$channel)
    {
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
        // return $id;
        return $operation = lead_sale::select("lead_sales.id")
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
        ->whereIn('lead_sales.channel_type', $mychannel)
        ->where('lead_sales.status', $id)
        ->where('lead_sales.lead_type',$leadtype)
        ->where('lead_sales.channel_type',$channel)
        ->whereIn('lead_sales.emirates', explode(',',auth()->user()->emirate))
        // ->where('lead_sales.pre_check_agent', auth()->user()->id)
        ->whereDate('lead_sales.updated_at', Carbon::today())
        ->get()->count();

    }
    public static function TotalChannelLead($id,$leadtype,$channel)
    {
        // return $id;
        if($id == '1.01'){
            // return $id . $leadtype;
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                // ->Join(
                //     'verification_forms',
                //     'verification_forms.verify_agent',
                //     '=',
                //     'users.id'
                // )
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', $id)
                // ->where('lead_sales.lead_type', $leadtype)
                // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.id', auth()->user()->id)
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                ->count();
        }
        elseif($id == '1.04'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                // ->Join(
                //     'verification_forms',
                //     'verification_forms.verify_agent',
                //     '=',
                //     'users.id'
                // )
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', $id)
                ->where('lead_sales.lead_type', $leadtype)
                // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.id', auth()->user()->id)
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                ->count();
        }
        elseif($id == '1.09'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                // ->Join(
                //     'verification_forms',
                //     'verification_forms.verify_agent',
                //     '=',
                //     'users.id'
                // )
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])

                ->where('lead_sales.lead_type', $leadtype)
                // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.id', auth()->user()->id)
                ->whereDate('lead_sales.created_at', Carbon::today())
                // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                ->count();
        }
        else if($id == '1.02'){
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                // ->Join(
                //     'verification_forms',
                //     'verification_forms.verify_agent',
                //     '=',
                //     'users.id'
                // )
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', $id)
                ->where('lead_sales.lead_type', $leadtype)
                // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.id', auth()->user()->id)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->count();
        }
        else{
            return $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')

                // ->Join(
                //     'verification_forms',
                //     'verification_forms.verify_agent',
                //     '=',
                //     'users.id'
                // )
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', $id)
                ->where('lead_sales.lead_type', $leadtype)
                // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
                ->where('users.id', auth()->user()->id)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->count();
        }

    }
    public static function TotalLeadVerifiedElife($id,$leadtype)
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
            ->where('lead_sales.status', '1.07')
            ->where('lead_sales.lead_type',$leadtype)
            // ->where('lead_sales.channel_type',$channel)
            ->where('users.id', auth()->user()->id)
            ->count();
    }
    public static function TotalPostPaidLead($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.lead_type', 'postpaid')
            // ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function TotalPostElifeLead($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.lead_type', 'Elife')
            // ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function TotalPostITLead($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.lead_type', 'ITProducts')
            // ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function PostPaidVerified($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.07')
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function PostPaidPending($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.01')
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    //
    public static function FloorManagerLeadType($id,$status,$type,$channel){
        if ($status == 'verified') {
            // return "00";
            // return $id . $status . $type . $channel;
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                    // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                )
                // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();

            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            //     ->LeftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
            //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.id', trim($id))
            //     ->whereDate('verification_forms.created_at', Carbon::today())
            //     // ->whereDate('lead_sales.created_', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        else if ($status == 'inprocess') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'later') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->where('lead_sales.status','1.06')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'rejected') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.15'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'followup') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == '1.02') {
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
                ->whereIn('activation_forms.status', ['1.02','1.08','1.11'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpactive') {
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
                ->whereIn('activation_forms.status', ['1.02','1.11','1.08'])
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }

        else {
            // $today = Carbon::today();
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->where('lead_sales.status', $status)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
    }
    //
    public static function FloorManagerLeadTypeDaily($id,$status,$type,$channel){
        if ($status == 'pending') {
            // return "00";
            // return $id . $status . $type . $channel;
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                -> whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();

            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            //     ->LeftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
            //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.id', trim($id))
            //     ->whereDate('verification_forms.created_at', Carbon::today())
            //     // ->whereDate('lead_sales.created_', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        elseif ($status == 'verified') {
            // return "00";
            // return $id . $status . $type . $channel;
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
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.15','1.06'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                -> whereDate('verification_forms.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();

            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            //     ->LeftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
            //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.id', trim($id))
            //     ->whereDate('verification_forms.created_at', Carbon::today())
            //     // ->whereDate('lead_sales.created_', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        else if ($status == 'inprocess') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())

                // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'mnpinprocess') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
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
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.sim_type','MNP')
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('verification_forms.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'later') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->where('lead_sales.status','1.06')
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'rejected') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.15'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'followup') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == '1.02') {
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
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.id', trim($id))
            ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpactive') {
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])

                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        else {
            // $today = Carbon::today();
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->where('lead_sales.status', $status)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereYear('activation_forms.created_at', Carbon::now()->year)
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
    }
    public static function TotalLeadFloorCountDaily($id,$status,$type,$channel){
        $role = auth()->user()->role;
        if ($status == 'pending') {
            // return "00";
            // return $id . $status . $type . $channel;
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                // ->LeftJoin(
                //     'verification_forms',
                //     'verification_forms.lead_no',
                //     '=',
                //     'lead_sales.id'
                // )
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role,$id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                            return $query->where('users.agent_code', $id);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                -> whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();

            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            //     ->LeftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
            //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.id', trim($id))
            //     ->whereDate('verification_forms.created_at', Carbon::today())
            //     // ->whereDate('lead_sales.created_', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        elseif ($status == 'verified') {
            // return "00";
            // return $id . $status . $type . $channel;
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
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role,$id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                        return $query->where('users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                -> whereDate('verification_forms.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();

            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            //     ->LeftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
            //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.id', trim($id))
            //     ->whereDate('verification_forms.created_at', Carbon::today())
            //     // ->whereDate('lead_sales.created_', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        else if ($status == 'inprocess') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();


            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())

                // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'mnpinprocess') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();


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
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.sim_type','MNP')
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where('users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereDate('verification_forms.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'later') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->where('lead_sales.status','1.06')
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                        return $query->where('users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'rejected') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.15'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'followup') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    }
                    else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                // ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        else if ($status == '1.02') {
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
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
            // ->where('users.agent_code', trim($id))
            ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        else if ($status == 'mnpactive') {
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
            ->whereIn('activation_forms.status', ['1.02','1.11','1.08'])
            ->where('lead_sales.sim_type','MNP')
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where('users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
            // ->where('users.agent_code', trim($id))
            ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        else {
            // $today = Carbon::today();
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->where('lead_sales.status', $status)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())

                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereYear('activation_forms.created_at', Carbon::now()->year)
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
    }
    public static function FloorManagerLeadTypeCount($id,$status,$type,$channel){
        $role = auth()->user()->role;
        if ($status == 'verified') {
            // return "00";
            // return $id . $status . $type . $channel;
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status',
                ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                    // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                )
                // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                            return $query->where('users.agent_code', $id);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                -> whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get()
                // ->where('users.id', $id)
                ->count();

            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            //     ->LeftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
            //     // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.id', trim($id))
            //     ->whereDate('verification_forms.created_at', Carbon::today())
            //     // ->whereDate('lead_sales.created_', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        else if ($status == 'inprocess') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                        return $query->where('users.agent_code', $id);

                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'later') {
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations',
                'lead_locations.lead_id',
                'lead_sales.id'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                            return $query->where('users.agent_code', $id);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->where('lead_sales.status','1.06')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        }
        else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                            return $query->where('users.agent_code', $id);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'rejected') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.15'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                            return $query->where('users.agent_code', $id);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'followup') {
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel,$role,$id) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    }
                //     elseif ($role == 'FloorManagerHead') {
                //         return $query->where('users.agent_code', $id);
                //         // return $query->where('users.teamleader', auth()->user()->tlid);
                // }
                else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.date_time', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == '1.02') {
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where('users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpactive') {
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
                ->whereIn('activation_forms.status', ['1.02','1.11','1.08'])
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        else {
            // $today = Carbon::today();
            return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
                ->where('lead_sales.status', $status)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->when($role, function ($query) use ($role, $id) {
                    if ($id == 'CC3') {
                        if ($role == 'FloorManagerHead') {
                            return $query->where( 'users.agent_code', $id);
                            // return $query->where('users.teamleader', auth()->user()->tlid);
                        }
                        else {
                            return $query->where('users.agent_code', $id);
                        }
                    } else if ($role == 'TeamLeader') {
                        return $query->where('users.teamleader', auth()->user()->id);
                        // return $query->where('users.agent_code', auth()->user()->agent_code);
                    } else {
                        return $query->where('users.agent_code', $id);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
    }
    public static function TotalLeadtype($id,$status,$type, $channel){
        // return $id;
        $channel = 'Combined';
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['ConnectCC']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('users.agent_code', auth()->user()->agent_code)
            ->where('users.id', $id)
            ->count();
    }
    public static function TotalLaterLead($id,$status){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.pre_check_agent',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', 'postpaid')
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->where('users.id', $id)
            ->count();
    }
    public static function TotalLaterLeadElife($id,$status){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.pre_check_agent',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', 'elife')
            // ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->where('users.id', $id)
            ->count();
    }
    public static function PostPaidFollow($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.id', auth()->user()->id)
            ->where('lead_sales.status', '1.03')
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function PostPaidReject($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.04')
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    public static function PostPaidActive($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.02')
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.id', $id)
            ->count();
    }
    // POSTPAID END

    // ELIFE START
    public static function ElifeVerified($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.07')
            ->where('lead_sales.lead_type', 'Elife')
            ->where('users.id', $id)
            ->count();
    }
    public static function ElifePending($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.01')
            ->where('lead_sales.lead_type', 'Elife')
            ->where('users.id', $id)
            ->count();
    }
    public static function ElifeFollow($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.id', auth()->user()->id)
            ->where('lead_sales.status', '1.03')
            ->where('lead_sales.lead_type', 'Elife')
            ->where('users.id', $id)
            ->count();
    }
    public static function ElifeReject($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.04')
            ->where('lead_sales.lead_type', 'Elife')
            ->where('users.id', $id)
            ->count();
    }
    public static function ElifeActive($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.02')
            ->where('lead_sales.lead_type', 'Elife')
            ->where('users.id', $id)
            ->count();
    }
    // ELIFE END
    // ITProduct START
    public static function ITProductVerified($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.07')
            ->where('lead_sales.lead_type', 'ITProducts')
            ->where('users.id', $id)
            ->count();
    }
    // public function
    public static function ITProductPending($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.01')
            ->where('lead_sales.lead_type', 'ITProducts')
            ->where('users.id', $id)
            ->count();
    }
    public static function ITProductFollow($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.03')
            ->where('lead_sales.lead_type', 'ITProducts')
            ->where('users.id', $id)
            ->count();
    }
    public static function ITProductReject($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.04')
            ->where('lead_sales.lead_type', 'ITProducts')
            ->where('users.id', $id)
            ->count();
    }
    public static function ITProductActive($id){
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('lead_sales.status', '1.02')
            ->where('lead_sales.lead_type', 'ITProducts')
            ->where('users.id', $id)
            ->count();
    }
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
    public function AllCordinationPending($id){
        // return $id;
        if($id == 'AllCord'){
            if (auth()->user()->role == 'MainCoordinator') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id", 'verification_forms.created_at as verified_date')
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->LeftJoin(
                    'timing_durations',
                    'timing_durations.lead_no',
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
                    ->LeftJoin(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    // ->whereIn('verification_forms.status', ['1.09','1.16'])
                    // ->where('users.agent_code', auth()->user()->agent_code)
                    ->get();
                    return view('dashboard.manage-cordination-lead', compact('operation'));

            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
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
                    ->Join(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->whereIn('lead_sales.status', ['1.07','1.16'])
                    ->where('users.agent_code', auth()->user()->agent_code)
                    ->get();
                return view('dashboard.manage-cordination-lead', compact('operation'));
            }
        }
        else if($id == 'AllActive')
        {
            //
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
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();

            return view('dashboard.view-activation-pending', compact('operation'));
        }
        else if($id == 'CordFollow'){
            if (auth()->user()->role == 'MainCoordinator') {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
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
                    ->Join(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->where('verification_forms.status','1.16')        // ->where('users.agent_code', auth()->user()->agent_code)
                    ->get();
                return view('dashboard.manage-cordination-lead', compact('operation'));
            } else {
                $operation = lead_sale::select("timing_durations.lead_generate_time", "lead_sales.*", "status_codes.status_name", "verification_forms.id as ver_id")
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
                    ->Join(
                        'verification_forms',
                        'verification_forms.lead_no',
                        '=',
                        'lead_sales.id'
                    )
                    ->where('lead_sales.status', '1.16')
                    ->where('users.agent_code', auth()->user()->agent_code)
                    ->get();
                return view('dashboard.manage-cordination-lead', compact('operation'));
            }
        }
        else if($id == 'CordActive'){
            //
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
            ->where('verification_forms.status', '1.17')
            ->where('lead_locations.assign_to', auth()->user()->id)
            // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            ->groupBy('verification_forms.lead_no')
            ->get();

            return view('dashboard.view-activation-pending', compact('operation'));
        }
    }
    public function Online()
    {
        $users = User::all();
        return view('dashboard.status', compact('users'));
    }
    public function SMSFinal()
    {
        // $users = User::all();
        $users = User::select('users.*')
                ->whereIn("users.role",array('sale','NumberAdmin'))->get();

        return view('dashboard.allow-for-sms', compact('users'));
    }
    //
    public function guest_type($slug){
        if (auth()->user()->role == 'Guest') {
            // $data = numberdetail
            //    return
            $agent_code = auth()->user()->agent_code;
            $r = numberdetail::select('numberdetails.type')->where('status', 'Available')->groupBy('numberdetails.type')->get();
            $data = \App\Models\numberdetail::select("numberdetails.*")
            // ->LeftJoin(
            //     'numberdetails',
            //     'numberdetails.id',
            //     '=',
            //     'choosen_numbers.number_id',
            //     'numberdetails.id'
            // )
            ->LeftJoin(
                'channel_partners',
                'channel_partners.name',
                '=',
                'numberdetails.channel_type',
                // 'numberdetails.id'
            )
                ->where("numberdetails.status", 'Available')
                ->whereNull('numberdetails.region')
                ->where(function ($query) use ($agent_code) {
                    $query->where('call_center', $agent_code)
                        ->orWhere('call_center', 'default');
                })
                ->when($agent_code, function ($query) use ($agent_code) {
                    if ($agent_code == 'CC5' || $agent_code == 'CC4' || $agent_code == 'CC7Z' || $agent_code == 'CC6' || $agent_code == 'CC11Z' || $agent_code == 'CC10' || $agent_code == 'CC8' || $agent_code == 'CC1' || $agent_code == 'CC2Z' || $agent_code == 'CC3' || $agent_code == 'CC9Z' || $agent_code == 'CC10' || $agent_code == 'CC11Z' || $agent_code == 'CC12Z' || $agent_code == 'CC13'){
                        return $query->whereNotIn('numberdetails.identity', ['SLPJUN1ED', 'GLDJUN1ED', 'PLTJUN1ED']);
                    }
                    if ($agent_code == 'CC10') {
                        // return $query->whereIn('lead_locations.assign_to', ['136']);
                        return $query->whereIn('numberdetails.channel_type', ['TTF'])
                        ->OrwhereIn('numberdetails.identity', ['TFE22', 'GLD2MT22', 'SS2MT22']);
                    }
                    elseif ($agent_code == 'AAMT') {
                        // return $query->whereIn('lead_locations.assign_to', ['136']);
                        return $query->whereIn('numberdetails.channel_type', ['ExpressDial']);
                        // ->OrwhereIn('numberdetails.identity', ['TFE22', 'GLD2MT22', 'SS2MT22']);
                    }
                    else {
                        return $query->whereIn('numberdetails.channel_type', ['ConnectCC']);
                    }
                })
                // ->where("numberdetails.channel_type", 'TTF')
                // ->where("numberdetails.status", 'Available')
                // ->where("channel_partners.status", 1)
                ->where("numberdetails.type", $slug)
                // ->take(1000)
                // ->where('choose_n')
                // ->where("numberdetails.channel_type", $channel)
                ->get();
            return view('number.guest-number', compact('data','r'));
        }
    }
    public function index_material(){
        return view('trainer.video-dashboard');
    }
    public function whatsapp_training(){
        return view('trainer.whatsapp-video-dashboard');
    }
    public function material_code(){
        // $call_center =
        $call_center = \App\Models\call_center::where('status', '1')->get();
        return view('trainer.code-dashboard',compact('call_center'));
    }
    //
    public function material_code_request(){
        // use
        // $z = \App\Models\pageauth::where('userid',auth()->user()->id)->where('other',1)->first();
        // if(!$z){
            $code = rand(1013, 11210);
            if(auth()->user()->agent_code == 'CC2'){
                $data = pageauth::create([
                    'userid' => auth()->user()->id,
                    'code' => $code,
                    'expires_at' => Carbon::now(),
                    'other' => '0'
                ]);
                $details = [
                    'code' => $code,
                    'email' => auth()->user()->email,
                    'call_center' => auth()->user()->agent_code,
                ];
                \Mail::to('isqintl@gmail.com')
                ->cc(['salmanahmed334@gmail.com', 'kkashifs@gmail.com', 'parvejalam18516@gmail.com'])
                // ->cc(['kkashifs@gmail.com'])
                ->send(new \App\Models\Mail\TrainingCode($details));
                \App\Models\Http\Controllers\WhatsAppController::TrainingWhatsApp($details);
            }
            elseif(auth()->user()->agent_code == 'CC1'){
                $data = pageauth::create([
                    'userid' => auth()->user()->id,
                    'code' => $code,
                    'expires_at' => Carbon::now(),
                    'other' => '0'
                ]);
                $details = [
                    'code' => $code,
                    'email' => auth()->user()->email,
                    'call_center' => auth()->user()->agent_code,
                ];
                \Mail::to('isqintl@gmail.com')
                ->cc(['salmanahmed334@gmail.com','hassancheema360@gmail.com'])
                // ->cc(['kkashifs@gmail.com'])
                ->send(new \App\Models\Mail\TrainingCode($details));
                \App\Models\Http\Controllers\WhatsAppController::TrainingWhatsApp($details);

            }
            elseif(auth()->user()->agent_code == 'CC11'){
                $data = pageauth::create([
                    'userid' => auth()->user()->id,
                    'code' => $code,
                    'expires_at' => Carbon::now(),
                    'other' => '0'
                ]);
                $details = [
                    'code' => $code,
                    'email' => auth()->user()->email,
                    'call_center' => auth()->user()->agent_code,
                ];
                \Mail::to('isqintl@gmail.com')
                ->cc(['salmanahmed334@gmail.com', 'gajendra@rolextogroup.com'])
                // ->cc(['kkashifs@gmail.com'])
                ->send(new \App\Models\Mail\TrainingCode($details));
                \App\Models\Http\Controllers\WhatsAppController::TrainingWhatsApp($details);

            }
            elseif(auth()->user()->agent_code == 'AAMT'){
                $data = pageauth::create([
                    'userid' => auth()->user()->id,
                    'code' => $code,
                    'expires_at' => Carbon::now(),
                    'other' => '0'
                ]);
                $details = [
                    'code' => $code,
                    'email' => auth()->user()->email,
                    'call_center' => auth()->user()->agent_code,
                ];
                \Mail::to('isqintl@gmail.com')
                ->cc(['tycoontechbox@gmail.com'])
                // ->cc(['kkashifs@gmail.com'])
                ->send(new \App\Models\Mail\TrainingCode($details));
                \App\Models\Http\Controllers\WhatsAppController::TrainingWhatsApp($details);

            }
            elseif(auth()->user()->agent_code == 'CC7'){
                $data = pageauth::create([
                    'userid' => auth()->user()->id,
                    'code' => $code,
                    'expires_at' => Carbon::now(),
                    'other' => '0'
                ]);
                $details = [
                    'code' => $code,
                    'email' => auth()->user()->email,
                    'call_center' => auth()->user()->agent_code,
                ];
                \Mail::to('isqintl@gmail.com')
                // ->cc(['salmanahmed334@gmail.com', 'gajendra@rolextogroup.com'])
                // ->cc(['kkashifs@gmail.com'])
                ->send(new \App\Models\Mail\TrainingCode($details));
                \App\Models\Http\Controllers\WhatsAppController::TrainingWhatsApp($details);

            }
            else{


            $data = pageauth::create([
                'userid' => auth()->user()->id,
                'code' => $code,
                'expires_at' => Carbon::now(),
                'other' => '0'
            ]);
            $details = [
                'code' => $code,
                'email' => auth()->user()->email,
                'call_center' => auth()->user()->agent_code,
            ];
            \Mail::to('isqintl@gmail.com')
            ->cc(['salmanahmed334@gmail.com'])
            ->bcc(['syedaashirhussain@gmail.com'])
            ->send(new \App\Models\Mail\TrainingCode($details));
            \App\Models\Http\Controllers\WhatsAppController::TrainingWhatsApp($details);
        }
            return 1;
        // }
    }
    //

    //
    public function material_code_accept(Request $request){
        // use
        $z = \App\Models\pageauth::where('userid', auth()->user()->id)->where('other','0')->latest();
        if($z){
            $validate = \App\Models\pageauth::where('code', $request->MyCode)->where('other','0')->first();
            if($validate){
                // return "1";
                $validate->expires_at = Carbon::now()->addHours(1);
                $validate->other = '1';
                $validate->save();
                return "1";
            }else{
                return "2";
            }

        }
        //    $z = pageauth::
    }
    //
    public function floorlead(Request $request){
        $agentcode = $request->id;

        $numberOfAgent = \App\Models\User::select("users.*")
        ->where('users.id', '!=', auth()->user()->id)
            ->when($agentcode, function ($query) use ($agentcode) {
                if ($agentcode == 'CC3') {
                    // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
                    //
                    // return $query->where('users.teamleader', auth()->user()->tlid);
                    return $query->where("users.agent_code", $agentcode);
                } else {
                    // return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
                    return $query->where("users.agent_code", $agentcode);
                }
                // else if($myrole == 'KHICordination'){
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
                // else {
                //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
                // }
            })
            ->whereIn("users.role", array('sale', 'NumberAdmin'))
            // ->where("users.role",'NumberAdmin')
            ->get();
            $id = $request->id;
            return view('dashboard.floor-dashboard-master',compact('numberOfAgent', 'id'));
    }
    //
    public function verificationdept(Request $request){
        // $numberOfAgent = \App\Models\User::select("users.*")
        // ->where('users.id', '!=', auth()->user()->id)
        //     // ->when($myrole, function ($query) use ($myrole) {
        //     //     if ($myrole == '1') {
        //     //         // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
        //     //         //
        //     //         return $query->where('users.agent_code', auth()->user()->agent_code);
        //     //     } else {
        //     //         return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
        //     //     }
        //     //     // else if($myrole == 'KHICordination'){
        //     //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
        //     //     // }
        //     //     // else {
        //     //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
        //     //     // }
        //     // })
        //     ->where("users.agent_code", $request->id)
        //     ->whereIn("users.role", array('sale', 'NumberAdmin'))
        //     // ->where("users.role",'NumberAdmin')
        //     ->get();
        //     $id = $request->id;
            $data = \App\Models\User::whereIn('role', ['Verification', 'NumberVerification'])
            ->where('email', '!=', 'verification@verification.com')
            ->get();
            return view('dashboard.verification-dept',compact('data'));
    }
    //
 // ITProduct END
    public function index(Request $request)
    {

        Session::put('call_center', auth()->user()->call_center);
        // return auth()->user()->role;
        if(auth()->user()->role == 'Emirate Coordinator'){
            $channel_partner = channel_partner::where('status', '1')->get();
            return view('dashboard.emirate-cordination-dashboard',compact('channel_partner'));
        }
        if(auth()->user()->role =='MWHGUEST'){
            // return "Zoom";
            $slug = 'MWH';
            $simtype = $request->simtype;
            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                ->wherein('numberdetails.channel_type', ['MWH'])
                ->groupBy('numberdetails.type')
                ->get();
            if ($request->ajax()) {
                //
                $role = auth()->user()->role;
                // $r = numberdetail::select('numberdetails.type')->where('status', 'Available')->groupBy('numberdetails.type')->get();
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity')
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                // ->where("numberdetails.call_center", $request->simtype)
                ->whereIn("numberdetails.channel_type", ['MWH'])
                ->where("numberdetails.status", 'Available')
                ->latest()
                ->get();
                // return $data;
                // $data = \App\Models\numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                // ->LeftJoin(
                //     'channel_partners',
                //     'channel_partners.name',
                //     '=',
                //     'numberdetails.channel_type',
                //     // 'numberdetails.id'
                // )
                // ->where("numberdetails.status", 'Available')
                // // ->limit(8)
                // ->get();
                // $data = \App\Models\User::latest()->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        // onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')"
                        //    $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='BookNum('".trim($row['id'])."')'> View </a>";
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="BookNum(' . trim($row['id']) . ',' . $row['number'] . ')">Book Number</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('dashboard.mwhnumber', compact('q', 'slug', 'simtype','mychannel'));
            //
        }
        if(auth()->user()->role == 'ExpressGuest'){
            // return "Zoom";
            // $slug = 'ExpressDial';
            $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->first();
            $simtype = $request->simtype;
            $q = numberdetail::select("numberdetails.type")
                // ->where("remarks.user_agent_id", auth()->user()->id)
                // ->where("numberdetails.id", $request->id)
                ->where("numberdetails.status", 'Available')
                -> whereIn('numberdetails.channel_type', $mychannel)
                // ->wherein('numberdetails.channel_type', ['MWH'])
                ->groupBy('numberdetails.type')
                ->get();
            if ($request->ajax()) {
                //
                $role = auth()->user()->role;
                // $r = numberdetail::select('numberdetails.type')->where('status', 'Available')->groupBy('numberdetails.type')->get();
                $data = numberdetail::select("numberdetails.number", "numberdetails.type", 'numberdetails.id', 'numberdetails.channel_type', 'numberdetails.identity','numberdetails.passcode')
                    // ->where("remarks.user_agent_id", auth()->user()->id)
                    // ->where("numberdetails.id", $request->id)
                    // ->where("numberdetails.call_center", $request->simtype)
                    ->whereIn('numberdetails.channel_type', $mychannel)
                // ->whereIn("numberdetails.channel_type", ['ExpressDial'])
                ->where("numberdetails.status", 'Available')
                ->latest()
                ->get();
                // return $data;
                // $data = \App\Models\numberdetail::select("numberdetails.number","numberdetails.type",'numberdetails.id','numberdetails.channel_type','numberdetails.identity')
                // ->LeftJoin(
                //     'channel_partners',
                //     'channel_partners.name',
                //     '=',
                //     'numberdetails.channel_type',
                //     // 'numberdetails.id'
                // )
                // ->where("numberdetails.status", 'Available')
                // // ->limit(8)
                // ->get();
                // $data = \App\Models\User::latest()->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        // onclick="BookNum('{{$item->id}}','{{route('ajaxRequest.BookNum')}}','{{$item->channel_type}}','{{$item->number}}','{{'home'}}')"
                        //    $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm' onclick='BookNum('".trim($row['id'])."')'> View </a>";
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="BookNum(' . trim($row['id']) . ',' . $row['number'] . ')">Book Number</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            // return $mychannel;
            return view('dashboard.mwhnumber', compact('q', 'simtype','mychannel'));
            //
        }
        if(auth()->user()->role == 'FloorManager'){
            // $channel_partner = channel_partner::where('status', '1')->get();
            $numberOfAgent = \App\Models\User::select("users.*")
            ->where('users.id', '!=', auth()->user()->id)
            ->where("users.agent_code", auth()->user()->agent_code)
            ->whereIn("users.role", array('sale', 'NumberAdmin'))
            // ->where("users.role",'NumberAdmin')
            ->get();
            return view('dashboard.floor-dashboard',compact('numberOfAgent'));
        }
        if(auth()->user()->role == 'TeamLeader'){
            // $channel_partner = channel_partner::where('status', '1')->get();
            $numberOfAgent = \App\Models\User::select("users.*")
            ->where('users.id', '!=', auth()->user()->id)
            ->where('users.teamleader',auth()->user()->id)
            ->where("users.agent_code", auth()->user()->agent_code)
            ->whereIn("users.role", array('sale', 'NumberAdmin'))
            // ->where("users.role",'NumberAdmin')
            ->get();
            return view('dashboard.floor-dashboard',compact('numberOfAgent'));
        }
        if(auth()->user()->role  == 'FloorManagerHead'){
             $myrole = auth()->user()->multi_agentcode;
            // $channel_partner = channel_partner::where('status', '1')->get();
            // $numberOfAgent = \App\Models\User::select("users.*")
            // ->where('users.id', '!=', auth()->user()->id)
            // ->when($myrole, function ($query) use ($myrole) {
            //     if ($myrole == '1') {
            //         // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            //         //
            //         return $query->where('users.agent_code', auth()->user()->agent_code);
            //     } else {
            //         return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
            //     }
            //     // else if($myrole == 'KHICordination'){
            //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
            //     // }
            //     // else {
            //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
            //     // }
            // })
            // // ->where("users.agent_code", auth()->user()->agent_code)
            // ->whereIn("users.role", array('sale', 'NumberAdmin'))
            // // ->where("users.role",'NumberAdmin')
            // ->get();
            return view('dashboard.master-floor-dashboard',compact('myrole'));
        }
        if(auth()->user()->role == 'TeamHire'){
            // $channel_partner = channel_partner::where('status', '1')->get();
            return view('dashboard.hr-dashboard');
        }
        if(auth()->user()->role == 'PrepaidChecker'){
                return view('dashboard.prepaid');
            // $channel_partner = channel_partner::where('status', '1')->get();
            // return view('dashboard.emirate-cordination-dashboard',compact('channel_partner'));
        }
        if(auth()->user()->role == 'AttendanceManager'){
            // $channel_partner = channel_partner::where('status', '1')->get();

        if(auth()->user()->agent_code == 'CC1'){
            $call_center = \App\Models\call_center::where('status','1')
            ->whereIn('call_center_name',['CC1'])
            ->get();
        }
        else if(auth()->user()->agent_code == 'CC5'){
            $call_center = \App\Models\call_center::where('status','1')
            ->whereIn('call_center_name',['CC5'])
            ->get();
        }else{

            $call_center = \App\Models\call_center::where('status','1')
            ->whereIn('call_center_name',['CC4','CC8','CC7','CC5'])
            ->get();
        }
            // $users = User::where('agent_code',auth()->user()->agent_code)->get();
            return view('dashboard.attendance-dashboard',compact('call_center'));
        }
        if(auth()->user()->role == 'Multi Coordinator'){
            // return "Zoom Zoom";
            $channel_partner = channel_partner::where('status', '1')->get();
            $user = auth()->user();
            $permissions = $user->getAllPermissions();
            return view('dashboard.main-coordination-dashboard',compact('channel_partner', 'permissions'));
        }
        if(auth()->user()->role == 'TESTER'){
            // return "SMS";
            $data = activation_form::select('activation_forms.*', 'audio_recordings.audio_file', 'plans.plan_name')
                ->LeftJoin('users', 'users.id', 'activation_forms.saler_id')
                ->LeftJoin('plans', 'plans.id', 'activation_forms.select_plan')
                ->LeftJoin('audio_recordings', 'audio_recordings.lead_no', 'activation_forms.lead_id')
                ->where('activation_forms.status', '1.02')
                ->whereNULL('remarks')
                // ->where('activation_forms.lead_id', $id)
                ->get();
            return view('dashboard.test-dashboard', compact('data'));
        }
        // return
        if(auth()->user()->role == 'Guest'){
            // $data = numberdetail
            //    return
            $agent_code = auth()->user()->agent_code;
            if (auth()->user()->region == 'Pak') {
                // return "Zoom";
                $r = numberdetail::select('numberdetails.type')->where('status', 'Available')->groupBy('numberdetails.type')->get();
                $data = \App\Models\numberdetail::select("numberdetails.*")
                // ->LeftJoin(
                //     'numberdetails',
                //     'numberdetails.id',
                //     '=',
                //     'choosen_numbers.number_id',
                //     'numberdetails.id'
                // )
                ->LeftJoin(
                    'channel_partners',
                    'channel_partners.name',
                    '=',
                    'numberdetails.channel_type',
                    // 'numberdetails.id'
                )
                ->where("numberdetails.status", 'Available')
                    // ->where("channel_partners.status", 1)
                    // ->where("numberdetails.channel_type", 'TTF')
                    // ->when($agent_code, function ($query) use ($agent_code) {
                    //     if ($agent_code == 'CC10') {
                    //         // return $query->whereIn('lead_locations.assign_to', ['136']);
                    //         return $query->whereIn('numberdetails.channel_type', ['TTF']);
                    //     } else {
                    //         return $query->whereIn('numberdetails.channel_type', ['ConnectCC']);
                    //     }
                    // })
                    ->when($agent_code, function ($query) use ($agent_code) {
                        if ($agent_code == 'CC10') {
                            // return $query->whereIn('lead_locations.assign_to', ['136']);
                            return $query->whereIn('numberdetails.channel_type', ['TTF']);
                            return $query->whereIn('numberdetails.identity', ['TFE22']);
                        } else {
                            return $query->whereIn('numberdetails.channel_type', ['ConnectCC']);
                        }
                    })
                    ->where(function ($query) use ($agent_code) {
                        $query->where('call_center', $agent_code)
                            ->orWhere('call_center', 'default');
                            // ->where('region', auth()->user()->region);

                    })
                // ->take(1000)
                // ->where('choose_n')
                // ->where("numberdetails.channel_type", $channel)
                ->get();
            } else {
                // return "z";
                $r = numberdetail::select('numberdetails.type')->where('status', 'Available')->groupBy('numberdetails.type')->get();
                $data = \App\Models\numberdetail::select("numberdetails.*")
                // ->LeftJoin(
                //     'numberdetails',
                //     'numberdetails.id',
                //     '=',
                //     'choosen_numbers.number_id',
                //     'numberdetails.id'
                // )
                ->LeftJoin(
                    'channel_partners',
                    'channel_partners.name',
                    '=',
                    'numberdetails.channel_type',
                    // 'numberdetails.id'
                )
                ->where("numberdetails.status", 'Available')
                // ->where("channel_partners.status", 1)
                // ->where("numberdetails.channel_type", 'TTF')
                    // ->whereNull('numberdetails.region')
                    ->when($agent_code, function ($query) use ($agent_code) {
                        if ($agent_code == 'CC10') {
                            // return $query->whereIn('lead_locations.assign_to', ['136']);
                            return $query->whereIn('numberdetails.channel_type', ['TTF'])
                            ->OrwhereIn('numberdetails.identity', ['TFE22','GLD2MT22', 'SS2MT22']);
                        } else {
                            return $query->whereIn('numberdetails.channel_type', ['ConnectCC']);
                        }
                    })
                    ->where(function ($query) use ($agent_code) {
                        $query->where('call_center', $agent_code)
                            ->orWhere('call_center', 'default');
                            // ->where('region', auth()->user()->region);

                    })
                // ->take(1000)
                // ->where('choose_n')
                // ->where("numberdetails.channel_type", $channel)
                ->get();

            }
            return view('number.guest-number',compact('data','r'));
        }
        // return auth()->user()->role;
        // return user::find(1)->authentications;
        // return User::find(1)->lastLoginAt();
        // $user = User::find(1);
        // return $user->isOnline();

        // return $user_ip_address = $request->ip();
        // if()
        // if(Auth::user()->hasRole('sale'))
        // {
        //     // return "Hoe";
        //     // do something
        // }
        // if(auth()->user()->agent_code == 'ARF'){
        //     return redirect(route('number-system.index'));
        // }
        if(auth()->user()->agent_code == 'ARF' || auth()->user()->role == 'NumberCord' || auth()->user()->role == 'NumberActivation'){
            return redirect(route('number-system-ttf','OCP1'));
        }
        // $permission = Permission::findById(6);
        // $role = Role::findById(18);
        // $role->givePermissionTo($permission);
        // auth()->user()->givePermissionTo('manage postpaid');
        // return auth()->user()->role;
        // return $role = Role::all();
        // return $permission = Permission::all();
        // $permission->removeRole($role);
        // $role->revokePermissionTo($permission);
        // $role->revokePermissionTo($permission);
        // $role = Role::findById(15);
        // Permissions::create([''])
        // Permission::create(['name'=> 'manage reporting']);
        // Role::create(['name'=> 'SpecialVerification']);
        // auth()->user()->givePermissionTo('manage elife');
        // auth()->user()->givePermissionTo('manage itproducts');
        // auth()->user()->assignRole('Sale');
        // return auth()->user()->permissions;
        // drakify('error'); // for success alert
        // return auth()->user()->role;
        // return "ROle" . auth()->user()->agent_code;
        if(auth()->user()->role == 'Manager' || auth()->user()->role == 'NumberSuperAdmin' || auth()->user()->role == 'Elife Manager'){
            $numberOfAgent = \App\Models\User::select("users.*")
            ->where('users.id', '!=', auth()->user()->id)
            ->where("users.agent_code",auth()->user()->agent_code)
            ->whereIn("users.role",array('sale','NumberAdmin'))
            // ->where("users.role",'NumberAdmin')
            ->get();

            $postpaid_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.02')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $elife_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.02')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $it_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.02')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $postpaid_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $elife_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $it_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $postpaid_rejected = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.04')
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $elife_rejected = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.04')
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $it_rejected = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.04')
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $postpaid_follow_up = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $elife_follow_up = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $it_follow_up = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $postpaid_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $elife_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $it_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
                $channel_partner = channel_partner::where('status','1')->get();
            // return view('dashboard.home-dashboard');
                return view('dashboard.manager.home-dashboard', compact('postpaid_activation', 'elife_activation', 'it_activation', 'postpaid_activation', 'elife_verified', 'it_verified', 'postpaid_rejected', 'elife_rejected', 'it_rejected', 'postpaid_follow_up', 'elife_follow_up', 'it_follow_up', 'postpaid_pending', 'elife_pending', 'it_pending', 'postpaid_verified','numberOfAgent','channel_partner'));

        }
        else if(auth()->user()->role == 'sale' || auth()->user()->role == 'NumberAdmin' || auth()->user()->role == 'FloorManager'){
            // return "s";
            // return auth()->user()->agent_code;


            $channel_partner = channel_partner::where('status', '1')->get();

                return view('agent.sale-dashboard',compact('channel_partner'));
        }
        else if(auth()->user()->role == 'Cordination'){
            $cordination_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->leftJoin(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->leftJoin(
                    'verification_forms',
                    'verification_forms.lead_no',
                    '=',
                    'lead_sales.id'
                )
                // ->where('users.id', auth()->user()->id)
                ->whereIn('lead_sales.status', ['1.07','1.16'])
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $cordination_complete = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->leftJoin(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
                $emirates = emirate::wherestatus('1')->get();
                $activation_users = User::role('activation')->get();
            $channel_partner = channel_partner::where('status', '1')->get();

                return view('dashboard.cordination-dashboard', compact('cordination_complete', 'cordination_pending','emirates', 'activation_users','channel_partner'));
        }
        else if(auth()->user()->role == 'MainCoordinator'){
            // $cordination_pending = \App\Models\User::select("users.id")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->leftJoin(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->leftJoin(
            //         'verification_forms',
            //         'verification_forms.lead_no',
            //         '=',
            //         'lead_sales.id'
            //     )
            //     // ->where('users.id', auth()->user()->id)
            //     ->where('lead_sales.status', '1.07')
            //     ->where('lead_sales.lead_type', 'postpaid')
            //     // ->where('users.agent_code', auth()->user()->agent_code)
            //     ->count();
            // $cordination_complete = \App\Models\User::select("users.id")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->leftJoin(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     // ->where('users.id', auth()->user()->id)
            //     ->where('lead_sales.status', '1.10')
            //     ->where('lead_sales.lead_type', 'postpaid')
            //     // ->where('users.agent_code', auth()->user()->agent_code)
            //     ->count();
            //     $emirates = emirate::wherestatus('1')->get();
            //     $activation_users = User::role('activation')->get();
                return view('coordination.coordination-dashboard');
        }
        else if(auth()->user()->role == 'Activation' || auth()->user()->role == 'Elife Active'){
            // $cordination_pending = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'timing_durations',
            //         'timing_durations.lead_no',
            //         '=',
            //         'verification_forms.lead_no'
            //     )
            //     ->Join(
            //         'remarks',
            //         'remarks.lead_no',
            //         '=',
            //         'verification_forms.lead_no'
            //     )
            //     ->Join(
            //         'status_codes',
            //         'status_codes.status_code',
            //         '=',
            //         'verification_forms.status'
            //     )
            //     ->where('verification_forms.status', '1.10')
            //     ->where('verification_forms.assing_to', auth()->user()->id)
            //     // ->where('verification_forms.emirate_location', auth()->user()->emirate)
            //     // ->groupBy('verification_forms.lead_no')
            //     ->count();

            $cordination_pending = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.lat", "lead_locations.lng")
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
                // ->Join(
                //     'remarks',
                //     'remarks.lead_no',
                //     '=',
                //     'verification_forms.lead_no'
                // )
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
                ->where('lead_sales.status', '1.10')
                ->where('lead_locations.assign_to', auth()->user()->id)
                // ->where('verification_forms.emirate_location', auth()->user()->emirate)
                // ->groupBy('verification_forms.lead_no')
                ->count();
            // $cordination_complete =
            $cordination_complete = \App\Models\activation_form::select('activation.*')
            ->where('activation_sold_by',auth()->user()->id)
            ->count();
            // $cordination_complete = \App\Models\User::select("users.id")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->leftJoin(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     // ->where('users.id', auth()->user()->id)
            //     ->where('lead_sales.status', '1.02')
            //     ->where('lead_sales.lead_type', 'postpaid')
            //     ->where('users.agent_code', auth()->user()->agent_code)
            //     ->count();
            $reverify_lead = \App\Models\User::select("users.id")
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
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
                return view('dashboard.activation-dashboard', compact('cordination_complete', 'cordination_pending', 'reverify_lead'));
        }
        else if(auth()->user()->role == 'Verification' || auth()->user()->role == 'NumberVerification' || auth()->user()->role == 'elif-Verification'){
             $postpaid_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'postpaid')
                // ->where('users.agent_code', auth()->user()->agent_code)
                ->count();

            $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'postpaid')
                // ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            $total_re_verify = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.13')
                ->where('lead_sales.lead_type', 'postpaid')
                // ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            //
                $user = auth()->user();
                // $channel_partner = ['']
                // $channel_partner = $user->getAllPermissions();
            // $channel_partner = channel_partner::where('status', '1')->get();

                return view('verification.verification-dashboard');

                //
        }
        else if(auth()->user()->role == 'Admin' || auth()->user()->role == 'SuperAdmin'){
            // return "yes";
            return view('admin.home-dashboard');
        }
        else if(auth()->user()->role == 'Trainer'){
            // return "yes";
            // return view('admin.home-dashboard');
            return view('trainer.home-dashboard');
        }
        else if(auth()->user()->role == 'NumberController')
        {
            // return "S";
            $r = numberdetail::select('numberdetails.type')->where('status','Available')->groupBy('numberdetails.type')->get();
            return view('admin.number-dashboard',compact('r'));
        }
        else if(auth()->user()->role == 'Uploader')
        {
            // return "S";
            // $data = \App\Models\UploaderDataBank::select('prefix_number', \DB::raw('count(*) as total'))->groupBy('prefix_number')->get();
            // $data2 = \App\Models\UploaderDataBank::select('prefix_an', \DB::raw('count(*) as total'))->groupBy('prefix_an')->get();
            return view('dashboard.uploader-dashboard');
            // $r = numberdetail::select('numberdetails.type')->where('status','Available')->groupBy('numberdetails.type')->get();
            // return view('admin.number-dashboard',compact('r'));
        }
        else{
            // return "zoom"
            $postpaid_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.02')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
                if ($postpaid_activation > 0) {
                    emotify('success', 'Hi, Congrats you have some Postpaid activate Leads, have a nice day');
                }
            $elife_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.02')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($elife_activation > 0) {
                emotify('success', 'Hi, Congrats you have some Elife activate Leads, have a nice day');
            }
            $it_activation = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.02')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($it_activation > 0) {
                emotify('success', 'Hi, Congrats you have some IT activate Leads, have a nice day');
            }
            // $postpaid_activation = \App\Models\User::select("users.id")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->where('users.id', auth()->user()->id)
            //     ->where('lead_sales.status', '1.07')
            //     ->where('lead_sales.lead_type', 'postpaid')
            //     ->where('users.agent_code', auth()->user()->agent_code)
            //     ->count();
            $postpaid_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($postpaid_verified > 0) {
                emotify('success', 'Hi, Congrats you have some Postpaid Verified Leads, have a nice day');
            }
            $elife_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($elife_verified > 0) {
                emotify('success', 'Hi, Congrats you have some Elife Verified Leads, have a nice day');
            }
            $it_verified = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.07')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($it_verified > 0) {
                emotify('success', 'Hi, Congrats you have some IT Verified Leads, have a nice day');
            }
            $postpaid_rejected = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.04')
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($postpaid_rejected > 0) {
                emotify('success', 'Hi, oops your lead has been rejected Kindly check reject section, have a nice day');
            }
            $elife_rejected = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.04')
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($elife_rejected > 0) {
                emotify('success', 'Hi, oops your Elife lead has been rejected Kindly check reject section, have a nice day');
            }
            $it_rejected = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', '1.04')
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($it_rejected > 0) {
                emotify('success', 'Hi, oops your IT lead has been rejected Kindly check reject section, have a nice day');
            }
            $postpaid_follow_up = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
                if($postpaid_follow_up > 0){
                    emotify('success', 'Hi, You have some postpaid Follow Up Leads, Have a Nice Day');
                }
            $elife_follow_up = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($elife_follow_up > 0) {
                emotify('success', 'Hi, You have some Elife Follow Up Leads, Have a Nice Day');
            }
            $it_follow_up = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.03')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($it_follow_up > 0) {
                emotify('success', 'Hi, You have some IT Follow Up Leads, Have a Nice Day');
            }
            $postpaid_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($postpaid_pending > 0) {
                emotify('success', 'Hi, You have some Postpaid Pending Leads, Have a Nice Day');
            }
            $elife_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($elife_pending > 0) {
                emotify('success', 'Hi, You have some Elife Pending Leads, Have a Nice Day');
            }
            $it_pending = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.01')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($it_pending > 0) {
                emotify('success', 'Hi, You have some Elife In Process Leads, Have a Nice Day');
            }
            $postpaid_in_process = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->where('lead_sales.lead_type', 'postpaid')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($postpaid_pending > 0) {
                emotify('success', 'Hi, You have some Postpaid In Process Leads, Have a Nice Day');
            }
            $elife_in_process = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->where('lead_sales.lead_type', 'Elife')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($elife_pending > 0) {
                emotify('success', 'Hi, You have some Elife In Process Leads, Have a Nice Day');
            }
            $it_in_process = \App\Models\User::select("users.id")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.id', auth()->user()->id)
                ->where('lead_sales.status', '1.10')
                ->where('lead_sales.lead_type', 'ITProducts')
                ->where('users.agent_code', auth()->user()->agent_code)
                ->count();
            if ($it_pending > 0) {
                emotify('success', 'Hi, You have some Elife Pending Leads, Have a Nice Day');
            }
                return view('dashboard.home-dashboard',compact('postpaid_activation', 'elife_activation', 'it_activation', 'postpaid_activation', 'elife_verified', 'it_verified', 'postpaid_rejected', 'elife_rejected', 'it_rejected', 'postpaid_follow_up', 'elife_follow_up', 'it_follow_up', 'postpaid_pending', 'elife_pending', 'it_pending','postpaid_verified', 'it_in_process', 'elife_in_process', 'postpaid_in_process'));
        }
    }
    public function feedback(){
        $data = CustomerFeedBack::all();
        return view('dashboard.feedback',compact('data'));
    }
    public static function paidlead_monthly_agent($postpaid, $channel){
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
            ->where('activation_forms.pay_status', 'Paid')
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.agent_code', auth()->user()->agent_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function freelead_monthly_agent($postpaid, $channel){
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
            ->where('activation_forms.pay_status', 'Free')
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.agent_code', auth()->user()->agent_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function Combinefreelead_monthly_agent($postpaid, $channel,$agent_code){
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
            ->where('activation_forms.pay_status', 'Free')
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.agent_code', $agent_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function paidlead_daily_agent($postpaid, $channel){
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
            ->where('activation_forms.pay_status', 'Paid')
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.agent_code', auth()->user()->agent_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
    }
    public static function freelead_daily_agent($postpaid, $channel){
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
            ->where('activation_forms.pay_status', 'Free')
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.agent_code', auth()->user()->agent_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
    }
    public static function Combinefreelead_daily_agent($postpaid, $channel,$agent_code){
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
            ->where('activation_forms.pay_status', 'Free')
            ->whereIn('lead_sales.channel_type', ['ConnectCC'])
            ->where('lead_sales.lead_type', 'postpaid')
            ->where('users.agent_code', $agent_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
    }
    public static function mytarget()
    {
        // $r = TargetAssignerManager::select('target_assigner_managers.target')
        // ->LeftJoin(
        //     'call_centers',
        //     'call_centers.id',
        //     'target_assigner_managers.call_center_id'
        // )
        // ->where('call_centers.call_center_code', auth()->user()->agent_code)
        // ->where('target_assigner_managers.month', Carbon::now()->month)
        // ->first();
        // $r = Target

        $r = TargetAssignerUser::select('target_assigner_users.target')
        ->LeftJoin(
            'users',
            'users.id',
            'target_assigner_users.user'
        )
            // ->where('agent_targets.sim_type', $type)
            ->where('users.agent_code', auth()->user()->agent_code)
            ->where('target_assigner_users.month', Carbon::now()->month)
            ->whereNull('users.deleted_at')
            ->where('users.role','Sale')
            ->sum('target_assigner_users.target');
        if ($r) {
            return $r;
        } else {
            return "0";
        }
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
            ->first();
            // ->sum('target_assigner_managers.target');
        // return $r->
        // $count =
        if ($r) {
            return $r->target;
        } else {
            return "0";
        }
    }
    public static function AgentTarget()
    {
        $r = TargetAssignerUser::select('target_assigner_users.target')
        ->LeftJoin(
            'users',
            'users.id',
            'target_assigner_users.user'
        )
            // ->where('agent_targets.sim_type', $type)
            ->where('users.id', auth()->user()->id)
            ->where('target_assigner_users.month', Carbon::now()->month)
            ->first();
        // return $r->
        // $count =
        if ($r) {
            return $r->target;
        } else {
            return "0";
        }
    }
    public static function my_group_daily_unassigned($lead_type){
        $myrole = auth()->user()->multi_agentcode;
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
                'users','users.id','lead_sales.saler_id'
            )
            ->where('verification_forms.status', '1.10')
            ->where('lead_locations.assign_to', '=', 136)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.lead_type', $lead_type)
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
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function channel_wise($mychannel){
        // $myrole = auth()->user()->multi_agentcode;
        // return $operation = verification_form::select("verification_forms.lead_no")
        //     ->LeftJoin(
        //         'lead_sales',
        //         'lead_sales.id',
        //         '=',
        //         'verification_forms.lead_no'
        //     )
        //     // ->LeftJoin(
        //     //     'users',
        //     //     'users.id',
        //     //     '=',
        //     //     'lead_sales.saler_id'
        //     // )
        //     ->LeftJoin(
        //         'lead_locations',
        //         'lead_locations.lead_id',
        //         '=',
        //         'lead_sales.id'
        //     )
        //     ->Join(
        //         'users','users.id','lead_sales.saler_id'
        //     )
        //     ->where('verification_forms.status', '1.10')
        //     ->where('lead_locations.assign_to', '=', 136)
        //     ->where('lead_sales.channel_type',$channel)
        //     ->whereDate('lead_sales.updated_at', Carbon::today())
        //     // ->where('lead_sales.lead_type', $lead_type)
        //     ->when($myrole, function ($query) use ($myrole) {
        //         if ($myrole == '1') {
        //             // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
        //             //
        //             return $query->where('users.agent_code', auth()->user()->agent_code);
        //         } else {
        //             return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
        //         }
        //         // else if($myrole == 'KHICordination'){
        //         //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
        //         // }
        //         // else {
        //         //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
        //         // }
        //     })
        //     // ->where('users.agent_code', auth()->user()->agent_code)
        //     ->groupBy('verification_forms.lead_no')
        //     ->get()
        //     ->count();
        // $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
        // ->whereIn('lead_sales.channel_type',$mychannel)
        // return $mychannel;
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
            ->whereIn('lead_sales.channel_type', [$mychannel])
            // ->where('verification_forms.status', '1.10')
            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
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
    public static function MixUnAssigned($lead_type){
        $myrole = auth()->user()->multi_agentcode;
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
                'users','users.id','lead_sales.saler_id'
            )
            ->where('lead_sales.status', '1.10')
            ->where('lead_locations.assign_to', '=', 136)
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.lead_type', $lead_type)
            // ->when($myrole, function ($query) use ($myrole) {
            //     if ($myrole == '1') {
            //         // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
            //         //
            //         return $query->where('users.agent_code', auth()->user()->agent_code);
            //     } else {
            //         return $query->whereIn('users.agent_code', explode(',', auth()->user()->multi_agentcode));
            //     }
            //     // else if($myrole == 'KHICordination'){
            //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
            //     // }
            //     // else {
            //     //     return $query->whereIn('users.agent_code', ['CC1', 'CC4', 'CC5', 'CC7', 'CC8']);
            //     // }
            // })
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function emirate_group_daily_unassigned($lead_type){
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
                'users','users.id','lead_sales.saler_id'
            )
            ->where('verification_forms.status', '1.10')
            ->where('lead_locations.assign_to', '=', 136)
            ->whereIn('lead_sales.emirates', explode(',',auth()->user()->emirate))
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.lead_type', $lead_type)
            // ->where('users.agent_code', auth()->user()->agent_code)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function combine_group_daily_unassigned($lead_type,$agent_code){
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
                'users','users.id','lead_sales.saler_id'
            )
            ->where('verification_forms.status', '1.10')
            ->where('lead_locations.assign_to', '=', 136)
            ->whereDate('lead_sales.updated_at', Carbon::today())
            // ->where('lead_sales.lead_type', $lead_type)
            ->where('users.agent_code', $agent_code)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function my_group_daily_assigned($lead_type){
        $myrole = auth()->user()->multi_agentcode;
        // return "Salman";

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
            // ->where('lead_sales.channel_type', 'TTF')
            ->where('lead_locations.assign_to', '!=', 136)
            ->where('lead_sales.status', '1.10')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->orderBy('lead_sales.updated_at', 'desc')
            ->get()->count();

    }
    public static function my_group_yesterday_assigned($lead_type){
        // return "9"
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');

        return
        $operation = verification_form::select("verification_forms.lead_no")
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            // ->Join(
            //     'lead_locations',
            //     'lead_locations.lead_id',
            //     '=',
            //     'lead_sales.id'
            // )
            ->whereIn('lead_sales.channel_type', $mychannel)

        ->whereIn('lead_sales.status', ['1.10', '1.21'])
        // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
        ->whereDate('lead_sales.updated_at', Carbon::yesterday())
        // ->where('users.agent_code', auth()->user()->agent_code)
        // ->where('verification_forms.assing_to', auth()->user()->id)
        // ->where('verification_forms.emirate_location', auth()->user()->emirate)
        ->groupBy('verification_forms.lead_no')
        ->get()->count();

    }
    public static function emirate_my_group_yesterday_assigned($lead_type){
        // return "9"
        $mychannel = \App\Models\AssignChannel::select('name')->where('userid', auth()->user()->id)->pluck('name');
            // ->whereIn('lead_sales.channel_type',$mychannel)
        return
        $operation = verification_form::select("verification_forms.lead_no")
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     '=',
            //     'lead_sales.saler_id'
            // )
            // ->Join(
            //     'lead_locations',
            //     'lead_locations.lead_id',
            //     '=',
            //     'lead_sales.id'
            // )
        ->whereIn('lead_sales.channel_type', $mychannel)
        ->whereIn('lead_sales.status', ['1.10', '1.21'])
        // ->where('lead_locations.assign_to', '!=', auth()->user()->id)
        ->whereDate('lead_sales.updated_at', Carbon::yesterday())
            // ->whereIn('lead_sales.emirates', explode(',', auth()->user()->emirate))
        // ->where('users.agent_code', auth()->user()->agent_code)
        // ->where('verification_forms.assing_to', auth()->user()->id)
        // ->where('verification_forms.emirate_location', auth()->user()->emirate)
        ->groupBy('verification_forms.lead_no')
        ->get()->count();

    }
    public static function combine_group_daily_assigned($lead_type,$agent_code){
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
            ->where('users.agent_code', $agent_code)
            // ->where('lead_sales.channel_type', 'TTF')
            ->where('lead_locations.assign_to', '!=', 136)
            ->where('lead_sales.status', '1.10')
            ->whereDate('lead_sales.updated_at', Carbon::today())
            ->orderBy('lead_sales.updated_at', 'desc')
            ->get()->count();

    }
    public static function my_group_daily_unassigned_monthly($lead_type){
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
                'users','users.id','lead_sales.saler_id'
            )
            ->where('verification_forms.status', '1.10')
            ->where('lead_locations.assign_to', '=', 136)
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('lead_sales.lead_type', $lead_type)
            ->where('users.agent_code', auth()->user()->agent_code)
            ->groupBy('verification_forms.lead_no')
            ->get()
            ->count();
    }
    public static function my_group_daily_assigned_monthly($lead_type){
            return  $a = lead_sale::select('lead_sales.id')
            ->Join(
                'lead_locations','lead_locations.lead_id','lead_sales.id'
            )
            ->Join(
                'users','users.id','lead_sales.saler_id'
            )
            ->where('users.agent_code',auth()->user()->agent_code)
            ->whereIn('lead_sales.status',['1.10','1.21'])
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            ->get()->count();
    }
    //
     public static function my_timeout_lead(){
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to",'lead_sales.appointment_to', 'appointment_from')
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
            ->where('users.agent_code',auth()->user()->code)
            // ->where('appointment_to', '<', Carbon::now())
            ->where('appointment_from', '<', Carbon::now())

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
    //
        public static function my_appointment(){
        // return $cd =  date('m/d/y H:i:s', strtotime(Carbon::now()));
        // $cd =
        // return Carbon::now();
        return $operation = verification_form::select("verification_forms.lead_no", "timing_durations.lead_generate_time", "verification_forms.*", "remarks.remarks as latest_remarks", "status_codes.status_name", "lead_locations.assign_to",'lead_sales.appointment_to', 'appointment_from')
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
            ->where('users.agent_code',auth()->user()->agent_code)
            // ->where('appointment_from', '>=', Carbon::now())
            // ->where('appointment_from', '=<', Carbon::now())
            // ->where('appointment_to', '>=', Carbon::now())
            // ->where('appointment_to', '=<', Carbon::now())
            ->where('appointment_from', '<', Carbon::now())

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
    //
    public static function appointment_lead_count()
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
            ->where('appointment_from', '<', Carbon::now())

            // ->where('appointment_from', '>=', Carbon::now())
            // ->where('appointment_to', '=<', Carbon::now())
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
    public static function later_today()
    {
        $myrole = auth()->user()->multi_agentcode;

        if (auth()->user()->role == 'MainCoordinator') {
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
                // ->where('lead_locations.assign_to', '=', auth()->user()->id)
                ->whereDate('lead_sales.later_date', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()
                ->count();
        }
    }
    public static function all_later()
    {
        if (auth()->user()->role == 'MainCoordinator') {
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
                // ->whereDate('lead_sales.updated_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()
                ->count();
        }
    }
    //

    public function reporthome(Request $request){
        // return "Home";
        return view('dashboard.report-dashboard');
    }
    //
    public function addleadwifi(Request $request){
        // $daring = explode('-', $id);
        // return $daring['0'] . $daring['1'];
        // $type = $daring['0'];
        // $ptype = $daring['1'];
        $countries = \App\Models\country_phone_code::all();
        $emirates = \App\Models\emirate::all();
        // $plans = \App\Models\plan::wherestatus('1')->get();
        $elifes = \App\Models\elife_plan::wherestatus('1')->get();
        // $addons = \App\Models\addon::wherestatus('1')->get();
        // $itproducts = \App\Models\it_products::wherestatus('1')->get();
        // $users = \App\Models\User::whererole('sale')->get();
        $last = \App\Models\lead_sale::latest()->first();
        $users = \App\Models\User::select("users.*")
            ->whereIn('role', array('sale', 'NumberAdmin'))
            ->where('agent_code', auth()->user()->agent_code)
            ->where('id', '!=', auth()->user()->id)
            ->get();
        // $q = numberdetail::select("numberdetails.type")
        //     ->where("numberdetails.status", "Available")
        //     ->whereIn("numberdetails.channel_type", ['TTF', 'ExpressDial'])
        //     ->groupBy('numberdetails.type')

        //     ->get();
        // $nonmembers = $users->reject(function ($users, $key) {
        //     return $users->hasRole('sale');
        // });
        // return $users->hasRole('sale')->get();
        // if (auth()->user()->role == 'TTF-SALE') {
            // return view('dashboard.add-lead-ttf', compact('countries', 'emirates', 'plans', 'elifes', 'addons', 'users', 'last', 'type', 'ptype', 'itproducts', 'q'));
        // } else {
            return view('dashboard.add-lead-wifi', compact('countries', 'emirates', 'elifes', 'users', 'last'));
        // }
    }
    //
     public function leadstorewifi(Request $request){
        //  return $request;
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
            'emirate_number' => 'required',
            'language' => 'required',
            'plan_new' => 'required',
            'area' => 'required',
            'email' => 'required|email',
            // 'selnumber' => 'required|numeric',
            // 'activation_charges_new' => 'required',
            // 'activation_rate_new' => 'required',
            'remarks_process_new' => 'required',
            // 'mytypeval.*' => 'required',
            'selnumber.*' => 'required',
            'plan_new.*' => 'required',
            // 'activation_charges_new.*' => 'required',
            // 'activation_rate_new.*' => 'required',
            'assing_to' => 'required',
            'start_date' => 'required',
            // 'start_date' => 'required_if',
            'start_time' => 'required|after:' . $ldate,

        ]);
        $leadchecker = lead_sale::where('customer_number',$request->cnumber)
        ->where('lead_sales.lead_type','HomeWifi')
        ->whereIn('status', ['1.05', '1.07', '1.08', '1.09', '1.10','1.16', '1.17', '1.19', '1.20', '1.21','1.01','1.06'])
        ->Join(
            'users','users.id','lead_sales.saler_id'
        )
        ->where('lead_sales.sim_type','New')
        ->where('users.agent_code',auth()->user()->agent_code)
        // ->whereDate('lead_sales.created_at',Carbon::now()->today())
        ->first();
        if($leadchecker){
            return response()->json(['error' => ['Documents' => ['Same Customer Number Lead Already Exist, Kindly Contact with IT Admin.']]], 200);
        }
        // if($request->nation != 'United Arab ')
        // return $request->nation;
        if($request->nation != 'United Arab Emirates'){
            // return $request->additional_document;
            if($request->additional_document == 'No Additional Document Required'){
                // return response()->json(['error' => 'Please Choose Documents']);
               return response()->json(['error' => ['Documents' => ['Documents invalid.']]], 200);
            }
            // if($request->plans == 'Gold Plus'){

            // }
            // if (strpos($request->plan_new), 'Emirati') !== FALSE) { // Yoshi version
            //         // echo "Match found";
            //         // return true;
            //    return response()->json(['error' => ['Documents' => ['Invalid Package Selection, Selected Package only allowed for UAE Citizen']]], 200);
            // }
        }
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        // $planName = $request->plan_name;
        $planName = $request->plan_new;
        $SelNumber = $request->customer_number;
        $activation_charge = 0;
        $activation_rate_new = 0;
        $test = $request->plans;
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
        $data = lead_sale::create([
            'customer_name' => $request->cname,
            'customer_number' => $request->cnumber,
            'area' => $request->area,
            'nationality' => $request->nation,
            'age' => $request->age,
            'sim_type' => $request->simtype,
            'gender' => $request->gender,
            'lead_type' => 'HomeWifi',
            'channel_type' => 'IdeaCorp',
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
            'email' => $request->email,
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
        // foreach ($request->selnumber as $key => $val) {
        //     // return $val;
        //     $damn = choosen_number::select('numberdetails.id','choosen_numbers.id as cid')
        //     ->Join(
        //         'numberdetails',
        //         'numberdetails.id',
        //         '=',
        //         'choosen_numbers.number_id'
        //     )
        //     ->where("choosen_numbers.user_id", auth()->user()->id)
        //     ->Orwhere('numberdetails.non_c','1')
        //     ->Orwhere('choosen_numbers.status','1')
        //     ->where('numberdetails.number', $val)
        //     ->first();
        //     // if($damn){
        //     //     $mycount = $damn->count();
        //     // }


        //     $count = numberdetail::select("numberdetails.id")
        //     ->where('numberdetails.number', $val)
        //     ->where('numberdetails.status','Available')
        //     ->count();
        //     if($damn){
        //         $d = numberdetail::select("numberdetails.id")
        //         ->where('numberdetails.number', $val)
        //         ->first();
        //         $k = numberdetail::findorfail($d->id);
        //         $k->status = 'Reserved';
        //         $k->book_type = '1';
        //         $k->save();
        //         $k = choosen_number::findorfail($damn->cid);
        //         $k->status = '2';
        //         // $k->book_type = '1';
        //         $k->save();
        //     }
        //     else if ($count > 0) {
        //         $d = numberdetail::select("numberdetails.id")
        //         ->where('numberdetails.number', $val)
        //         ->first();
        //         $k = numberdetail::findorfail($d->id);
        //         $k->status = 'Reserved';
        //         $k->book_type = '1';
        //         $k->save();

        //         //
        //         $k = choosen_number::create([
        //                 'number_id' => $d->id,
        //                 'user_id' => auth()->user()->id,
        //                 'status' => '1',
        //                 'book_type' => '1',
        //                 'agent_group' => auth()->user()->agent_code,
        //                 // 'ip_address' => Request::ip(),
        //                 'date_time' => Carbon::now()->toDateTimeString(),
        //             ]);
        //         // return "number has been reserved";
        //         $log = choosen_number_log::create([
        //             // 'number'
        //             'number_id' => $k->id,
        //             'user_id' => auth()->user()->id,
        //             'agent_group' => auth()->user()->agent_code,
        //         ]);
        //         //
        //     } else {
        //         $m = lead_sale::findorfail($data->id);
        //         $m->status = '1.04';
        //         $m->save();
        //         return response()->json(['error' => ['Documents' => ['Number Already Selected => ' .$val.', Please Make New Lead.']]], 200);
        //         // $d = numberdetail::select("numberdetails.id")
        //         // ->where('numberdetails.number', $val)
        //         // ->first();
        //         // $k = numberdetail::findorfail($d->id);
        //         // $k->status = 'Reserved';
        //         // $k->save();
        //         // //
        //         // $k = choosen_number::create([
        //         //         'number_id' => $k->id,
        //         //         'user_id' => auth()->user()->id,
        //         //         'status' => '1',
        //         //         'agent_group' => auth()->user()->agent_code,
        //         //         // 'ip_address' => Request::ip(),
        //         //         'date_time' => Carbon::now()->toDateTimeString(),
        //         //     ]);
        //         // // return "number has been reserved";
        //         // $log = choosen_number_log::create([
        //         //     // 'number'
        //         //     'number_id' => $k->id,
        //         //     'user_id' => auth()->user()->id,
        //         //     'agent_group' => auth()->user()->agent_code,
        //         // ]);
        //         // return $ch->id;
        //     }
        //     // return $d->id;
        //     // return "number has been reserved";
        // }
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
            \App\Models\lead_location::create([
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

        //
        notify()->success('New Sale has been submitted succesfully');
        return response()->json(['success' => 'New Sale has been submitted succesfully']);

    }
}
