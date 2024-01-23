<?php

namespace App\Http\Controllers;

use App\Models\activation_form;
use App\Models\call_center;
use App\Models\channel_partner;
use App\Models\lead_sale;
use App\Models\numberdetail;
use App\Models\plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function CallCenterReport()
    {
        $call_center = \App\Models\call_center::whereStatus('1')->get();
        return view('admin.call-center-report', compact('call_center'));
    }
    //
    public static function NumberOfAgent($AgentCode)
    {
        return $count = User::whereAgentCode($AgentCode)->count();
    }
    public static function AllLeadsCallCenter($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsCallCenterPrevious($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsCallCenterUser($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.id', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsCallCenterCombine($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            ->where('users.agent_code', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsMonthly($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsMonthlyPrevious($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsMonthlyUser($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($call_center))
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsCallCenterToday($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($call_center))
            ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsCallCenterTodayUser($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.id', trim($call_center))
            ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsCallCenterTodayCombine($call_center, $type)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            ->where('users.agent_code', trim($call_center))
            ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsToday($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($call_center))
            ->where('users.agent_code', '!=', 'AAMT')

            ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsTodayUser($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($call_center))
            ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsTodayCombine($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            // ->where('users.agent_code', trim($call_center))
            ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->count();
    }
    public static function AllLeadsMonthlyCombine($call_center, $type, $channel)
    {
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.status', $status)
            ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            // ->where('users.agent_code', trim($call_center))
            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.id', $id)
            ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)

            ->count();
    }
    public static function CalCenterLeadtypePlan($id, $status, $type, $channel)
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterLeadtypePlanPrevious($id, $status, $type, $channel)
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterLeadtypePlanUser($id, $status, $type, $channel)
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
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
    public static function CalCenterLeadtypePlanDaily($id, $status, $type, $channel)
    {
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterLeadtypePlanDailyUser($id, $status, $type, $channel)
    {
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
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
    public static function CalCenterTodayPlan($id, $status, $type, $channel)
    {
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($id))
            ->where('users.agent_code', '!=', 'AAMT')
            ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterMonthlyPlan($id, $status, $type, $channel)
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterMonthlyPlanPrevious($id, $status, $type, $channel)
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterMonthlyPlanUser($id, $status, $type, $channel, $agent_code)
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($agent_code))
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function CalCenterLeadtype($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                // ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                // ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.updated_at', Carbon::now()->year)

                ->get()->count();
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.03','1.01'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpfollowup') {
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
                // ->where('lead_sales.lead_type', $type)
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        // else if($status == 'inprocess'){
        //     return "00";
        // }
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeMNP($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                // ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.03','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('activation_forms.sim_type', 'MNP')
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        // else if($status == 'inprocess'){
        //     return "00";
        // }
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeHW($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                // ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
                        return $query->whereIn('lead_sales.channel_type', ['Ideacorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                // ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.03','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                // ->where('activation_forms.sim_type', 'HomeWifi')
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        // else if($status == 'inprocess'){
        //     return "00";
        // }
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeUser($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
                ->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial'])
                ->where('users.id', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.03','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        // else if($status == 'inprocess'){
        //     return "00";
        // }
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
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
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeCombine($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn(
                    'lead_sales.status',
                    ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                    // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02']
                )
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                // ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
            //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.03','1.01'])
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTotalMonth($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                // ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     // -> whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // return "3";
            // return $type . $channel;
            return $a = lead_sale::select('lead_sales.id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                // ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.updated_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpfollowup') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                // ->where('lead_sales.lead_type', $type)
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTotalMonthMNP($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                // ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     // -> whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // return "3";
            // return $type . $channel;
            return $a = lead_sale::select('lead_sales.id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])

                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->where('activation_forms.sim_type', 'MNP')
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTotalMonthHW($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->month)
                // ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     // -> whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // return "3";
            // return $type . $channel;
            return $a = lead_sale::select('lead_sales.id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                // ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])

                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->where('activation_forms.sim_type', 'HomeWifi')
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTotalMonthPrevious($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)
                ->groupBy('verification_forms.lead_no')

                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     // -> whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // return "3";
            // return $type . $channel;
            return $a = lead_sale::select('lead_sales.id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])

                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTotalMonthUser($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn(
                    'lead_sales.status',
                    ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                    // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
                )
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->month)
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // return "3";
            // return $type . $channel;
            return $a = lead_sale::select('lead_sales.id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])

                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                // ->whereIn('lead_sales.status', ['1.16','1.17','1.03'])
                ->whereIn('lead_sales.status', ['1.19', '1.20', '1.21'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    //
    public static function CalCenterLeadEmirate($id, $status, $type, $channel)
    {
        if ($status == '1.02') {
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
                ->whereIn('activation_forms.status', ['1.02', '1.11'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->whereIn('lead_sales.emirates', [trim($id)])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)
                // ->whereDate('activation_forms.created_at', Carbon::now()->month)
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->whereIn('lead_sales.emirates', [trim($id)])
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                ->get()->count();
        }
    }
    //
    //
    public static function CalCenterLeadCategory($id, $status, $type, $channel)
    {
        return $k = lead_sale::select('lead_sales.customer_name', 'users.agent_code', 'plans.plan_name')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                '=',
                'activation_forms.activation_selected_no'
            )
            ->where('numberdetails.type', $id)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            // ->where('lead_sales.channel_type', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    //
    public static function CalCenterLeadCategoryAll($id, $status, $type, $channel)
    {
        return $k = lead_sale::select('lead_sales.customer_name', 'users.agent_code', 'plans.plan_name')
            ->join(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->join(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->join(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->join(
                'numberdetails',
                'numberdetails.number',
                '=',
                'activation_forms.activation_selected_no'
            )
            // ->where('numberdetails.type', $id)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('lead_sales.sim_type', 'New')
            // ->where('lead_sales.channel_type', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
        // return $k = lead_sale::select('lead_sales.customer_name', 'users.agent_code', 'plans.plan_name')
        // ->LeftJoin(
        //     'activation_forms',
        //     'activation_forms.lead_id',
        //     '=',
        //     'lead_sales.id'
        // )
        //     ->LeftJoin(
        //         'plans',
        //         'plans.id',
        //         '=',
        //         'activation_forms.select_plan'
        //     )
        //     ->LeftJoin(
        //         'users',
        //         'activation_forms.saler_id',
        //         '=',
        //         'users.id'
        //     )
        //     ->LeftJoin(
        //         'numberdetails',
        //         'numberdetails.number',
        //         '=',
        //         'activation_forms.activation_selected_no'
        //     )
        //     // ->where('numberdetails.type', $id)
        //     ->when($channel, function ($query) use ($channel) {
        //         if ($channel == 'Combined') {
        //             return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
        //         } else {
        //             return $query->where('lead_sales.channel_type', $channel);
        //             // return $query->whereIn('partner.id', $deals_in_daily);
        //         }
        //     })
        //     // ->where('lead_sales.channel_type', $channel_partner)
        //     ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
        //     ->whereMonth('activation_forms.created_at', Carbon::now()->month)
        //     ->whereYear('activation_forms.created_at', Carbon::now()->year)
        //     ->get()
        //     ->count();
    }
    //
    //
    public static function CalCenterLeadEmirateAll($id, $status, $type, $channel)
    {
        if ($status == '1.02') {
            // return "Salman";
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
                ->whereIn('activation_forms.status', ['1.02', '1.11'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->whereIn('lead_sales.emirates', [trim($id)])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)
                // ->whereDate('activation_forms.created_at', Carbon::now()->month)
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('lead_sales.emirates', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.updated_at', Carbon::now()->year)
                ->get()->count();
        }
    }
    //
    public static function CalCenterLeadtypeDate($id, $status, $type, $channel)
    {
        // return $id . $status . $channel . $type;
        // $channel = "'ExpressDial',"
        // return $status;
        // return $channel;
        // $abcd =  $collection->pluck('plan_name', 'id');
        // $channel = "TTF,ExpressDial";

        // $status = trim($status);
        if ($status == '1.01') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        if ($status == 'verified') {
            // return "909";
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('verification_forms.created_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->groupBy('verification_forms.lead_no')
                ->get()

                // ->where('users.id', $id)
                ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'mnpinprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
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
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpfollowup') {
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
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    //
    public static function CalCenterLeadtypeDateMNP($id, $status, $type, $channel)
    {
        // return $id . $status . $channel . $type;
        // $channel = "'ExpressDial',"
        // return $status;
        // return $channel;
        // $abcd =  $collection->pluck('plan_name', 'id');
        // $channel = "TTF,ExpressDial";

        // $status = trim($status);
        if ($status == '1.01') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('verification_forms.created_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->groupBy('verification_forms.lead_no')

                ->get()
                // ->where('users.id', $id)
                ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('activation_forms.sim_type', 'MNP')
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeDateHomeWifi($id, $status, $type, $channel)
    {
        // return $id . $status . $channel . $type;
        // $channel = "'ExpressDial',"
        // return $status;
        // return $channel;
        // $abcd =  $collection->pluck('plan_name', 'id');
        // $channel = "TTF,ExpressDial";

        // $status = trim($status);
        if ($status == '1.01') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        }
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('verification_forms.created_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->groupBy('verification_forms.lead_no')

                ->get()
                // ->where('users.id', $id)
                ->count();
            // ->whereIn('lead_sales.status',
            //         ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.13']
            //     // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
            // )
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('activation_forms.sim_type', 'HomeWifi')
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    //
    public static function CalCenterLeadtypeDateUser($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {

            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                // ->groupBy('verification_forms.lead_no')

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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
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
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.id', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
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
        } else {
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.id', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeDateCombine($id, $status, $type)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn(
                    'lead_sales.status',
                    ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                )
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                // ->groupBy('verification_forms.lead_no')

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
            //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == '1.02') {
            // return "000";
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
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterToday($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == '1.01') {
            // //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->where('lead_sales.status',1.01)
            // ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            // // ->where('users.agent_code', trim($id))
            // ->whereDate('lead_sales.created_at', Carbon::today())
            // ->get()
            // ->count();
            // // ->where('users.id', $id)
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        if ($status == '1.06') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', 1.06)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->whereDate('verification_forms.created_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')

                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.15','1.06'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'mnpinprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
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
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'mnpfollowup') {
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
                // ->where('lead_sales.lead_type', $type)
                ->where('lead_sales.sim_type', 'MNP')
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $today = Carbon::today();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->where('lead_sales.status', $status)
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTodayMNP($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == '1.01') {
            // //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->where('lead_sales.status',1.01)
            // ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            // // ->where('users.agent_code', trim($id))
            // ->whereDate('lead_sales.created_at', Carbon::today())
            // ->get()
            // ->count();
            // // ->where('users.id', $id)
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        if ($status == '1.06') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', 1.06)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->whereDate('verification_forms.created_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')

                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.15','1.06'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereNot('users.agent_code', 'AAMT')

                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereNot('users.agent_code', 'AAMT')

                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereNot('users.agent_code', 'AAMT')

                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('activation_forms.sim_type', 'MNP')
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $today = Carbon::today();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->where('lead_sales.status', $status)
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTodayHomeWifi($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == '1.01') {
            // //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->where('lead_sales.status',1.01)
            // ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            // // ->where('users.agent_code', trim($id))
            // ->whereDate('lead_sales.created_at', Carbon::today())
            // ->get()
            // ->count();
            // // ->where('users.id', $id)
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        if ($status == '1.06') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->where('lead_sales.status', 1.06)
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereNot('users.agent_code', 'AAMT')

                ->whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->whereDate('verification_forms.created_at', Carbon::today())
                ->groupBy('verification_forms.lead_no')

                // ->whereDate('lead_sales.created_', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status',
            // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21','1.15','1.06'])
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.created_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // return "0Z";
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
                        return $query->whereIn('lead_sales.channel_type', ['IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })            // ->where('users.agent_code', trim($id))
                ->where('users.agent_code', '!=', 'AAMT')

                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->where('users.agent_code', trim($id))
                ->whereNot('users.agent_code', 'AAMT')

                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('activation_forms.sim_type', 'HomeWifi')
                // ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'IdeaCorp']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', '!=', 'AAMT')

                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $today = Carbon::today();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->where('lead_sales.status', $status)
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTodayUser($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                // ->whereIn('lead_sales.status',
                // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                //     // ['1.05', '1.07', '1.08', '1.09', '1.10','1.02','1.16','1.17']
                // )
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                // ->groupBy('verification_forms.lead_no')

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                ->get()->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $today = Carbon::today();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            //     // $user =  DB::table("subjects")->select('subject_name', 'id')
            //     ->Join(
            //         'lead_sales',
            //         'lead_sales.saler_id',
            //         '=',
            //         'users.id'
            //     )
            //     ->where('lead_sales.status', $status)
            //     ->where('lead_sales.lead_type', $type)
            //     ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.updated_at', Carbon::today())
            //     // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterTodayCombine($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn(
                    'lead_sales.status',
                    ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']

                    // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
                )
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->groupBy('verification_forms.lead_no')

                ->get()->count();
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
            //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.date_time', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterMonthlyCombine($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if ($status == 'verified') {
            //  $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn(
                    'lead_sales.status',
                    ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21']
                    // ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17']
                )
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)
                // ->groupBy('verification_forms.lead_no')


                // ->whereDate('lead_sales.date_time', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
            // return $p = lead_sale
            // return  $a = lead_sale::select('lead_sales.id')
            //     ->Join(
            //         'lead_locations',
            //         'lead_locations.lead_id',
            //         'lead_sales.id'
            //     )
            //     ->Join(
            //         'users',
            //         'users.id',
            //         'lead_sales.saler_id'
            //     )
            //     ->where('lead_locations.assign_to', '!=', 136)
            //     ->where('lead_sales.status', '1.10')
            //     ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            //     ->get()->count();
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()->count();
            // $postpaid_verified = \App\Models\User::select("users.*")
            // // $user =  DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            //     'lead_sales',
            //     'lead_sales.saler_id',
            //     '=',
            //     'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            //     ->where('lead_sales.lead_type', $type)
            //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            //     // ->where('users.agent_code', trim($id))
            //     ->whereDate('lead_sales.date_time', Carbon::today())
            //     ->get()
            //     // ->where('users.id', $id)
            //     ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user =  DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01'])
                // ->whereIn('lead_sales.status', ['1.04','1.01'])
                ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereDate('lead_sales.date_time', Carbon::today())
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereDate('lead_sales.date_time', Carbon::today())
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where('lead_sales.lead_type', $type)
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('activation_forms.created_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else {
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
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                ->whereMonth('lead_sales.created_at', Carbon::now()->month)
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereDate('lead_sales.updated_at', Carbon::today())
                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    public static function CalCenterLeadtypeInProcess($id, $status, $status1, $type, $channel)
    {
        // return $id;
        return $postpaid_verified = \App\Models\User::select("users.id")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.status', [$status, $status1])
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            // ->where('users.id', $id)
            ->count();
    }
    // public function
    public function DailyReport(Request $request)
    {
        // return $request;
        // return  "Daily Report";
        $channel_partner = $cp = $request->channel;
        if ($channel_partner == 'Combined') {
            $channel_partner = 'Combined';
            $channel_name = 'Combined';
        } else {
            $channel_partner = $request->channel;
            $channel_name = $request->channel;
        }
        //   return $channel_partner;
        // $channel_partner = channel_partner::where('secondary_status','1')
        // ->when($cp, function ($query) use ($cp) {
        //     if ($cp == 'Combined') {
        //         return $query->whereIn('channel_partner.name', ['TTF','ExpressDial']);
        //     }  else  {
        //         return $query->where('name', $cp);
        //         // return $query->whereIn('partner.id', $deals_in_daily);
        //     }
        // })->get();
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('id', 'asc')->get();
        return view('report.dailyreport', compact('channel_partner', 'callcenter', 'channel_name'));
    }
    public function NewDailyReport(Request $request)
    {
        // return $request;
        // return  "Daily Report";
        $channel_partner = $cp = $request->channel;
        if ($channel_partner == 'Combined') {
            $channel_partner = 'Combined';
            $channel_name = 'Combined';
        } else {
            $channel_partner = $request->channel;
            $channel_name = $request->channel;
        }
        //   return $channel_partner;
        // $channel_partner = channel_partner::where('secondary_status','1')
        // ->when($cp, function ($query) use ($cp) {
        //     if ($cp == 'Combined') {
        //         return $query->whereIn('channel_partner.name', ['TTF','ExpressDial']);
        //     }  else  {
        //         return $query->where('name', $cp);
        //         // return $query->whereIn('partner.id', $deals_in_daily);
        //     }
        // })->get();
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('id', 'asc')->get();
        return view('report.NewDailyReport', compact('channel_partner', 'callcenter', 'channel_name'));
    }
    public function PreviousReport(Request $request)
    {
        // return $request;
        // return  "Daily Report";
        // $channel_partner = channel_partner::whereStatus('1')->get();
        $channel_partner = $cp = $request->channel;
        if ($channel_partner == 'Combined') {
            $channel_partner = 'Combined';
            $channel_name = 'Combined';
        } else {
            $channel_partner = $request->channel;
            $channel_name = $request->channel;
        }
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('id', 'asc')->get();
        return view('report.previousreport', compact('channel_partner', 'callcenter'));
    }
    //
    public function PreviousReportCustomer(Request $request)
    {
        // return $request;
        // return  "Daily Report";

        // $channel_partner = channel_partner::whereStatus('1')->get();
        $channel_partner = $cp = $request->channel_partner;
        if ($channel_partner == 'Combined') {
            $channel_partner = 'Combined';
            $channel_name = 'Combined';
        } else {
            $channel_partner = $request->channel_partner;
            $channel_name = $request->channel_partner;
        }
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('id', 'asc')->get();

        //
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        //
        // return $channel_partner;
        return view('report.previousreportcustom', compact('channel_partner', 'callcenter', 'start_date', 'end_date'));
    }
    //
    public function DailyReportCallCenter($id)
    {
        // return $request;
        // return  "Daily Report";
        $channel_partner = channel_partner::whereIn('name', ['TTF', 'ExpressDial'])->get();
        //  $channel_partner = [
        //                 [
        //                     // 'email' =>
        //                     // 'Aroojmalikam776@gmail.com',
        //                     'name' => 'TTF',
        //                 ], [
        //                     // Email: verifcallmax@gmail.com
        //                     // 'email' =>
        //                     // '16171519-142@uogsialkot.edu.pk',
        //                     'name' => 'ExpressDial',
        //                 ],

        //                 ];

        $callcenter = \App\Models\User::select("users.id", "users.name as call_center_name", 'users.email', 'users.agent_code')
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->where('users.agent_code', trim($id))
            ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            // ->orderBy(DB::raw('COUNT(id)'), 'DESC')
            ->get();

        $call_center_id = $id;
        return view('report.dailyreport-callcenter', compact('channel_partner', 'callcenter', 'call_center_id'));
    }
    public function MonthlyReportCallCenter($id)
    {
        // return $request;
        // return  "Daily Report";
        // $channel_partner = channel_partner::whereStatus('1')->get();
        $channel_partner = 'Combined';

        $callcenter = \App\Models\User::select("users.id", "users.name as call_center_name", 'users.email', 'users.agent_code')
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->where('users.agent_code', trim($id))
            ->whereIn('users.role', ['NumberAdmin', 'Sale'])
            // ->orderBy(DB::raw('COUNT(id)'), 'DESC')
            ->get();

        $call_center_id = $id;
        return view('report.monthlyreport-callcenter', compact('channel_partner', 'callcenter', 'call_center_id'));
    }

    public function myactivation($id)
    {
        $call_center_monthly = \App\Models\activation_form::select('activation_forms.*', 'plans.plan_name', 'plans.monthly_payment as revenue')
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
            ->LeftJoin(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            // ->where('lead_sales.lead_type', '')
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            ->where('users.id', $id)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get();
        $call_center_daily = \App\Models\activation_form::select('activation_forms.*', 'plans.plan_name', 'plans.revenue as revenue')
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
            ->LeftJoin(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            // ->where('lead_sales.lead_type', '')
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            ->where('users.id', $id)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get();
        return view('report.dailyactivation', compact('call_center_monthly', 'call_center_daily'));
    }
    // public function TodayReport(Request $request){
    //     // return $request;
    //     // return  "Daily Report";
    //     $channel_partner = channel_partner::whereStatus('1')->get();
    //     $callcenter = call_center::select('call_centers.*')
    //     ->where('call_center_name','!=','ARF')
    //     ->where('call_center_name','!=','CC8-Elife')
    //     ->where('status','1')->get();
    //     return view('report.dailyreport',compact('channel_partner', 'callcenter'));
    // }

    public static function plan_sum($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.08', '1.11'])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->sum('plans.revenue');
    }
    public static function plan_sum_user($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.id', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.08', '1.11'])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->sum('plans.revenue');
    }
    public static function plan_sum_sum($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })        // ->where('users.agent_code', trim($id))
            ->where('users.agent_code', '!=', 'AAMT')
            ->whereIn('activation_forms.status', ['1.02', '1.08', '1.11'])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->sum('plans.revenue');
    }
    public static function plan_sum_sumUser($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.08', '1.11'])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->sum('plans.revenue');
    }
    public static function plan_sum_combine($id, $status, $type)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            ->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.08', '1.11'])
            ->whereDate('activation_forms.created_at', Carbon::today())

            ->sum('plans.revenue');
    }
    public static function plan_sum_monthly($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.08', '1.11'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->sum('plans.revenue');
    }
    public static function plan_sum_monthly_previous($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->sum('plans.revenue');
    }
    public static function plan_sum_monthly_user($id, $status, $type, $channel)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.id', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->sum('plans.revenue');
    }
    public static function plan_sum_monthly_combine($id, $status, $type, $channel)
    {
        // $k =
        // return "001";
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })        // ->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->sum('plans.revenue');
        // return $k = lead_sale::select('plans.revenue')
        // ->LeftJoin(
        //     'activation_forms',
        //     'activation_forms.lead_id',
        //     '=',
        //     'lead_sales.id'
        // )
        // ->LeftJoin(
        //     'plans',
        //     'plans.id',
        //     '=',
        //     'activation_forms.select_plan'
        // )
        // ->LeftJoin(
        //     'users',
        //     'lead_sales.saler_id',
        //     '=',
        //     'users.id'
        // )
        //     // ->where('plans.monthly_payment', '<', 150)
        //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
        // ->where('lead_sales.lead_type', $type)
        // ->where('users.agent_code', trim($id))
        //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
        // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
        // ->sum('plans.revenue');
    }
    public static function plan_sum_monthly_combine_previous($id, $status, $type, $channel)
    {
        // $k =
        // return "001";
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })        // ->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->sum('plans.revenue');
        // return $k = lead_sale::select('plans.revenue')
        // ->LeftJoin(
        //     'activation_forms',
        //     'activation_forms.lead_id',
        //     '=',
        //     'lead_sales.id'
        // )
        // ->LeftJoin(
        //     'plans',
        //     'plans.id',
        //     '=',
        //     'activation_forms.select_plan'
        // )
        // ->LeftJoin(
        //     'users',
        //     'lead_sales.saler_id',
        //     '=',
        //     'users.id'
        // )
        //     // ->where('plans.monthly_payment', '<', 150)
        //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
        // ->where('lead_sales.lead_type', $type)
        // ->where('users.agent_code', trim($id))
        //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
        // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
        // ->sum('plans.revenue');
    }
    public static function plan_sum_monthly_combine_user($id, $status, $type, $channel)
    {
        // $k =
        // return "001";
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $id . $status . $type . $channel;
        return $k = lead_sale::select('plans.revenue')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->sum('plans.revenue');
        // return $k = lead_sale::select('plans.revenue')
        // ->LeftJoin(
        //     'activation_forms',
        //     'activation_forms.lead_id',
        //     '=',
        //     'lead_sales.id'
        // )
        // ->LeftJoin(
        //     'plans',
        //     'plans.id',
        //     '=',
        //     'activation_forms.select_plan'
        // )
        // ->LeftJoin(
        //     'users',
        //     'lead_sales.saler_id',
        //     '=',
        //     'users.id'
        // )
        //     // ->where('plans.monthly_payment', '<', 150)
        //     // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
        // ->where('lead_sales.lead_type', $type)
        // ->where('users.agent_code', trim($id))
        //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
        // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
        // ->sum('plans.revenue');
    }
    public static function plan_category_act($category, $channel_partner)
    {
        return $k = lead_sale::select('lead_sales.customer_name', 'users.agent_code', 'plans.plan_name')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                '=',
                'activation_forms.activation_selected_no'
            )
            ->where('numberdetails.type', $category)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            // ->where('lead_sales.channel_type', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function plan_category_act_previous($category, $channel_partner)
    {
        return $k = lead_sale::select('lead_sales.customer_name', 'users.agent_code', 'plans.plan_name')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                '=',
                'activation_forms.activation_selected_no'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('numberdetails.type', $category)
            // ->where('lead_sales.channel_type', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get()
            ->count();
    }
    public function plan_below_150_get()
    {
        return $k = lead_sale::select('lead_sales.customer_name', 'users.agent_code', 'plans.plan_name', 'plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            // ->where('lead_sales.channel_type', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->where('plans.monthly_payment', '<', 150)

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->get();
        // ->count();
    }
    public static function plan_below_150($channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function plan_below_150_previous($channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->get()
            ->count();
    }
    public static function plan_below_150_monthly_call_center($call_center_code, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('users.agent_code', $call_center_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function plan_below_150_monthly_call_center_previous($call_center_code, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('users.agent_code', $call_center_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function plan_below_150_monthly_call_centeruser($call_center_code, $channel_partner)
    {
        // return "2"
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 149)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('users.id', $call_center_code)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function plan_below_150_daily_call_center($call_center_code, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('users.agent_code', $call_center_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
    }
    public static function plan_below_150_daily_call_centerUser($call_center_code, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('users.id', $call_center_code)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->get()
            ->count();
    }
    public static function plan_below_150_user($id, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', $id)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->count();
    }
    public static function plan_total($channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            // ->where('users.agent_code', '=', $channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->count();
    }
    public static function plan_total_previous($channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            // ->where('users.agent_code', '=', $channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->count();
    }
    public static function plan_total_user($id, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            // ->where('users.agent_code', '=', $channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->where('users.agent_code', $id)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->count();
    }
    public static function plan_above_150($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('plans.monthly_payment', '>=', 150)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)


            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    // public static function plan_above_150($channel_partner)
    // {
    //     // $k =
    //     // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
    //     //
    //     // return $channel_partner;
    //     return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //         ->LeftJoin(
    //             'plans',
    //             'plans.id',
    //             '=',
    //             'activation_forms.select_plan'
    //         )
    //         ->LeftJoin(
    //             'users',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //     ->when($channel_partner, function ($query) use ($channel_partner) {
    //         if ($channel_partner == 'Combined') {
    //             return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
    //         } else {
    //             return $query->where('lead_sales.channel_type', $channel_partner);
    //             // return $query->whereIn('partner.id', $deals_in_daily);
    //         }
    //     })            ->where('plans.monthly_payment', '>=', 150)
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)


    //         // ->whereDate('lead_sales.date_time', Carbon::today())
    //         // ->where('users.agent_code', '=',$channel_partner)
    //         //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
    //         ->count();
    // }
    //
    public static function plan_above_150_200_daily($call_center_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            // ->where('lead_sales.channel_type', $channel_partner)
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    //

    public static function plan_above_150_200_dailyUser($call_center_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.id', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200_month($call_center_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            // ->whereMonth('activation_forms.created_at', Carbon::today())
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200_month_previous($call_center_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            // ->whereMonth('activation_forms.created_at', Carbon::today())
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200_month_user($call_center_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.id', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            // ->whereMonth('activation_forms.created_at', Carbon::today())
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200User($id, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->where('users.agent_code', $id)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200_monthly($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200_monthly_previous($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_above_150_200_monthly_user($id, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->where('users.agent_code', $id)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->count();
    }
    public static function plan_below_150_daily($channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
    }
    public static function plan_below_150_dailyUser($id, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'activation_forms.saler_id',
                '=',
                'users.id'
            )
            ->where('plans.monthly_payment', '<', 150)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            //         ->whereIn('activation_forms.status',['1.02','1.11','1.08'])
            ->where('users.agent_code', $id)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
    }
    public static function plan_total_daily($channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            // ->where('users.agent_code', '=', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->count();
    }
    public static function plan_total_dailyUser($id, $channel_partner)
    {
        return $k = lead_sale::select('plans.monthly_payment')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('plans.monthly_payment', '<', 150)
            // ->where('users.agent_code', '=', $channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->where('users.agent_code', $id)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->count();
    }
    public static function plan_above_150_daily($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 150)
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_daily($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            // return $query->whereBetween('plans.monthly_payment', [$start, $end]);
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_day($agent_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $agent_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_dayUser($agent_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.id', $agent_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_dailyUser($user, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->where('users.agent_code', $user)

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)



            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_previous($channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            // ->whereDate('activation_forms.created_at', Carbon::today())


            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_monthly($agent_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $agent_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('activation_forms.created_at', Carbon::today())


            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_monthly_previous($agent_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $agent_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            // ->whereDate('activation_forms.created_at', Carbon::today())


            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_monthly_user($agent_code, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.id', $agent_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereDate('activation_forms.created_at', Carbon::today())


            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function plan_above_200_user($id, $channel_partner)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->where('users.agent_code', $id)
            // ->whereDate('activation_forms.created_at', Carbon::today())


            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    //
    public function MonthlyActivation(Request $request)
    {
        // return $request;
        // SELECT b.customer_name,c.plan_name,a.customer_number,a.selected_number,a.created_at as created_date,d.created_at as lead_assign_date, a.emirates,b.created_at as activated_date from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan INNER JOIN lead_locations d on d.lead_id = a.id

        // return $r = lead_sale::select('lead_sales.customer_name','plans.plan_name','lead_sales.selected_number','lead_sales.customer_number','lead_sales.created_at as create_date', 'lead_locations.created_at as assign_date','activation_forms.created_at as activation_date')
        // ->LeftJoin(
        //     'activation_forms','activation_forms.lead_id','=','lead_sales.id'
        // )
        // ->LeftJoin(
        //     'plans','plans.id','=','lead_sales.select_plan'
        // )
        // ->LeftJoin(
        //     'lead_locations',
        //     'lead_locations.lead_id','=','lead_sales.id'
        // )
        // ->where('lead_sales.status','1.02')
        // ->get();
        $channel_partner = channel_partner::whereStatus('1')->get();
        return view('report.activationreport', compact('channel_partner'));
    }
    //
    public function PreviousMonthlyActivation(Request $request)
    {
        // return $request;
        // SELECT b.customer_name,c.plan_name,a.customer_number,a.selected_number,a.created_at as created_date,d.created_at as lead_assign_date, a.emirates,b.created_at as activated_date from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan INNER JOIN lead_locations d on d.lead_id = a.id

        // return $r = lead_sale::select('lead_sales.customer_name','plans.plan_name','lead_sales.selected_number','lead_sales.customer_number','lead_sales.created_at as create_date', 'lead_locations.created_at as assign_date','activation_forms.created_at as activation_date')
        // ->LeftJoin(
        //     'activation_forms','activation_forms.lead_id','=','lead_sales.id'
        // )
        // ->LeftJoin(
        //     'plans','plans.id','=','lead_sales.select_plan'
        // )
        // ->LeftJoin(
        //     'lead_locations',
        //     'lead_locations.lead_id','=','lead_sales.id'
        // )
        // ->where('lead_sales.status','1.02')
        // ->get();
        $channel_partner = channel_partner::whereStatus('1')->get();
        return view('report.activationreport', compact('channel_partner'));
    }
    public static function carry_forward($id, $type, $channel)
    {
        // return Carbon::now()->submonth()->month;
        return $postpaid_verified = \App\Models\User::select("users.*")
            // $user =  DB::table("subjects")->select('subject_name', 'id')
            ->Join(
                'lead_sales',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            ->whereMonth('lead_sales.created_at', '=', Carbon::now()->submonth()->month)
            // ->whereDate('lead_sales.updated_at', Carbon::today())
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public function DailyActivation(Request $request)
    {
        $channel_partner = channel_partner::whereStatus('1')->get();
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('call_center_name', 'asc')->get();
        // return $callcenter_today = call_center::select('call_centers.*')
        // ->where('call_center_name','!=','ARF')
        // ->where('call_center_name','!=','CC8-Elife')
        // ->where('status','1')
        // ->where('created_at', Carbon::today())
        // ->get();
        // ->get();
        return view('report.ActivationReport2', compact('callcenter'));

        // return view('report.dailyreport', compact('channel_partner', 'callcenter'));
    }
    public function MonthlyTarget(Request $request)
    {
        $channel_partner = channel_partner::whereStatus('1')->get();
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('call_center_name', 'asc')->get();
        // return $callcenter_today = call_center::select('call_centers.*')
        // ->where('call_center_name','!=','ARF')
        // ->where('call_center_name','!=','CC8-Elife')
        // ->where('status','1')
        // ->where('created_at', Carbon::today())
        // ->get();
        // ->get();
        return view('report.monthlytarget', compact('callcenter'));

        // return view('report.dailyreport', compact('channel_partner', 'callcenter'));
    }

    public function CallCenterActivation(Request $request)
    {
        $channel_partner = channel_partner::whereStatus('1')->get();
        $callcenter = call_center::select('call_centers.*')
            ->where('call_center_name', '!=', 'ARF')
            ->where('call_center_name', '!=', 'CC8-Elife')
            ->where('status', '1')->orderBy('call_center_name', 'asc')->get();
        // return $callcenter_today = call_center::select('call_centers.*')
        // ->where('call_center_name','!=','ARF')
        // ->where('call_center_name','!=','CC8-Elife')
        // ->where('status','1')
        // ->where('created_at', Carbon::today())
        // ->get();
        // ->get();
        return view('report.call-center-activation', compact('callcenter'));

        // return view('report.dailyreport', compact('channel_partner', 'callcenter'));
    }
    public static function ActivationUser($userid, $date)
    {
        return $k = activation_form::select('id')
            ->where('saler_id', $userid)
            ->where('activation_date', $date)
            // ->where('')
            ->count();
    }
    public static function ActivationUserGrandTotal($userid)
    {
        return $k = activation_form::select('id')
            ->where('saler_id', $userid)
            // ->where('activation_date', $date)
            ->whereMonth('activation_forms.activation_date', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)


            // ->where('')
            ->count();
    }
    public static function ActivationCallAgent($userid, $date)
    {
        // return $userid;
        return $k = activation_form::select('id')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            ->where('users.agent_code', $userid)
            ->where('activation_forms.activation_date', $date)
            // ->where('')
            ->count();
    }
    public static function ActivationCallAgentBetween($userid, $date1, $date2)
    {
        // return $userid;
        // return $date1 . $date2;
        return $k = activation_form::select('id')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            ->where('users.agent_code', $userid)
            ->whereBetween('activation_forms.activation_date', [$date1, $date2])
            // ->where('')
            ->count();
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
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->count();
    }
    // public static function ActivationCallAgentBetween($userid){
    //     // return $userid;
    //     return $k = activation_form::select('id')
    //     ->LeftJoin(
    //         'users','users.id','activation_forms.saler_id'
    //     )
    //     ->where('users.agent_code', $userid)
    //         // ->where('activation_forms.activation_date', $date)
    //         // ->where('')
    //     ->whereMonth('activation_forms.activation_date', Carbon::now()->month)
    //     ->count();
    // }
    public static function ActivationGrandTotal()
    {
        // return $userid;
        return $k = activation_form::select('activation_forms.id')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            // ->where('users.agent_code', $userid)
            // ->where('activation_forms.activation_date', $date)
            // ->where('')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->get()
            ->count();
    }
    public static function ActivationGrandTotalDaily($d)
    {
        // return $userid;
        return $k = activation_form::select('activation_forms.id')
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            // ->where('users.agent_code', $userid)
            // ->where('activation_forms.activation_date', $date)
            // ->where('')
            ->whereDate('activation_forms.activation_date', $d)
            ->get()
            ->count();
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
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->sum();
    }
    public static function TotalPaid($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('users.agent_code', $type)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidUser($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            ->where('activation_forms.pay_status', 'Paid')
            ->where('users.agent_code', $id)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidCallAgent($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            ->where('users.agent_code', trim($id))
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('lead_sales.pay_status','Paid')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidCallAgentUser($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            ->where('users.id', trim($id))
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('lead_sales.pay_status','Paid')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidMonthlyCallCenter($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            ->where('users.agent_code', trim($id))
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('lead_sales.pay_status', 'Paid')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidMonthlyCallCenterUser($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            ->where('users.id', trim($id))
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('lead_sales.pay_status', 'Paid')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidMonthly($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            // ->where('lead_sales.pay_status','Paid')
            ->where('activation_forms.pay_status', 'Paid')
            // ->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalPaidMonthlyUser($id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            // ->where('lead_sales.pay_status','Paid')
            ->where('activation_forms.pay_status', 'Paid')
            ->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    public static function TotalCountCallCenter($date, $id, $type, $channel)
    {
        // return $id . $type . $channel;
        // return "30";
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'lead_sales.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('lead_sales.lead_type', $type)
            // ->where('lead_sales.pay_status','Paid')
            // ->where('activation_forms.pay_status', 'Paid')
            ->where('users.agent_code', trim($id))
            ->whereDate('activation_forms.activation_date', $date)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    // public function user_activation_target()
    // Sub Month Report
    public static function CalCenterLeadtypePrevious($id, $status, $type, $channel)
    {
        // return $id;
        // return $status;
        // return $status;
        // $status = trim($status);
        if (
            $status == 'verified'
        ) {
            // $status = "'1.03','1.05','1.07','1.08','1.09','1.10'";
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user = DB::table("subjects")->select('subject_name', 'id')
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
                ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10', '1.02', '1.16', '1.17', '1.19', '1.20', '1.21', '1.15', '1.06'])
                // ->where('lead_sales.lead_type', $type)
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                // ->where('users.agent_code', trim($id))
                // ->whereDate('verification_forms.created_at', Carbon::today())
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->whereMonth('verification_forms.created_at', Carbon::now()->submonth())
                ->whereYear('verification_forms.created_at', Carbon::now()->year)


                // -> whereDate('lead_sales.created_at', Carbon::today())
                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'inprocess') {
            return $a = lead_sale::select('lead_sales.id')
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
                ->where(
                    'lead_sales.lead_type',
                    $type
                )
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })->where('users.agent_code', trim($id))
                ->where('lead_locations.assign_to', '!=', 136)
                ->where('lead_sales.status', '1.10')
                ->whereMonth('lead_sales.updated_at', Carbon::now()->submonth())
                ->whereYear('lead_sales.updated_at', Carbon::now()->year)

                ->get()->count();
            // return $postpaid_verified = \App\Models\User::select("users.*")
            // // $user = DB::table("subjects")->select('subject_name', 'id')
            // ->Join(
            // 'lead_sales',
            // 'lead_sales.saler_id',
            // '=',
            // 'users.id'
            // )
            // ->whereIn('lead_sales.status', ['1.05', '1.07', '1.08', '1.09', '1.10'])
            // ->where('lead_sales.lead_type', $type)
            // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
            // ->where('users.agent_code', trim($id))
            // ->whereMonth('lead_sales.created_at', Carbon::now()->submonth())
            // ->get()
            // // ->where('users.id', $id)
            // ->count();
            // 1.03,1.05,1.07,1.08,1.09,1.10
        } else if ($status == 'nonverified') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user = DB::table("subjects")->select('subject_name', 'id')
                ->Join(
                    'lead_sales',
                    'lead_sales.saler_id',
                    '=',
                    'users.id'
                )
                ->whereIn('lead_sales.status', ['1.03', '1.01', '1.04', '1.15'])
                // ->whereIn('lead_sales.status', ['1.03','1.01'])
                ->where('lead_sales.lead_type', $type)
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth(
                    'lead_sales.created_at',
                    Carbon::now()->submonth()
                )
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'rejected') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user = DB::table("subjects")->select('subject_name', 'id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth(
                    'lead_sales.created_at',
                    Carbon::now()->submonth()
                )
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                ->get()
                // ->where('users.id', $id)
                ->count();
        } else if ($status == 'followup') {
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user = DB::table("subjects")->select('subject_name', 'id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth(
                    'lead_sales.created_at',
                    Carbon::now()->submonth()
                )
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
                ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
                ->where(
                    'lead_sales.lead_type',
                    $type
                )
                ->when($channel, function ($query) use ($channel) {
                    if ($channel == 'Combined') {
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                // ->whereIn('lead_sales.channel_type', ['TTF','ExpressDial'])
                ->where('users.agent_code', trim($id))
                ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
                ->whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
        }
        // else if($status == 'inprocess'){
        // return "00";
        // }
        else {
            // $today = Carbon::today();
            return $postpaid_verified = \App\Models\User::select("users.*")
                // $user = DB::table("subjects")->select('subject_name', 'id')
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
                        return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                    } else {
                        return $query->where('lead_sales.channel_type', $channel);
                        // return $query->whereIn('partner.id', $deals_in_daily);
                    }
                })
                ->where('users.agent_code', trim($id))
                ->whereMonth(
                    'lead_sales.created_at',
                    Carbon::now()->submonth()
                )
                ->whereYear('lead_sales.created_at', Carbon::now()->year)

                // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
                ->get()
                // ->where('users.id', $id)
                ->count();
            // $status = $status;
        }
        // $status = explode(',', $status);


    }
    //
    public static function MyActivationType($userid, $type)
    {
        return $k = activation_form::select('id')
            ->where('saler_id', $userid)
            ->where('activation_forms.sim_type', $type)
            ->where('activation_forms.status', '1.02')
            // ->where('activation_forms.is_prepaid', '0')
            ->whereMonth('activation_forms.activation_date', Carbon::now()->submonth())
            ->whereYear('activation_forms.activation_date', Carbon::now()->year)
            ->count();
    }
    public static function IsPrepaidAct($userid)
    {
        return $k = activation_form::select('id')
            ->where('saler_id', $userid)
            // ->whereMonth('activation_forms.sim_type', $type)
            ->where('activation_forms.is_prepaid', '1')
            ->whereMonth('activation_forms.activation_date', Carbon::now()->submonth())
            ->whereYear('activation_forms.activation_date', Carbon::now()->year)


            ->count();
    }
    public static function ActivationNumberCategoryCount($userid, $type)
    {
        return $k = activation_form::select('id')
            ->Join(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no'
            )
            ->where('activation_forms.saler_id', $userid)
            // ->where('numberdetails.type', $type)
            ->when($type, function ($query) use ($type) {
                if ($type == 'Silver') {
                    return $query->whereIn('numberdetails.type', ['Silver', 'Silver Star', 'Silver Plus']);
                } else {
                    // ->where('numberdetails.type', $type)
                    return $query->where('numberdetails.type', $type);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('activation_forms.is_prepaid', '0')
            ->where('activation_forms.status', '1.02')
            // ->whereMonth('activation_forms.sim_type', $type)
            // ->where('activation_forms.is_prepaid', '1')
            ->whereMonth('activation_forms.activation_date', Carbon::now()->submonth())
            ->whereYear('activation_forms.activation_date', Carbon::now()->year)
            ->count();
    }
    public static function UserIncrement($userid)
    {
        $k = \App\Models\additional_salary::where('userid', $userid)
            ->whereMonth('additional_salaries.salary_month', Carbon::now()->submonth())
            ->whereYear('additional_salaries.salary_month', Carbon::now()->year)

            ->first();
        if ($k) {
            return $k->inc_amount;
        } else {
            return 0;
        }
    }
    public static function UserDecrment($userid)
    {
        $k = \App\Models\additional_salary::where('userid', $userid)
            ->whereMonth('additional_salaries.salary_month', Carbon::now()->submonth())
            ->whereYear('additional_salaries.salary_month', Carbon::now()->year)

            ->first();
        if ($k) {
            return $k->dec_amount;
        } else {
            return 0;
        }
        // return $k->dec_amount;
    }
    public static function plan_above_200_dayUser_count($userid)
    {
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        return $k = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.id', $userid)
            // ->where('lead_sales.channel_type', $channel_partner)
            ->where('plans.monthly_payment', '>', 225)
            ->where('activation_forms.is_prepaid', '0')
            ->whereMonth('activation_forms.activation_date', Carbon::now()->submonth())
            ->whereYear('activation_forms.activation_date', Carbon::now()->year)
            // ->whereDate('activation_forms.created_at', Carbon::today())

            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)

            // ->whereDate('lead_sales.date_time', Carbon::today())
            // ->where('users.agent_code', '=',$channel_partner)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->count();
    }
    //
    public function Prepaid(Request $request)
    {

        return view('dashboard.prepaid');
    }
    public function all_prepaid(Request $request)
    {

        return view('dashboard.all_prepaid');
    }
    public function callcenter_prepaid(Request $request)
    {
        $slug = $request->slug;
        // if(auth()->user()->role == 'admin')
        return view('dashboard.callcenter_prepaid', compact('slug'));
    }
    public function total_prepaid(Request $request)
    {
        $operation = activation_form::select('activation_forms.customer_name as name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id', 'activation_forms.updated_at', 'activation_forms.created_at', 'activation_forms.customer_number')
            ->Join(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            // ->whereYear('activation_forms.created_at',$request->year)
            // ->whereMonth('activation_forms.created_at',$request->month)
            ->where('is_prepaid', '1')
            ->get();
        return view('dashboard.total_prepaid', compact('operation'));
    }
    public function total_invalid(Request $request)
    {
        $operation = activation_form::select('activation_forms.customer_name as name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id', 'activation_forms.updated_at', 'activation_forms.created_at')
            ->Join(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            // ->whereYear('activation_forms.created_at',$request->year)
            // ->whereMonth('activation_forms.created_at',$request->month)
            ->where('is_prepaid', '2')
            ->get();
        return view('dashboard.total_prepaid', compact('operation'));
    }
    public function isprepaid(Request $request)
    {
        // return view('dashboard.prepaid');
        if ($request->status == 'prepaid') {
            $data = activation_form::findorfail($request->id);
            $data->is_prepaid = 1;
            $data->save();
            return "1";
        } elseif ($request->status == 'invalid') {
            $data = activation_form::findorfail($request->id);
            $data->is_prepaid = 2;
            $data->save();
            return "2";
        }
    }
    //
    public function our_prepaid_ajax(Request $request)
    {
        // return $request->year;
        if (auth()->user()->role == 'Admin' || auth()->user()->role == 'SuperAdmin') {
            $operation = activation_form::select('activation_forms.customer_name as name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id', 'activation_forms.updated_at as date', 'activation_forms.created_at', 'users.name as saler_name', 'users.email as saler_email', 'activation_forms.customer_number')
                ->Join(
                    'plans',
                    'plans.id',
                    'activation_forms.select_plan'
                )
                ->LeftJoin(
                    'users',
                    'activation_forms.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.agent_code', $request->agent_code)
                ->whereYear('activation_forms.created_at', $request->year)
                ->whereMonth('activation_forms.created_at', $request->month)
                ->where('is_prepaid', '1')
                ->get();
        } else {


            $operation = activation_form::select('activation_forms.customer_name as name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id', 'activation_forms.updated_at as date', 'activation_forms.created_at', 'users.name as saler_name', 'users.email as saler_email', 'activation_forms.customer_number')
                ->Join(
                    'plans',
                    'plans.id',
                    'activation_forms.select_plan'
                )
                ->LeftJoin(
                    'users',
                    'activation_forms.saler_id',
                    '=',
                    'users.id'
                )
                ->where('users.agent_code', auth()->user()->agent_code)
                ->whereYear('activation_forms.created_at', $request->year)
                ->whereMonth('activation_forms.created_at', $request->month)
                ->where('is_prepaid', '1')
                ->get();
        }
        $year = $request->year;
        $month = $request->month;
        return view('dashboard.ajax.ourprepaid', compact('operation', 'year', 'month'));
        // $status = $request->status;
        //
    }
    //
    public function all_call_center_report(Request $request)
    {
        $call_center = call_center::where('status', 1)->get();
        return view('dashboard.callcenter-report', compact('call_center'));
    }
    //
    public function prepaid_ajax(Request $request)
    {
        // return $request->year;
        $operation = activation_form::select('activation_forms.customer_name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id')
            ->Join(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->whereYear('activation_forms.created_at', $request->year)
            ->whereMonth('activation_forms.created_at', $request->month)
            ->where('is_prepaid', '0')
            ->get();
        $year = $request->year;
        $month = $request->month;
        return view('dashboard.ajax.myprepaid', compact('operation', 'year', 'month'));
        // $status = $request->status;
        //
    }
    //
    public static function MyCallCenterActivation($call_center, $status, $date)
    {
        // return $date;
        $date = \Carbon\Carbon::parse($date);

        $day = $date->day;
        $month = $date->month;
        $year = $date->year;
        return $operation = activation_form::select('activation_forms.customer_name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id')
            ->Join(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            ->where('users.agent_code', $call_center)
            ->whereYear('activation_forms.created_at', $date)
            ->whereMonth('activation_forms.created_at', $date)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('is_prepaid', '0')
            ->count();
    }
    //
    public static function TotalSaleCallCenter($call_center, $date)
    {
        // return $date;
        // $date = \Carbon\Carbon::now();
        $start = new Carbon('first day of January 2022'); //2021-01-01 00:00:00
        // $end =  $lastMonth =  $date->subMonth()->format('Y-m-d'); // 8
        // $start = \Carbon\Carbon::parse($date);
        $end = new Carbon('last day of last month');
        // $day = $date->day;
        // $month = $date->month;
        // $year = $date->year;
        return $operation = activation_form::select('activation_forms.customer_name', 'activation_forms.activation_selected_no', 'plans.plan_name', 'activation_forms.id')
            ->Join(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
            ->where('users.agent_code', $call_center)
            // ->whereYear('activation_forms.created_at', $date)
            ->whereBetween('activation_forms.created_at', [$start, $end])
            // ->whereMonth('activation_forms.created_at', $date)
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('is_prepaid', '0')
            ->count();
    }
    //
    //
    public static function CallCenterAgent($agent)
    {
        return $users = User::select('users.id')
            ->where('users.agent_code', $agent)
            ->whereIn("users.role", array('sale', 'NumberAdmin'))->get()->count();
    }
    public static function AllAgent()
    {
        return $users = User::select('users.id')
            ->Join(
                'call_centers',
                'call_centers.call_center_name',
                'users.agent_code'
            )
            // ->where('users.agent_code',$agent)
            ->whereIn("users.role", array('sale', 'NumberAdmin'))->get()->count();
    }
    //

    //CHAT GRAPH
    public static function PlanCategoryType($id, $status, $type, $channel)
    {
        // ob_end_clean();
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
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'lead_sales.saler_id'
            // )
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })->where('users.agent_code', trim($id))
            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    //
    public function CategoryReport(Request $request)
    {
        $q = numberdetail::select("numberdetails.type")
            // ->where("remarks.user_agent_id", auth()->user()->id)
            // ->where("numberdetails.id", $request->id)
            ->where("numberdetails.status", 'Available')
            ->wherein('numberdetails.channel_type', ['ExpressDial', 'TTF'])
            ->groupBy('numberdetails.type')
            ->get();
        return view('report.category-report', compact('q'));
    }
    //
    public function PreviousReportForm(Request $request)
    {
        // $q = numberdetail::select("numberdetails.type")
        // // ->where("remarks.user_agent_id", auth()->user()->id)
        // // ->where("numberdetails.id", $request->id)
        // ->where("numberdetails.status", 'Available')
        // ->wherein('numberdetails.channel_type', ['ExpressDial', 'TTF'])
        // ->groupBy('numberdetails.type')
        // ->get();
        $channel_partner = channel_partner::where('secondary_status', 1)->get();
        return view('report.previous-report', compact('channel_partner'));
    }
    //
    public function ShowPreviousReport(Request $request)
    {
        // return "Zoom";
        // return $request->channel_partner;
        $validatedData = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        return "PreviousReportCustomer/start_date/" . $request->start_date . "/end_date" . '/' . $request->end_date . "/channel" . '/' . $request->channel_partner;
        // return $request;
    }
    //
    public function DownloadCategoryReport(Request $request)
    {
        // return "Zoom";
        $validatedData = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()->all()]);
        }
        return "DownloadCustomReport/start_date/" . $request->start_date . "/end_date" . '/' . $request->end_date . "/channel" . '/' . implode(',', $request->channel_partner)  . "/category" . '/' . implode(',', $request->number_category);
        // return $request;
    }
    //
    //
    public static function TotalCategoryActive($month, $status)
    {
        $channel = 'Combined';
        // return $month;
        $m = Carbon::createFromTimestamp($month);
        $year = Carbon::createFromTimestamp($month)->format('Y');

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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('numberdetails.type', $status)
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })            // ->where('users.agent_code', trim($id))
            ->whereMonth('activation_forms.created_at', $m)
            ->whereYear('activation_forms.created_at', $year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    //
    public function CategoryWiseCount(Request $request)
    {
        // return $request;
        return view('dashboard.CategoryCount');
    }
    //
    public static function UserWiseActive($userid, $month)
    {
        // return $month;
        $channel = 'Combined';
        // return $month;
        // return $userid;
        $m = Carbon::createFromTimestamp($month);
        $year = Carbon::createFromTimestamp($month)->format('Y');

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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('activation_forms.is_prepaid', '0')
            // ->where('numberdetails.type', $status)
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.id', trim($userid))
            ->whereMonth('activation_forms.created_at', $m)
            ->whereYear('activation_forms.created_at', $year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            // ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function UserWiseActivePrepaid($userid, $month)
    {
        // return $month;
        $channel = 'Combined';
        // return $month;
        $m = Carbon::createFromTimestamp($month);
        $year = Carbon::createFromTimestamp($month)->format('Y');

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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('activation_forms.is_prepaid', '1')
            // ->where('numberdetails.type', $status)
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.id', trim($userid))
            ->whereMonth('activation_forms.created_at', $m)
            ->whereYear('activation_forms.created_at', $year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    public static function UserWiseActivePrepaidMNP($userid, $month)
    {
        // return $month;
        $channel = 'Combined';
        // return $month;
        $m = Carbon::createFromTimestamp($month);
        $year = Carbon::createFromTimestamp($month)->format('Y');

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
            // ->LeftJoin(
            //     'numberdetails',
            //     'numberdetails.number',
            //     'activation_forms.activation_selected_no',
            // )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('activation_forms.is_prepaid', '1')
            ->where('lead_sales.sim_type', 'MNP')
            // ->where('numberdetails.type', $status)
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.id', trim($userid))
            ->whereMonth('activation_forms.created_at', $m)
            ->whereYear('activation_forms.created_at', $year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    //
    public static function UserWiseTypeCategory($userid, $month, $type)
    {
        // return $month;
        $channel = 'Combined';
        // return $month;
        $m = Carbon::createFromTimestamp($month);
        $year = Carbon::createFromTimestamp($month)->format('Y');

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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('activation_forms.is_prepaid', '0')
            ->when($type, function ($query) use ($type) {
                if ($type == 'Silver') {
                    return $query->whereIn('numberdetails.type', ['Silver', 'Silver']);
                } else {
                    // ->where('numberdetails.type', $type)
                    return $query->where('numberdetails.type', $type);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.id', trim($userid))
            ->whereMonth('activation_forms.created_at', $m)
            ->whereYear('activation_forms.created_at', $year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    //
    // public function planzeroto124nc()
    //
    public static function UserWiseTypeCategoryMNP($userid, $month, $type)
    {
        // return $month;
        $channel = 'Combined';
        // return $month;
        $m = Carbon::createFromTimestamp($month);
        $year = Carbon::createFromTimestamp($month)->format('Y');

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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->where('activation_forms.is_prepaid', '0')
            ->where('lead_sales.sim_type', 'MNP')
            // ->where('numberdetails.type', $type)
            // ->where('lead_sales.lead_type', $type)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.id', trim($userid))
            ->whereMonth('activation_forms.created_at', $m)
            ->whereYear('activation_forms.created_at', $year)


            // ->whereDate('activation_forms.created_at', Carbon::today())
            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
    //
    public static function planrangecdaily($call_center_code, $channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
    }
    //
    public static function planrangecdailyall($channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
    }
    //
    public static function planrangencdaily($call_center_code, $channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', 'No Commitment')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
    }
    public static function planrangencdailyall($channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', 'No Commitment')
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
    }
    //
    // final_ep_calculator_daily
    public static function final_ep_calculator_daily($call_center_code, $channel_partner)
    {
        // return 0;
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        // if($commit_0_124)
        $commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();

        $commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();

        $commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();


        //
        //
        $no_commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $no_commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $no_commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();

        $no_commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        //
        if ($commit_225_plus > 0) {
            $commit_225_plus_val = 225 * $commit_225_plus;
        } else {
            $commit_225_plus_val = 0;
        }
        if ($no_commit_150_224 > 0) {
            $no_commit_150_224_val = 25 * $no_commit_150_224;
        } else {
            $no_commit_150_224_val = 0;
        }
        if ($no_commit_225_plus > 0) {
            $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        } else {
            $no_commit_225_plus_val = 0;
        }
        if ($commit_125_149 > 0) {
            $commit_125_149_val = 75 * $commit_125_149;
        } else {
            $commit_125_149_val = 0;
        }
        if ($commit_150_224 > 0) {
            $commit_150_224_val = 125 * $commit_150_224;
        } else {
            $commit_150_224_val = 0;
        }
        return $final = $commit_225_plus_val + $no_commit_150_224_val + $no_commit_225_plus_val + $commit_125_149_val + $commit_150_224_val;
        //
        // return $commit_125_149;
        // if ($commit_0_124 > 0 && $commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "1111";
        //     $commit_0_124_val = 0 * $commit_0_124;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 >  0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "22";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "33";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_225_plus > 0 && $no_commit_0_124 > 0 &&  $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "44";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($no_commit_0_124 > 0 && $no_commit_125_149 > 0  && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "5";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_125_149 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0; //this
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($no_commit_0_124 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0; // this
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_225_plus > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_125_149 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_0_124 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_125_149 > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_125_149 > 0 && $commit_225_plus > 0
        // ) {
        //     // return "7";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "7";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }

        // else if ($no_commit_225_plus > 0) {
        //     // return "8";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if($commit_0_124 > 0){
        //     // return "9";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = ;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        //     $commit_0_124_val = 0 * $commit_0_124;
        // }
        // else if ($commit_125_149 > 0) {
        //     // return 12;
        //     $commit_125_149_val = 75 * $commit_125_149;
        // } else if ($commit_150_224 > 0) {
        //     $commit_150_224_val = 125 * $commit_150_224;
        // } else if ($commit_225_plus > 0) {
        //     // return "0000";
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        // } else if ($no_commit_0_124 > 0) {
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        // } else if ($no_commit_125_149 > 0) {
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        // } else if ($no_commit_150_224 > 0) {
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        // }
        // else if($no_commit_225_plus > 0){
        // }
        // else {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_225_plus_val = 0;
        //     $commit_150_224_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // // return $commit_125_149_val;
        // $am = 0;
        // //
        // return $am + $commit_0_124_val + $commit_125_149_val + $commit_150_224_val + $commit_225_plus_val + $no_commit_0_124_val + $no_commit_125_149_val + $no_commit_150_224_val + $no_commit_225_plus_val;
    }
    public static function final_ep_calculator_dailyall($channel_partner)
    {
        // return 0;
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', '!=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();

        //
        //
        $no_commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('users.agent_code', '!=', 'AAMT')
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $no_commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', '!=', 'AAMT')
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $no_commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', '!=', 'AAMT')
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        $no_commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', '!=', 'AAMT')
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereDate('activation_forms.created_at', Carbon::today())
            ->count();
        //
        // if (
        //     $commit_0_124 > 0 && $commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 || $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     $commit_0_124_val = 0 * $commit_0_124;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 || $no_commit_0_124 > 0 && $no_commit_125_149 >  0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if (
        //     $commit_150_224 > 0 && $commit_225_plus > 0 || $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_225_plus > 0 || $no_commit_0_124 > 0 &&  $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($no_commit_0_124 > 0 && $no_commit_125_149 > 0  && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if (
        //     $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($no_commit_225_plus > 0) {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_0_124 > 0
        // ) {
        //     $commit_0_124_val = 0 * $commit_0_124;
        // } else if ($commit_125_149 > 0) {
        //     $commit_125_149_val = 75 * $commit_125_149;
        // } else if ($commit_150_224 > 0) {
        //     $commit_150_224_val = 125 * $commit_150_224;
        // } else if ($commit_225_plus > 0) {
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        // } else if ($no_commit_0_124 > 0) {
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        // } else if ($no_commit_125_149 > 0) {
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        // } else if ($no_commit_150_224 > 0) {
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        // }
        // // else if($no_commit_225_plus > 0){
        // // }
        // else {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_225_plus_val = 0;
        //     $commit_150_224_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // // return $commit_125_149_val;
        // $am = 0;
        // //
        // return $am + $commit_0_124_val + $commit_125_149_val + $commit_150_224_val + $commit_225_plus_val + $no_commit_0_124_val + $no_commit_125_149_val + $no_commit_150_224_val + $no_commit_225_plus_val;

        if ($commit_225_plus > 0) {
            $commit_225_plus_val = 225 * $commit_225_plus;
        } else {
            $commit_225_plus_val = 0;
        }
        if ($no_commit_150_224 > 0) {
            $no_commit_150_224_val = 25 * $no_commit_150_224;
        } else {
            $no_commit_150_224_val = 0;
        }
        if ($no_commit_225_plus > 0) {
            $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        } else {
            $no_commit_225_plus_val = 0;
        }
        if ($commit_125_149 > 0) {
            $commit_125_149_val = 75 * $commit_125_149;
        } else {
            $commit_125_149_val = 0;
        }
        if ($commit_150_224 > 0) {
            $commit_150_224_val = 125 * $commit_150_224;
        } else {
            $commit_150_224_val = 0;
        }
        return $final = $commit_225_plus_val + $no_commit_150_224_val + $no_commit_225_plus_val + $commit_125_149_val + $commit_150_224_val;
    }
    //
    public static function planrangencmonthly($call_center_code, $channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', 'No Commitment')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
    }
    public static function planrangencmonthlyall($channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', 'No Commitment')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
    }
    //
    public static function planrangecmonthly($call_center_code, $channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
    }
    //
    public static function planrangecmonthlyall($channel_partner, $start, $end)
    {
        return $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->when($start, function ($query) use ($start, $end) {
                if ($start == '225plus') {
                    return $query->where('plans.monthly_payment', '>=', 225);
                } else {
                    // return $query->where('lead_sales.channel_type', $channel_partner);
                    return $query->whereBetween('plans.monthly_payment', [$start, $end]);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
    }
    // final_ep_calculator_daily
    public static function final_ep_calculator_monthly($call_center_code, $channel_partner)
    {
        // return 0;
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        // if($commit_0_124)
        $commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();

        $commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();

        $commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();


        //
        //
        $no_commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        $no_commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        $no_commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();

        $no_commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        //
        if ($commit_225_plus > 0) {
            $commit_225_plus_val = 225 * $commit_225_plus;
        } else {
            $commit_225_plus_val = 0;
        }
        if ($no_commit_150_224 > 0) {
            $no_commit_150_224_val = 25 * $no_commit_150_224;
        } else {
            $no_commit_150_224_val = 0;
        }
        if ($no_commit_225_plus > 0) {
            $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        } else {
            $no_commit_225_plus_val = 0;
        }
        if ($commit_125_149 > 0) {
            $commit_125_149_val = 75 * $commit_125_149;
        } else {
            $commit_125_149_val = 0;
        }
        if ($commit_150_224 > 0) {
            $commit_150_224_val = 125 * $commit_150_224;
        } else {
            $commit_150_224_val = 0;
        }
        return $final = $commit_225_plus_val + $no_commit_150_224_val + $no_commit_225_plus_val + $commit_125_149_val + $commit_150_224_val;
        //
        // return $commit_125_149;
        // if ($commit_0_124 > 0 && $commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "1111";
        //     $commit_0_124_val = 0 * $commit_0_124;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 >  0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "22";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "33";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_225_plus > 0 && $no_commit_0_124 > 0 &&  $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "44";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($no_commit_0_124 > 0 && $no_commit_125_149 > 0  && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "5";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_125_149 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0; //this
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($no_commit_0_124 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0; // this
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_225_plus > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_125_149 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_0_124 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_125_149 > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_125_149 > 0 && $commit_225_plus > 0
        // ) {
        //     // return "7";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "7";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }

        // else if ($no_commit_225_plus > 0) {
        //     // return "8";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if($commit_0_124 > 0){
        //     // return "9";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = ;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        //     $commit_0_124_val = 0 * $commit_0_124;
        // }
        // else if ($commit_125_149 > 0) {
        //     // return 12;
        //     $commit_125_149_val = 75 * $commit_125_149;
        // } else if ($commit_150_224 > 0) {
        //     $commit_150_224_val = 125 * $commit_150_224;
        // } else if ($commit_225_plus > 0) {
        //     // return "0000";
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        // } else if ($no_commit_0_124 > 0) {
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        // } else if ($no_commit_125_149 > 0) {
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        // } else if ($no_commit_150_224 > 0) {
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        // }
        // else if($no_commit_225_plus > 0){
        // }
        // else {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_225_plus_val = 0;
        //     $commit_150_224_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // // return $commit_125_149_val;
        // $am = 0;
        // //
        // return $am + $commit_0_124_val + $commit_125_149_val + $commit_150_224_val + $commit_225_plus_val + $no_commit_0_124_val + $no_commit_125_149_val + $no_commit_150_224_val + $no_commit_225_plus_val;
    }
    // final_ep_calculator_daily
    public static function final_ep_calculator_monthlyall($call_center_code, $channel_partner)
    {
        // return 0;
        // $k =
        // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
        //
        // return $channel_partner;
        $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        // if($commit_0_124)
        $commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();

        $commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();

        $commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code',$call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();


        //
        //
        $no_commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '!=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [0, 124])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        $no_commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [125, 149])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        $no_commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->whereBetween('plans.monthly_payment', [150, 224])
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();

        $no_commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
            ->LeftJoin(
                'activation_forms',
                'activation_forms.lead_id',
                '=',
                'lead_sales.id'
            )
            ->LeftJoin(
                'plans',
                'plans.id',
                '=',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'lead_sales.saler_id',
                '=',
                'users.id'
            )
            // ->where('users.agent_code', $call_center_code)
            ->when($channel_partner, function ($query) use ($channel_partner) {
                if ($channel_partner == 'Combined') {
                    return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
                } else {
                    return $query->where('lead_sales.channel_type', $channel_partner);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
            ->where('plans.duration', '=', 'No Commitment')
            ->where('plans.monthly_payment', '>=', 225)
            ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            ->whereYear('activation_forms.created_at', Carbon::now()->year)
            ->count();
        //
        if ($commit_225_plus > 0) {
            $commit_225_plus_val = 225 * $commit_225_plus;
        } else {
            $commit_225_plus_val = 0;
        }
        if ($no_commit_150_224 > 0) {
            $no_commit_150_224_val = 25 * $no_commit_150_224;
        } else {
            $no_commit_150_224_val = 0;
        }
        if ($no_commit_225_plus > 0) {
            $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        } else {
            $no_commit_225_plus_val = 0;
        }
        if ($commit_125_149 > 0) {
            $commit_125_149_val = 75 * $commit_125_149;
        } else {
            $commit_125_149_val = 0;
        }
        if ($commit_150_224 > 0) {
            $commit_150_224_val = 125 * $commit_150_224;
        } else {
            $commit_150_224_val = 0;
        }
        return $final = $commit_225_plus_val + $no_commit_150_224_val + $no_commit_225_plus_val + $commit_125_149_val + $commit_150_224_val;
        //
        // return $commit_125_149;
        // if ($commit_0_124 > 0 && $commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "1111";
        //     $commit_0_124_val = 0 * $commit_0_124;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 >  0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "22";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_150_224 > 0 && $commit_225_plus > 0 && $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "33";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($commit_225_plus > 0 && $no_commit_0_124 > 0 &&  $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "44";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // } else if ($no_commit_0_124 > 0 && $no_commit_125_149 > 0  && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "5";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if ($no_commit_125_149 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0; //this
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($no_commit_0_124 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0; // this
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_225_plus > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_150_224 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 125 * $commit_150_224;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_125_149 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_0_124 > 0 && $no_commit_225_plus > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_125_149 > 0) {
        //     // return "6";

        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus; // this
        // }
        // else if ($commit_125_149 > 0 && $commit_225_plus > 0
        // ) {
        //     // return "7";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 75 * $commit_125_149;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // else if ($no_commit_150_224 > 0 && $no_commit_225_plus > 0
        // ) {
        //     // return "7";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }

        // else if ($no_commit_225_plus > 0) {
        //     // return "8";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        // }
        // else if($commit_0_124 > 0){
        //     // return "9";
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_150_224_val = 0;
        //     $commit_225_plus_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = ;
        //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
        //     $commit_0_124_val = 0 * $commit_0_124;
        // }
        // else if ($commit_125_149 > 0) {
        //     // return 12;
        //     $commit_125_149_val = 75 * $commit_125_149;
        // } else if ($commit_150_224 > 0) {
        //     $commit_150_224_val = 125 * $commit_150_224;
        // } else if ($commit_225_plus > 0) {
        //     // return "0000";
        //     $commit_225_plus_val = 225 * $commit_225_plus;
        // } else if ($no_commit_0_124 > 0) {
        //     $no_commit_0_124_val = 0 * $no_commit_0_124;
        // } else if ($no_commit_125_149 > 0) {
        //     $no_commit_125_149_val = 0 * $no_commit_125_149;
        // } else if ($no_commit_150_224 > 0) {
        //     $no_commit_150_224_val = 25 * $no_commit_150_224;
        // }
        // else if($no_commit_225_plus > 0){
        // }
        // else {
        //     $commit_0_124_val = 0;
        //     $commit_125_149_val = 0;
        //     $commit_225_plus_val = 0;
        //     $commit_150_224_val = 0;
        //     $no_commit_0_124_val = 0;
        //     $no_commit_125_149_val = 0;
        //     $no_commit_150_224_val = 0;
        //     $no_commit_225_plus_val = 0;
        // }
        // // return $commit_125_149_val;
        // $am = 0;
        // //
        // return $am + $commit_0_124_val + $commit_125_149_val + $commit_150_224_val + $commit_225_plus_val + $no_commit_0_124_val + $no_commit_125_149_val + $no_commit_150_224_val + $no_commit_225_plus_val;
    }
    // public static function final_ep_calculator_monthlyall($channel_partner)
    // {
    //     // return 0;
    //     // $k =
    //     // SELECT a.select_plan,c.plan_name,c.monthly_payment from lead_sales a INNER JOIN activation_forms b on b.lead_id = a.id INNER JOIN plans c on c.id = a.select_plan ORDER BY c.monthly_payment DESC
    //     //
    //     // return $channel_partner;
    //     $commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //         ->LeftJoin(
    //             'plans',
    //             'plans.id',
    //             '=',
    //             'activation_forms.select_plan'
    //         )
    //         ->LeftJoin(
    //             'users',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         // ->where('users.agent_code',$call_center_code)
    //         ->when($channel_partner, function ($query) use ($channel_partner) {
    //             if ($channel_partner == 'Combined') {
    //                 return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
    //             } else {
    //                 return $query->where('lead_sales.channel_type', $channel_partner);
    //                 // return $query->whereIn('partner.id', $deals_in_daily);
    //             }
    //         })
    //         ->where('plans.duration','!=', 'No Commitment')
    //         ->whereBetween('plans.monthly_payment', [0,124])
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //         ->count();
    //      $commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //         ->LeftJoin(
    //             'plans',
    //             'plans.id',
    //             '=',
    //             'activation_forms.select_plan'
    //         )
    //         ->LeftJoin(
    //             'users',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         // ->where('users.agent_code',$call_center_code)
    //         ->when($channel_partner, function ($query) use ($channel_partner) {
    //             if ($channel_partner == 'Combined') {
    //                 return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
    //             } else {
    //                 return $query->where('lead_sales.channel_type', $channel_partner);
    //                 // return $query->whereIn('partner.id', $deals_in_daily);
    //             }
    //         })
    //         ->where('plans.duration', '!=', 'No Commitment')
    //         ->whereBetween('plans.monthly_payment', [125,149])
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //         ->count();
    //      $commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //         ->LeftJoin(
    //             'plans',
    //             'plans.id',
    //             '=',
    //             'activation_forms.select_plan'
    //         )
    //         ->LeftJoin(
    //             'users',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         // ->where('users.agent_code',$call_center_code)
    //         ->when($channel_partner, function ($query) use ($channel_partner) {
    //             if ($channel_partner == 'Combined') {
    //                 return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
    //             } else {
    //                 return $query->where('lead_sales.channel_type', $channel_partner);
    //                 // return $query->whereIn('partner.id', $deals_in_daily);
    //             }
    //         })
    //         ->where('plans.duration', '!=', 'No Commitment')
    //         ->whereBetween('plans.monthly_payment', [150,224])
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //         ->count();
    //      $commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //         ->LeftJoin(
    //             'plans',
    //             'plans.id',
    //             '=',
    //             'activation_forms.select_plan'
    //         )
    //         ->LeftJoin(
    //             'users',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         // ->where('users.agent_code',$call_center_code)
    //         ->when($channel_partner, function ($query) use ($channel_partner) {
    //             if ($channel_partner == 'Combined') {
    //                 return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial']);
    //             } else {
    //                 return $query->where('lead_sales.channel_type', $channel_partner);
    //                 // return $query->whereIn('partner.id', $deals_in_daily);
    //             }
    //         })
    //         ->where('plans.duration', '!=', 'No Commitment')
    //         ->where('plans.monthly_payment', '>=', 225)
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //         ->count();

    //     //
    //     //
    //     $no_commit_0_124 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //         ->LeftJoin(
    //             'plans',
    //             'plans.id',
    //             '=',
    //             'activation_forms.select_plan'
    //         )
    //         ->LeftJoin(
    //             'users',
    //             'lead_sales.saler_id',
    //             '=',
    //             'users.id'
    //         )
    //         // ->where('users.agent_code', $call_center_code)
    //         ->when($channel_partner, function ($query) use ($channel_partner) {
    //             if ($channel_partner == 'Combined') {
    //                 return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
    //             } else {
    //                 return $query->where('lead_sales.channel_type', $channel_partner);
    //                 // return $query->whereIn('partner.id', $deals_in_daily);
    //             }
    //         })
    //         ->where('plans.duration', '!=', 'No Commitment')
    //         ->whereBetween('plans.monthly_payment', [0, 124])
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //         ->count();
    //     $no_commit_125_149 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //     ->LeftJoin(
    //         'plans',
    //         'plans.id',
    //         '=',
    //         'activation_forms.select_plan'
    //     )
    //     ->LeftJoin(
    //         'users',
    //         'lead_sales.saler_id',
    //         '=',
    //         'users.id'
    //     )
    //     // ->where('users.agent_code', $call_center_code)
    //     ->when($channel_partner, function ($query) use ($channel_partner) {
    //         if ($channel_partner == 'Combined') {
    //             return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
    //         } else {
    //             return $query->where('lead_sales.channel_type', $channel_partner);
    //             // return $query->whereIn('partner.id', $deals_in_daily);
    //         }
    //     })
    //     ->where('plans.duration', '=', 'No Commitment')
    //     ->whereBetween('plans.monthly_payment', [125, 149])
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //     ->count();
    //     $no_commit_150_224 = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //     ->LeftJoin(
    //         'plans',
    //         'plans.id',
    //         '=',
    //         'activation_forms.select_plan'
    //     )
    //     ->LeftJoin(
    //         'users',
    //         'lead_sales.saler_id',
    //         '=',
    //         'users.id'
    //     )
    //     // ->where('users.agent_code', $call_center_code)
    //     ->when($channel_partner, function ($query) use ($channel_partner) {
    //         if ($channel_partner == 'Combined') {
    //             return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
    //         } else {
    //             return $query->where('lead_sales.channel_type', $channel_partner);
    //             // return $query->whereIn('partner.id', $deals_in_daily);
    //         }
    //     })
    //     ->where('plans.duration', '=', 'No Commitment')
    //     ->whereBetween('plans.monthly_payment', [150, 224])
    //     ->whereDate('activation_forms.created_at', Carbon::today())
    //     ->count();
    //     $no_commit_225_plus = lead_sale::select('plans.monthly_payment', 'lead_sales.channel_type')
    //     ->LeftJoin(
    //         'activation_forms',
    //         'activation_forms.lead_id',
    //         '=',
    //         'lead_sales.id'
    //     )
    //     ->LeftJoin(
    //         'plans',
    //         'plans.id',
    //         '=',
    //         'activation_forms.select_plan'
    //     )
    //     ->LeftJoin(
    //         'users',
    //         'lead_sales.saler_id',
    //         '=',
    //         'users.id'
    //     )
    //     // ->where('users.agent_code', $call_center_code)
    //     ->when($channel_partner, function ($query) use ($channel_partner) {
    //         if ($channel_partner == 'Combined') {
    //             return $query->whereIn('lead_sales.channel_type', ['TTF', 'ExpressDial', 'MWH', 'Ideacorp']);
    //         } else {
    //             return $query->where('lead_sales.channel_type', $channel_partner);
    //             // return $query->whereIn('partner.id', $deals_in_daily);
    //         }
    //     })
    //     ->where('plans.duration', '=', 'No Commitment')
    //     ->where('plans.monthly_payment', '>=', 225)
    //         ->whereMonth('activation_forms.created_at', Carbon::now()->month)
    //         ->whereYear('activation_forms.created_at', Carbon::now()->year)
    //     ->count();
    //     //
    //     // if (
    //     //     $commit_0_124 > 0 && $commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 || $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
    //     // ) {
    //     //     $commit_0_124_val = 0 * $commit_0_124;
    //     //     $commit_125_149_val = 75 * $commit_125_149;
    //     //     $commit_150_224_val = 125 * $commit_150_224;
    //     //     $commit_225_plus_val = 225 * $commit_225_plus;
    //     //     $no_commit_0_124_val = 0 * $no_commit_0_124;
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if ($commit_125_149 > 0  && $commit_150_224 > 0 && $commit_225_plus > 0 || $no_commit_0_124 > 0 && $no_commit_125_149 >  0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 75 * $commit_125_149;
    //     //     $commit_150_224_val = 125 * $commit_150_224;
    //     //     $commit_225_plus_val = 225 * $commit_225_plus;
    //     //     $no_commit_0_124_val = 0 * $no_commit_0_124;
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if (
    //     //     $commit_150_224 > 0 && $commit_225_plus > 0 || $no_commit_0_124 > 0 && $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0
    //     // ) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_150_224_val = 125 * $commit_150_224;
    //     //     $commit_225_plus_val = 225 * $commit_225_plus;
    //     //     $no_commit_0_124_val = 0 * $no_commit_0_124;
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if ($commit_225_plus > 0 || $no_commit_0_124 > 0 &&  $no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_150_224_val = 0;
    //     //     $commit_225_plus_val = 225 * $commit_225_plus;
    //     //     $no_commit_0_124_val = 0 * $no_commit_0_124;
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if ($no_commit_0_124 > 0 && $no_commit_125_149 > 0  && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_150_224_val = 0;
    //     //     $commit_225_plus_val = 0;
    //     //     $no_commit_0_124_val = 0 * $no_commit_0_124;
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if ($no_commit_125_149 > 0 && $no_commit_150_224 > 0 && $no_commit_225_plus > 0) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_150_224_val = 0;
    //     //     $commit_225_plus_val = 0;
    //     //     $no_commit_0_124_val = 0;
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if (
    //     //     $no_commit_150_224 > 0 && $no_commit_225_plus > 0
    //     // ) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_150_224_val = 0;
    //     //     $commit_225_plus_val = 0;
    //     //     $no_commit_0_124_val = 0;
    //     //     $no_commit_125_149_val = 0;
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if ($no_commit_225_plus > 0) {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_150_224_val = 0;
    //     //     $commit_225_plus_val = 0;
    //     //     $no_commit_0_124_val = 0;
    //     //     $no_commit_125_149_val = 0;
    //     //     $no_commit_150_224_val = 0;
    //     //     $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     // } else if ($commit_0_124 > 0
    //     // ) {
    //     //     $commit_0_124_val = 0 * $commit_0_124;
    //     // } else if ($commit_125_149 > 0) {
    //     //     $commit_125_149_val = 75 * $commit_125_149;
    //     // } else if ($commit_150_224 > 0) {
    //     //     $commit_150_224_val = 125 * $commit_150_224;
    //     // } else if ($commit_225_plus > 0) {
    //     //     $commit_225_plus_val = 225 * $commit_225_plus;
    //     // } else if ($no_commit_0_124 > 0) {
    //     //     $no_commit_0_124_val = 0 * $no_commit_0_124;
    //     // } else if ($no_commit_125_149 > 0) {
    //     //     $no_commit_125_149_val = 0 * $no_commit_125_149;
    //     // } else if ($no_commit_150_224 > 0) {
    //     //     $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     // }
    //     // // else if($no_commit_225_plus > 0){
    //     // // }
    //     // else {
    //     //     $commit_0_124_val = 0;
    //     //     $commit_125_149_val = 0;
    //     //     $commit_225_plus_val = 0;
    //     //     $commit_150_224_val = 0;
    //     //     $no_commit_0_124_val = 0;
    //     //     $no_commit_125_149_val = 0;
    //     //     $no_commit_150_224_val = 0;
    //     //     $no_commit_225_plus_val = 0;
    //     // }
    //     // // return $commit_125_149_val;
    //     // $am = 0;
    //     // //
    //     // return $am + $commit_0_124_val + $commit_125_149_val + $commit_150_224_val + $commit_225_plus_val + $no_commit_0_124_val + $no_commit_125_149_val + $no_commit_150_224_val + $no_commit_225_plus_val;

    //     if ($commit_225_plus > 0) {
    //         $commit_225_plus_val = 225 * $commit_225_plus;
    //     } else {
    //         $commit_225_plus_val = 0;
    //     }
    //     if ($no_commit_150_224 > 0) {
    //         $no_commit_150_224_val = 25 * $no_commit_150_224;
    //     } else {
    //         $no_commit_150_224_val = 0;
    //     }
    //     if ($no_commit_225_plus > 0) {
    //         $no_commit_225_plus_val = 100 * $no_commit_225_plus;
    //     } else {
    //         $no_commit_225_plus_val = 0;
    //     }
    //     if ($commit_125_149 > 0) {
    //         $commit_125_149_val = 75 * $commit_125_149;
    //     } else {
    //         $commit_125_149_val = 0;
    //     }
    //     if ($commit_150_224 > 0) {
    //         $commit_150_224_val = 125 * $commit_150_224;
    //     } else {
    //         $commit_150_224_val = 0;
    //     }
    //     return $final = $commit_225_plus_val + $no_commit_150_224_val + $no_commit_225_plus_val + $commit_125_149_val + $commit_150_224_val;
    //  }
    //
    public static function TwoMonthRecord($id)
    {
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
            ->LeftJoin(
                'numberdetails',
                'numberdetails.number',
                'activation_forms.activation_selected_no',
            )
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            ->whereIn('numberdetails.type', ['Platinum', 'Gold'])
            ->whereIn('activation_forms.status', ['1.02', '1.11', '1.08'])
            // ->where('lead_sales.lead_type', )
            // ->where('lead_sales.channel_type', $channel)
            ->where('users.id', trim($id))
            ->where('activation_forms.created_at', '>=', Carbon::now()->submonths(2))
            // ->whereYear('activation_forms.created_at', Carbon::now()->month)
            ->whereYear('activation_forms.created_at', Carbon::now()->year)

            // ->whereBetween('date_time', [$today->startOfMonth(), $today->endOfMonth])
            ->get()
            // ->where('users.id', $id)
            ->count();
    }
}
