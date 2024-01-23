@extends('layouts.app')
{{-- resources/views/report/ --}}

@section('content')
<div class="container">
    {{-- <h3 class="text-left display-6">{{$channel->name}}</h3> --}}
</div>
<div class="container">
    <div class="row">
        <div class="md-offset-4">
            <div class="table-responsive">
                {{-- @foreach ($channel_partner as $channel) --}}
                @php
                $channel = 'Combined';
                @endphp
                <div class="table-responsive">

                <table class="table table-striped table-bordered zero-configuration" style="font-weight:400;width:200px;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="16" style="background:#FFC107">
                                <h3>
                                    Today Summary >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            <th>Agent Name</th>
                            <th>Agent Email</th>
                            {{-- <th>Paid</th> --}}
                            <th>Act
                                {{-- (1.02) --}}
                            </th>
                            <th>GLD
                                {{-- (1.02) --}}
                            </th>
                            <th>PLT
                                {{-- (1.02) --}}
                            </th>
                            <th>>150</th>
                            <th>150 to 225</th>
                            <th>225+</th>

                            {{-- <th>Agents</th> --}}
                            <th>Ver
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>InP
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>AlLD</th>

                            <th>NNV
                                {{-- (1.01) --}}
                            </th>


                            <th>RJ
                                {{-- (1.04) --}}
                            </th>
                            <th>F-Up
                                {{-- (1.03) --}}
                            </th>
                            {{-- <th>Carry Forward</th> --}}
                            <th>Pnt</th>
                            <th>Ave</th>
                            <th>EP</th>
                            <th>FP</th>
                            <th>FA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // dd($callcenter);
                        @endphp
                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}

                        <tr style="background:#6363d2;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            {{-- <td>
                             {{$provider::NumberOfAgent($cc->id)}}
                            </td> --}}
                            <td>
                                {{-- <a href="{{route('myactivation',$cc->id)}}">{{$cc->call_center_name}}</a> --}}
                                <a href="#">{{$cc->call_center_name}}</a>
                                {{-- {{$cc->call_center_name}} --}}
                            </td>
                            <td>
                                {{$cc->email}}
                            </td>

                            <td>
                                {{$a = $provider::CalCenterLeadtypeDateUser($cc->id,'1.02','postpaid',$channel)}}
                            </td>
                            <td>
                                {{ $provider::CalCenterLeadtypePlanDailyUser($cc->id,'Gold','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanDailyUser($cc->id,'Platinum','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_daily_call_centerUser($cc->id,$channel)}}
                                {{-- {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel)}} --}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_dailyUser($cc->id,$channel)}}
                                {{-- {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel)}} --}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_dayUser($cc->id,$channel)}}
                            </td>

                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'verified','postpaid',$channel)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel)}}
                                --}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'inprocess','postpaid',$channel)}}
                            </td>

                                                        <td>
                                {{$provider::AllLeadsCallCenterTodayUser($cc->id,'postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'nonverified','postpaid',$channel)}}
                            </td>

                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'rejected','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'followup','postpaid',$channel)}}
                            </td>
                            {{-- <td>
                                {{$provider::carry_forward($cc->id,'postpaid',$channel)}}
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_user($cc->id,'followup','postpaid',$channel)}}
                            </td>
                            <td>
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_user($cc->id,'followup','postpaid',$channel) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($a != 0)
                                    {{round($fa = $fr/$a)}}
                                @else
                                0
                                    @endif
                            </td>

                        </tr>
                        @endforeach



                    </tbody>
                    <tfoot>
                         <tr style="background:ffeb3b85;color:#000;font-weight:bold;border:none">
                            <td  style="border:none;" colspan="3">
                                {{$channel}}
                            </td>
                            {{-- <td  style="border:none;">
                                {{$channel}}
                            </td> --}}


                            <td>
                                {{$ab = $provider::CalCenterTodayUser($cc->agent_code,'1.02','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayPlan($cc->agent_code,'Gold','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayPlan($cc->agent_code,'Platinum','postpaid',$channel)}}
                            </td>

                            <td>
                                {{$provider::plan_below_150_dailyUser($cc->agent_code,$channel)}}
                                {{-- {{$provider::plan_below_150_daily_call_center_today($cc->call_center_code,,$channel)}} --}}
                                {{-- {{$provider::CalCenterToday($cc->call_center_code,'1.01','postpaid',$channel)}} --}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200User($cc->agent_code,$channel)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_dailyUser($cc->agent_code,$channel)}}
                            </td>
                            <td>
                                {{-- VVV --}}
                                {{$provider::CalCenterTodayUser($cc->agent_code,'verified','postpaid',$channel)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'inprocess','postpaid',$channel)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsTodayUser($cc->agent_code,'postpaid',$channel)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'nonverified','postpaid',$channel)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'rejected','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'followup','postpaid',$channel)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_sumUser($cc->agent_code,'followup','postpaid',$channel)}}
                            </td>
                            <td>
                                @if($ab != 0)
                                {{$k = number_format($provider::plan_sum_sumUser($cc->agent_code,'followup','postpaid',$channel) / $ab,0)}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                                {{-- {{$xxx}} --}}
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($ab != 0)
                                    {{round($fa = $fr/$ab)}}
                                @else
                                0
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>

                <div class="py-2"></div>
                <table class=" table-bordered text-center" style="font-weight:400;">
                    <thead>
                        {{-- @foreach ($channel_partner as $key => $channel) --}}


                        <tr style="background:#673ab7;color:#fff">
                            <th style="width:80px;">{{$channel}}</th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$provider::plan_below_150_dailyUser($cc->agent_code,$channel)}}
                            </th>
                            <th style="width:80px;">150 to 225</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$provider::plan_above_150_200User($cc->agent_code,$channel)}}
                            </th>
                            <th style="width:80px;">225 & Above</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$provider::plan_above_200_dailyUser($cc->agent_code,$channel)}}
                            </th>

                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total_dailyUser($cc->agent_code,$channel)}}
                            </th>
                        </tr>
                        {{-- @endforeach --}}
                    </thead>
                </table>

                                <div class="table-responsive">

                {{--  --}}
                <table class="table table-striped table-bordered zero-configuration" style="font-weight:400;width:200px;">
                 {{-- <table class=" table-bordered text-center" style="font-weight:400;"> --}}
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="16" style="background:#FFC107">
                                <h3>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, Y')}},
                                    Till
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            <th>Agent Name</th>
                            <th>Email</th>
                            {{-- <th>Paid</th> --}}
                            <th>Act
                                {{-- (1.02) --}}
                            </th>
                            <th>GLD
                                {{-- (1.02) --}}
                            </th>
                            <th>PLT
                                {{-- (1.02) --}}
                            </th>
                            <th>>150</th>
                            <th>150 to 225</th>
                            <th>225+</th>

                            {{-- <th>Agents</th> --}}
                            <th>Verified
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>All Leads</th>

                            <th>Non Verified
                                {{-- (1.01) --}}
                            </th>


                            <th>Rejected
                                {{-- (1.04) --}}
                            </th>
                            <th>Follow Up
                                {{-- (1.03) --}}
                            </th>
                            {{-- <th>Carry Forward</th> --}}
                            <th>Point</th>
                            <th>Average</th>
                            <th>EP</th>
                            <th>FP</th>
                            <th>FA</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}
                        <tr style="background:#009688;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            {{-- <td>
                        {{$provider::NumberOfAgent($cc->id)}}
                            </td> --}}
                            <td>
                                <a href="{{route('myactivation',$cc->id)}}">{{$cc->call_center_name}}</a>
                                {{-- {{$cc->call_center_name}} --}}
                            </td>
                            <td>
                                {{$cc->email}}
                            </td>

                            <td>
                                {{$a = $provider::CalCenterLeadtypeUser($cc->id,'1.02','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanUser($cc->id,'Gold','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanUser($cc->id,'Platinum','postpaid',$channel)}}
                            </td>
                            <td>
                                {{-- sweet home --}}
                                {{$provider::plan_below_150_monthly_call_centeruser($cc->id,$channel)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_month_user($cc->id,$channel)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_monthly_user($cc->id,$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'verified','postpaid',$channel)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel)}}
                                --}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'inprocess','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenterUser($cc->id,'postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'nonverified','postpaid',$channel)}}
                            </td>


                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'rejected','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'followup','postpaid',$channel)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = round($provider::plan_sum_monthly_user($cc->id,'followup','postpaid',$channel))}}
                            </td>
                            <td>
                                {{-- A | {{$a}} --}}
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_monthly_user($cc->id,'followup','postpaid',$channel) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                               {{-- ZZZ = {{$zzz}} --}}
                               {{-- YYY = {{$xxx}} --}}
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($a != 0)
                                    {{round($fa = $fr/$a)}}
                                @else
                                0
                                    @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold">
                            <td colspan="3">
                                {{$channel}}
                            </td>


                            <td>
                                {{$a= $provider::CalCenterTotalMonthUser($cc->agent_code,'1.02','postpaid',$channel)}}
                            </td>
                            {{-- <td>
                                {{$a= $provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid',$channel)}}
                            </td> --}}
                                                        <td>
                                {{$provider::CalCenterMonthlyPlanUser($cc->agent_code,'Gold','postpaid',$channel,$call_center_id)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyPlanUser($cc->agent_code,'Platinum','postpaid',$channel,$call_center_id)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_user($cc->agent_code,$channel)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_monthly_user($cc->agent_code,$channel)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_user($cc->agent_code,$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'verified','postpaid',$channel)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel)}}
                                --}}
                            </td>
                             <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'inprocess','postpaid',$channel)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsMonthlyUser($cc->agent_code,'postpaid',$channel)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'nonverified','postpaid',$channel)}}
                            </td>


                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'rejected','postpaid',$channel)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'followup','postpaid',$channel)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_monthly_combine_user($cc->agent_code,'followup','postpaid',$channel)}}
                                {{-- {{round($provider::plan_sum_monthly_combine($cc->id,'followup','postpaid',$channel))}} --}}
                                {{-- {{round($provider::plan_sum_monthly_combine($cc->id,'followup','postpaid',$channel))}} --}}
                            </td>
                            <td>
                                {{-- ACD {{$a}} --}}
                                @if($a != 0)
                                {{-- B --}}
                                {{$k = number_format($point / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                             <td>
                                {{-- Z= {{$zzz}} --}}
                                {{-- Y= {{$xxx}} --}}
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($a != 0)
                                    {{round($fa = $fr/$a)}}
                                @else
                                0
                                    @endif
                            </td>
                            {{-- CalCenterTotalMonth --}}
                        </tr>

                    </tbody>
                </table>
                </div>


                {{-- @endforeach --}}
                @foreach ($channel_partner as $channel)
                <div class="table-responsive">

                <table class="table table-striped table-bordered zero-configuration" style="font-weight:400;width:200px;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="16" style="background:#FFC107">
                                <h3>
                                    Today Summary >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            <th>Agent Name</th>
                            <th>Agent Email</th>
                            {{-- <th>Paid</th> --}}
                            <th>Act
                                {{-- (1.02) --}}
                            </th>
                            <th>GLD
                                {{-- (1.02) --}}
                            </th>
                            <th>PLT
                                {{-- (1.02) --}}
                            </th>
                            <th>>150</th>
                            <th>150 to 225</th>
                            <th>225+</th>

                            {{-- <th>Agents</th> --}}
                            <th>Ver
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>InP
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>AlLD</th>

                            <th>NNV
                                {{-- (1.01) --}}
                            </th>


                            <th>RJ
                                {{-- (1.04) --}}
                            </th>
                            <th>F-Up
                                {{-- (1.03) --}}
                            </th>
                            {{-- <th>Carry Forward</th> --}}
                            <th>Pnt</th>
                            <th>Ave</th>
                            <th>EP</th>
                            <th>FP</th>
                            <th>FA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // dd($callcenter);
                        @endphp
                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}

                        <tr style="background:#6363d2;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            {{-- <td>
                             {{$provider::NumberOfAgent($cc->id)}}
                            </td> --}}
                            <td>
                                <a href="{{route('myactivation',$cc->id)}}">{{$cc->call_center_name}}</a>
                                {{-- {{$cc->call_center_name}} --}}
                            </td>
                            <td>
                                {{$cc->email}}
                            </td>

                            <td>
                                {{$a = $provider::CalCenterLeadtypeDateUser($cc->id,'1.02','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{ $provider::CalCenterLeadtypePlanDailyUser($cc->id,'Gold','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanDailyUser($cc->id,'Platinum','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_daily_call_centerUser($cc->id,$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel->name)}} --}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_dailyUser($cc->id,$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel->name)}} --}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_dayUser($cc->id,$channel->name)}}
                            </td>

                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'inprocess','postpaid',$channel->name)}}
                            </td>

                                                        <td>
                                {{$provider::AllLeadsCallCenterTodayUser($cc->id,'postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'nonverified','postpaid',$channel->name)}}
                            </td>

                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'rejected','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateUser($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            {{-- <td>
                                {{$provider::carry_forward($cc->id,'postpaid',$channel->name)}}
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_user($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            <td>
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_user($cc->id,'followup','postpaid',$channel->name) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($a != 0)
                                    {{round($fa = $fr/$a)}}
                                @else
                                0
                                    @endif
                            </td>

                        </tr>
                        @endforeach



                    </tbody>
                    <tfoot>
                         <tr style="background:ffeb3b85;color:#000;font-weight:bold;border:none">
                            <td  style="border:none;" colspan="3">
                                {{$channel->name}}
                            </td>
                            {{-- <td  style="border:none;">
                                {{$channel->name}}
                            </td> --}}


                            <td>
                                {{$ab = $provider::CalCenterTodayUser($cc->agent_code,'1.02','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayPlan($cc->agent_code,'Gold','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayPlan($cc->agent_code,'Platinum','postpaid',$channel->name)}}
                            </td>

                            <td>
                                {{$provider::plan_below_150_dailyUser($cc->agent_code,$channel->name)}}
                                {{-- {{$provider::plan_below_150_daily_call_center_today($cc->call_center_code,,$channel->name)}} --}}
                                {{-- {{$provider::CalCenterToday($cc->call_center_code,'1.01','postpaid',$channel->name)}} --}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200User($cc->agent_code,$channel->name)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_dailyUser($cc->agent_code,$channel->name)}}
                            </td>
                            <td>
                                {{-- VVV --}}
                                {{$provider::CalCenterTodayUser($cc->agent_code,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'inprocess','postpaid',$channel->name)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsTodayUser($cc->agent_code,'postpaid',$channel->name)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'nonverified','postpaid',$channel->name)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'rejected','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayUser($cc->agent_code,'followup','postpaid',$channel->name)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_sumUser($cc->agent_code,'followup','postpaid',$channel->name)}}
                            </td>
                            <td>
                                @if($ab != 0)
                                {{$k = number_format($provider::plan_sum_sumUser($cc->agent_code,'followup','postpaid',$channel->name) / $ab,0)}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                                {{-- {{$xxx}} --}}
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($ab != 0)
                                    {{round($fa = $fr/$ab)}}
                                @else
                                0
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>

                <div class="py-2"></div>
                <table class=" table-bordered text-center" style="font-weight:400;">
                    <thead>
                        @foreach ($channel_partner as $key => $channel)


                        <tr style="background:{{ $key == 1 ? '#673ab7' : '#000000' }};color:#fff">
                            <th style="width:80px;">{{$channel->name}}</th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$provider::plan_below_150_dailyUser($cc->agent_code,$channel->name)}}
                            </th>
                            <th style="width:80px;">150 to 225</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$provider::plan_above_150_200User($cc->agent_code,$channel->name)}}
                            </th>
                            <th style="width:80px;">225 & Above</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$provider::plan_above_200_dailyUser($cc->agent_code,$channel->name)}}
                            </th>

                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total_dailyUser($cc->agent_code,$channel->name)}}
                            </th>
                        </tr>
                        @endforeach
                    </thead>
                </table>

                @endforeach

                @foreach ($channel_partner as $channel)
                <div class="table-responsive">

                {{--  --}}
                <table class="table table-striped table-bordered zero-configuration" style="font-weight:400;width:200px;">
                 {{-- <table class=" table-bordered text-center" style="font-weight:400;"> --}}
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="16" style="background:#FFC107">
                                <h3>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, Y')}},
                                    Till
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            <th>Agent Name</th>
                            <th>Email</th>
                            {{-- <th>Paid</th> --}}
                            <th>Act
                                {{-- (1.02) --}}
                            </th>
                            <th>GLD
                                {{-- (1.02) --}}
                            </th>
                            <th>PLT
                                {{-- (1.02) --}}
                            </th>
                            <th>>150</th>
                            <th>150 to 225</th>
                            <th>225+</th>

                            {{-- <th>Agents</th> --}}
                            <th>Verified
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>All Leads</th>

                            <th>Non Verified
                                {{-- (1.01) --}}
                            </th>


                            <th>Rejected
                                {{-- (1.04) --}}
                            </th>
                            <th>Follow Up
                                {{-- (1.03) --}}
                            </th>
                            {{-- <th>Carry Forward</th> --}}
                            <th>Point</th>
                            <th>Average</th>
                            <th>EP</th>
                            <th>FP</th>
                            <th>FA</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}
                        <tr style="background:#009688;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            {{-- <td>
                        {{$provider::NumberOfAgent($cc->id)}}
                            </td> --}}
                            <td>
                                <a href="{{route('myactivation',$cc->id)}}">{{$cc->call_center_name}}</a>
                                {{-- {{$cc->call_center_name}} --}}
                            </td>
                            <td>
                                {{$cc->email}}
                            </td>

                            <td>
                                {{$a = $provider::CalCenterLeadtypeUser($cc->id,'1.02','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanUser($cc->id,'Gold','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanUser($cc->id,'Platinum','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{-- sweet home --}}
                                {{$provider::plan_below_150_monthly_call_centeruser($cc->id,$channel->name)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_month_user($cc->id,$channel->name)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_monthly_user($cc->id,$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'inprocess','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenterUser($cc->id,'postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'nonverified','postpaid',$channel->name)}}
                            </td>


                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'rejected','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = round($provider::plan_sum_monthly_user($cc->id,'followup','postpaid',$channel->name))}}
                            </td>
                            <td>
                                {{-- A | {{$a}} --}}
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_monthly_user($cc->id,'followup','postpaid',$channel->name) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                               {{-- ZZZ = {{$zzz}} --}}
                               {{-- YYY = {{$xxx}} --}}
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($a != 0)
                                    {{round($fa = $fr/$a)}}
                                @else
                                0
                                    @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold">
                            <td colspan="3">
                                {{$channel->name}}
                            </td>


                            <td>
                                {{$a= $provider::CalCenterTotalMonthUser($cc->agent_code,'1.02','postpaid',$channel->name)}}
                            </td>
                            {{-- <td>
                                {{$a= $provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid',$channel->name)}}
                            </td> --}}
                                                        <td>
                                {{$provider::CalCenterMonthlyPlanUser($cc->agent_code,'Gold','postpaid',$channel->name,$call_center_id)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyPlanUser($cc->agent_code,'Platinum','postpaid',$channel->name,$call_center_id)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_user($cc->agent_code,$channel->name)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_monthly_user($cc->agent_code,$channel->name)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_user($cc->agent_code,$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                             <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'inprocess','postpaid',$channel->name)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsMonthlyUser($cc->agent_code,'postpaid',$channel->name)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'nonverified','postpaid',$channel->name)}}
                            </td>


                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'rejected','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'followup','postpaid',$channel->name)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_monthly_combine_user($cc->agent_code,'followup','postpaid',$channel->name)}}
                                {{-- {{round($provider::plan_sum_monthly_combine($cc->id,'followup','postpaid',$channel->name))}} --}}
                                {{-- {{round($provider::plan_sum_monthly_combine($cc->id,'followup','postpaid',$channel->name))}} --}}
                            </td>
                            <td>
                                {{-- ACD {{$a}} --}}
                                @if($a != 0)
                                {{-- B --}}
                                {{$k = number_format($point / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                             <td>
                                {{-- Z= {{$zzz}} --}}
                                {{-- Y= {{$xxx}} --}}
                                {{$xtra = $zzz * 75 + $xxx * 225}}
                            </td>
                            <td>
                                {{$fr = $xtra + $point}}
                            </td>
                            <td>
                                @if($a != 0)
                                    {{round($fa = $fr/$a)}}
                                @else
                                0
                                    @endif
                            </td>
                            {{-- CalCenterTotalMonth --}}
                        </tr>

                    </tbody>
                </table>
                </div>

                @endforeach
                <div class="py-2"></div>
                <table class=" table-bordered text-center" style="font-weight:400;">
                    <thead>
                        @foreach ($channel_partner as $key => $channel)

                        <tr style="background:{{ $key == 1 ? '#673ab7' : '#000000' }};color:#fff">
                            <th style="width:80px;">POINTS</th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$p = $provider::plan_below_150_user($cc->agent_code,$channel->name)}}
                            </th>
                            <th>
                                {{$pp =$p*0}}
                            </th>
                            <th style="width:80px;">150 to 225</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$z = $provider::plan_above_150_200_monthly_user($cc->agent_code,$channel->name)}}
                            </th>
                            <th>
                                {{$zz=  $z*75}}
                            </th>
                            <th style="width:80px;">225 & Above</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$k = $provider::plan_above_200_user($cc->agent_code,$channel->name)}}
                            </th>
                            <th>
                                {{$kk = $k*225}}
                            </th>

                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total_user($cc->agent_code,$channel->name)}}
                            </th>
                            <th>
                                {{$pp+$zz+$kk}}
                            </th>
                        </tr>
                        {{-- <tr style="background:{{ $key == 1 ? '#673ab7' : '#000000' }};color:#fff">
                            <th style="width:80px;">{{$channel->name}}</th>
                            <th style="width:80px;">150 & Above</th>
                            <th style="width:80px;">

                                {{$provider::plan_above_150($channel->name)}}
                            </th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$provider::plan_below_150($channel->name)}}
                            </th>
                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total($channel->name)}}
                            </th>
                        </tr> --}}
                        @endforeach
                    </thead>
                </table>
            </div>
            <h4 class="d-none hidden" style="display:none">COMBINE (HEADING WILL CHANGE/REMOVE LATER)</h4>
            <div class="table-responsive hidden d-none" style="display:none">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Today Summary >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Agent</th>
                            <th>All Leads</th>
                            <th>Verified
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>Non Verified
                                {{-- (1.01) --}}
                            </th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>Activated
                                {{-- (1.02) --}}
                            </th>
                            <th>Rejected
                                {{-- (1.04) --}}
                            </th>
                            <th>Follow Up
                                {{-- (1.03) --}}
                            </th>
                            <th>Carry Forward</th>
                            <th>Point</th>
                            <th>Average</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}

                        <tr style="background:#6363d2;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            {{-- <td>
                    {{$provider::NumberOfAgent($cc->id)}}
                            </td> --}}
                            <td>
                                {{$cc->call_center_name}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenterTodayCombine($cc->id,'postpaid')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateCombine($cc->id,'verified','postpaid')}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateCombine($cc->id,'nonverified','postpaid')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateCombine($cc->id,'inprocess','postpaid')}}
                            </td>
                            <td>
                                {{$a = $provider::CalCenterLeadtypeDateCombine($cc->id,'1.02','postpaid')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateCombine($cc->id,'rejected','postpaid')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDateCombine($cc->id,'followup','postpaid')}}
                            </td>
                            <td>
                                {{-- plan_sum --}}
                                0
                            </td>
                            <td>
                                {{round($provider::plan_sum_combine($cc->id,'followup','postpaid'))}}
                            </td>
                            <td>
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_combine($cc->id,'followup','postpaid') / $a,2)}}
                                @else
                                0
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold;border:none">
                            <td colspan="2" style="border:none;">
                                {{-- {{$channel->name}} --}}
                                ALL COMBINE
                            </td>
                            <td>
                                {{$provider::AllLeadsTodayCombine($cc->id,'postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterToday($cc->id,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayCombine($cc->id,'nonverified','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayCombine($cc->id,'inprocess','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayCombine($cc->id,'1.02','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayCombine($cc->id,'rejected','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayCombine($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{-- plan_sum --}}
                                0
                            </td>
                            <td>
                                {{round($provider::plan_sum_combine($cc->id,'followup','postpaid',$channel->name))}}
                            </td>
                            <td>
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_combine($cc->id,'followup','postpaid',$channel->name) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                        </tr>


                    </tbody>
                </table>
                {{--  --}}
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, Y')}},
                                    Till
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Agent</th>
                            <th>All Leads</th>
                            <th>Verified
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>Non Verified
                                {{-- (1.01) --}}
                            </th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>Activated
                                {{-- (1.02) --}}
                            </th>
                            <th>Rejected
                                {{-- (1.04) --}}
                            </th>
                            <th>Follow Up
                                {{-- (1.03) --}}
                            </th>
                            <th>Carry Forward</th>
                            <th>Point</th>
                            <th>Average</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}
                        <tr style="background:#009688;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            {{-- <td>
                            {{$provider::NumberOfAgent($cc->id)}}
                            </td> --}}
                            <td>
                                {{$cc->call_center_name}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenterCombine($cc->id,'postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeCombine($cc->id,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeCombine($cc->id,'nonverified','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeCombine($cc->id,'inprocess','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$a = $provider::CalCenterLeadtypeCombine($cc->id,'1.02','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{round($provider::CalCenterLeadtypeCombine($cc->id,'rejected','postpaid',$channel->name))}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeCombine($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{-- plan_sum --}}
                                0
                            </td>
                            <td>
                                {{$provider::plan_sum_monthly_combine($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{-- A | {{$a}} --}}
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_monthly_combine($cc->id,'followup','postpaid',$channel->name) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold;border:none">
                            <td colspan="2" style="border:none;">
                                ALL COMBINE
                            </td>
                            <td>
                                {{$provider::AllLeadsMonthlyCombine($cc->id,'postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyCombine($cc->id,'verified','postpaid',$channel->name)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel->name)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyCombine($cc->id,'nonverified','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyCombine($cc->id,'inprocess','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyCombine($cc->id,'1.02','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyCombine($cc->id,'rejected','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyCombine($cc->id,'followup','postpaid',$channel->name)}}
                            </td>
                            <td>
                                {{-- plan_sum --}}
                                0
                            </td>
                            <td>
                                {{round($provider::plan_sum_combine($cc->id,'followup','postpaid',$channel->name))}}
                            </td>
                            <td>
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_combine($cc->id,'followup','postpaid',$channel->name) / $a,2)}}
                                @else
                                0
                                @endif
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    a {
    color: #fff;
    text-decoration: none;
}
</style>
{{-- <x-footer></x-footer> --}}
{{-- @endforeach --}}
@endsection
