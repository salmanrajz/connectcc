@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h3 class="text-left display-6">{{$channel_partner}}</h3> --}}
</div>
<div class="container">
    <div class="row">
        <div class="md-offset-4">
            <div class="row">
                <button class="btn btn-success ml-3 mb-3" onclick="window.location.href='{{url('MainReport/ExpressDial')}}'">ED</button>
                <button class="btn btn-primary ml-1 mb-3" onclick="window.location.href='{{url('MainReport/TTF')}}'">TTF</button>
                <button class="btn btn-primary ml-1 mb-3" onclick="window.location.href='{{url('MainReport/MWH')}}'">MWH</button>
                <button class="btn btn-info ml-1 mb-3" onclick="window.location.href='{{url('MainReport/Combined')}}'">Combined</button>
                {{-- <button class="btn btn-primary ml-1 mb-3" onclick="window.location.href='{{url('MainReport/TTF')}}'>TTF</button> --}}
            </div>
            <div class="table-responsive">
                {{-- @foreach ($channel_partner as $channel) --}}

                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    {{$channel_name}} - {{$channel_partner}} - Today Summary >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            <th>Call Center</th>
                            {{-- <th>Paid</th> --}}
                            <th>Act
                                {{-- (1.02) --}}
                            </th>
                            <th>MNP Act
                                {{-- (1.02) --}}
                            </th>
                            <th>VR.
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>In P
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>LT
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
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
                            <th>PV
                                {{-- (1.02) --}}
                            </th>
                            {{-- <th>Agents</th> --}}


                            <th>AL</th>

                            <th>Non V
                                {{-- (1.01) --}}
                            </th>


                            <th>Rej
                                {{-- (1.04) --}}
                            </th>
                            <th>F-Up
                                {{-- (1.03) --}}
                            </th>
                            {{-- <th>Carry Forward</th> --}}
                            <th>Point</th>
                            <th>Ave</th>
                            <th>EP</th>
                            <th>FP</th>
                            <th>FA</th>
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
                             {{$provider::NumberOfAgent($cc->call_center_code)}}
                            </td> --}}
                            <td>
                                <a href="{{route('daily.report.callcenter',$cc->call_center_name)}}">{{$cc->call_center_name}}</a>
                            </td>
                            {{-- <td>
                                {{$provider::TotalPaidCallAgent($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td> --}}
                            <td>
                                {{$a = $provider::CalCenterLeadtypeDate($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$mnp_act = $provider::CalCenterLeadtypeDateMNP($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'verified','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                                                       <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'inprocess','postpaid',$channel_partner)}}
                            </td>
                            </td>
                                                       <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.06','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{ $provider::CalCenterLeadtypePlanDaily($cc->call_center_code,'Gold','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanDaily($cc->call_center_code,'Platinum','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_daily_call_center($cc->call_center_code,$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel_partner)}} --}}
                            </td>
                            <td>
                                {{$zzz =$provider::plan_above_150_200_daily($cc->call_center_code,$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel_partner)}} --}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_day($cc->call_center_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'1.01','postpaid',$channel_partner)}}
                            </td>




                                                        <td>
                                {{$provider::AllLeadsCallCenterToday($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'nonverified','postpaid',$channel_partner)}}
                            </td>

                            <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'rejected','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeDate($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                            </td>
                            {{-- <td>
                                {{$provider::carry_forward($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td> --}}
                            <td>
                                {{ $point =$provider::plan_sum($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{-- MY ZERO --}}
                                @if($a != 0)
                                @php $zzz_act = $mnp_act + $a; @endphp
                                {{$k = number_format($provider::plan_sum($cc->call_center_code,'followup','postpaid',$channel_partner) / $zzz_act,2)}}
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
                                @php $zzz_act = $mnp_act + $a; @endphp

                                    {{round($fa = $fr/$zzz_act)}}
                                @else
                                0
                                    @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold;border:none">
                            <td colspan="2" style="border:none;">
                                {{$channel_name}}
                            </td>

                            {{-- <td>
                                {{$provider::TotalPaid($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td> --}}
                            <td>
                                {{$ab = $provider::CalCenterToday($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$ab_mnp_act = $provider::CalCenterTodayMNP($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterToday($cc->call_center_code,'verified','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterToday($cc->call_center_code,'inprocess','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterToday($cc->call_center_code,'1.06','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayPlan($cc->call_center_code,'Gold','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTodayPlan($cc->call_center_code,'Platinum','postpaid',$channel_partner)}}
                            </td>

                            <td>
                                {{$provider::plan_below_150_daily($channel_partner)}}
                                {{-- {{$provider::plan_below_150_daily_call_center_today($cc->call_center_code,,$channel_partner)}} --}}
                                {{-- {{$provider::CalCenterToday($cc->call_center_code,'1.01','postpaid',$channel_partner)}} --}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200($channel_partner)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_daily($channel_partner)}}
                            </td>
                            <td>
                                {{-- s --}}
                                {{$provider::CalCenterToday($cc->call_center_code,'1.01','postpaid',$channel_partner)}}
                            </td>


                                                        <td>
                                {{$provider::AllLeadsToday($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td>

                            <td>
                                {{$provider::CalCenterToday($cc->call_center_code,'nonverified','postpaid',$channel_partner)}}
                            </td>

                            <td>
                                {{$provider::CalCenterToday($cc->call_center_code,'rejected','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterToday($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $provider::plan_sum_sum($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                @if($ab != 0)
                                @php $zzz_act = $ab_mnp_act + $ab; @endphp
                                {{$k = number_format($provider::plan_sum_sum($cc->call_center_code,'followup','postpaid',$channel_partner) / $zzz_act,0)}}
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


                    </tbody>
                </table>

                <div class="py-2"></div>
                <table class=" table-bordered text-center" style="font-weight:400;">
                    <thead>
                        {{-- @foreach ($channel_partner as $key => $channel) --}}


                        <tr style="background:#000000;color:#fff">
                            <th style="width:80px;">{{$channel_name}}</th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$provider::plan_below_150_daily($channel_partner)}}
                            </th>
                            <th style="width:80px;">150 to 225</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$provider::plan_above_150_200($channel_partner)}}
                            </th>
                            <th style="width:80px;">225 & Above</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$provider::plan_above_200_daily($channel_partner)}}
                            </th>

                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total_daily($channel_partner)}}
                            </th>
                        </tr>
                        {{-- @endforeach --}}
                    </thead>
                </table>

                {{-- @endforeach --}}
                {{-- @foreach ($channel_partner as $channel) --}}

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
                            <th>Name of Call Center</th>
                            <th>FC</th>
                            {{-- <th>Paid</th> --}}
                            <th>Act
                                {{-- (1.02) --}}
                            </th>
                            <th>MNP Act
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
                            <th>Verified
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10,1.02) --}}
                            </th>
                            <th>LTR
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
                            <th>ER</th>
                            <th>TA</th>
                            <th>ASA</th>
                            <th>EAA</th>
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
                        {{$provider::NumberOfAgent($cc->call_center_code)}}
                            </td> --}}
                            <td>
                                <a href="{{route('daily.report.callcenter',$cc->call_center_name)}}">{{$cc->call_center_name}}</a>
                                {{-- {{$cc->call_center_name}} --}}
                            </td>
                            <td>
                                @php
                                $a = $provider::CalCenterLeadtype($cc->call_center_code,'1.02','postpaid',$channel_partner);
                                @endphp
                                {{-- {{date('d')}} --}}
                                @if($a == 0)
                                {{$forecase = 0}}
                                @else
                                {{-- {{$a}}-{{date('d')}} --}}
                                {{-- {{($a/date('d'))}} --}}
                                @php
                                $data = date('d');
                                 $fr = round($a / $data,2);
                                echo $forecase = round($fr * 30)
                                @endphp
                                @endif
                            </td>
                            {{-- <td>
                                {{$provider::TotalPaidMonthlyCallCenter($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td> --}}
                            <td>
                                {{$a}}
                                {{-- {{$a = $provider::CalCenterLeadtype($cc->call_center_code,'1.02','postpaid',$channel_partner)}} --}}
                            </td>
                            <td>
                                {{$mnp_act = $provider::CalCenterLeadtypeMNP($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                                {{-- {{}} --}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlan($cc->call_center_code,'Gold','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlan($cc->call_center_code,'Platinum','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_monthly_call_center($cc->call_center_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_month($cc->call_center_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_monthly($cc->call_center_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtype($cc->call_center_code,'verified','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtype($cc->call_center_code,'1.06','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtype($cc->call_center_code,'inprocess','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenter($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtype($cc->call_center_code,'nonverified','postpaid',$channel_partner)}}
                            </td>


                            <td>
                                {{$provider::CalCenterLeadtype($cc->call_center_code,'rejected','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtype($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$point = round($provider::plan_sum_monthly($cc->call_center_code,'followup','postpaid',$channel_partner))}}
                            </td>
                            <td>
                                {{-- A | {{$a}} --}}
                                @if($a != 0)
                                @php $zzz_act = $a + $mnp_act; @endphp
                                {{$k = number_format($provider::plan_sum_monthly($cc->call_center_code,'followup','postpaid',$channel_partner) / $zzz_act,2)}}
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
                                    @php $zzz_act = $a + $mnp_act; @endphp
                                    {{round($fa = $fr/$zzz_act)}}
                                @else
                                0
                                    @endif
                            </td>
                            <td style="background-color:#FFC107;color:black;font-weight:1000">
                                @php
                                    // $now = Carbon::now();
                                    // if($now->month == 2){
                                    //     $days = 28;
                                    // }else{
                                    //     $days = 31;
                                    // }
                                    $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
                                    $rfc = $fr  / date('d');
                                    echo $fff=  round($rfc*$daysInMonth);
                                @endphp
                                {{-- {{$fr/30}} --}}
                            </td>
                            <td style="background-color:#09a7cb;color:black;font-weight:1000">
                                {{$agent = $provider::CallCenterAgent($cc->call_center_code)}}
                            </td>
                            <td style="background-color:#aa09cb;color:black;font-weight:1000">
                                @php
                                $agent = $provider::CallCenterAgent($cc->call_center_code);
                                // echo $av = $a/$agent;
                                if($agent == 0)
                                {
                                    echo 0;
                                }
                                else{
                                    $zzz_act = $a + $mnp_act;
                                    echo $av = round($zzz_act/$agent,2);
                                }
                                @endphp
                            </td>
                            <td>
                                @if($forecase == 0)
                                0
                                @else
                                {{round($forecase / $agent,2)}}
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold">
                            <td colspan="2">
                                {{$channel_name}}
                            </td>

                            {{-- <td>
                                {{$provider::TotalPaidMonthly($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td> --}}
                            <td>
                                @php
                                $a = $provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid',$channel_partner);
                                @endphp
                                {{-- {{date('d')}} --}}
                                @if($a == 0)
                                {{$forecase = 0}}
                                @else
                                {{-- {{$a}}-{{date('d')}} --}}
                                {{-- {{($a/date('d'))}} --}}
                                @php
                                $data = date('d');
                                 $fr = round($a / $data,2);
                                echo $forecase = round($fr * 30)
                                @endphp
                                @endif
                            </td>

                            <td>
                                {{$a}}
                            </td>
                            <td>
                                {{$mnp_act = $provider::CalCenterTotalMonthMNP($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterMonthlyPlan($cc->call_center_code,'Gold','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyPlan($cc->call_center_code,'Platinum','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150($channel_partner)}}
                            </td>
                            <td>
                                {{$zzz= $provider::plan_above_150_200_monthly($channel_partner)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200($channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonth($cc->call_center_code,'verified','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonth($cc->call_center_code,'1.06','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                             <td>
                                {{$provider::CalCenterTotalMonth($cc->call_center_code,'inprocess','postpaid',$channel_partner)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsMonthly($cc->call_center_code,'postpaid',$channel_partner)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTotalMonth($cc->call_center_code,'nonverified','postpaid',$channel_partner)}}
                            </td>


                            <td>
                                {{$provider::CalCenterTotalMonth($cc->call_center_code,'rejected','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonth($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $total_2 = $provider::plan_sum_monthly_combine($cc->call_center_code,'followup','postpaid',$channel_partner)}}
                                {{-- {{round($provider::plan_sum_monthly_combine($cc->call_center_code,'followup','postpaid',$channel_partner))}} --}}
                                {{-- {{round($provider::plan_sum_monthly_combine($cc->call_center_code,'followup','postpaid',$channel_partner))}} --}}
                            </td>
                            <td>
                                @if($a != 0)
                                @php $zzz_act = $a + $mnp_act; @endphp
                                {{$k = number_format($provider::plan_sum_monthly_combine($cc->call_center_code,'followup','postpaid',$channel_partner) / $zzz_act,0)}}
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
                                @php
                                $zzz_act = $a + $mnp_act;
                                @endphp
                                    {{round($fa = $fr/$zzz_act)}}
                                @else
                                0
                                    @endif
                            </td>
                            <td style="background-color:#FFC107;color:black;font-weight:1000">
                                @php
                                $daysInMonth = \Carbon\Carbon::now()->daysInMonth;

                                    $rfc = $fr  / date('d');
                                    echo $fff=  round($rfc*$daysInMonth);
                                @endphp
                            </td>
                            <td style="background-color:#09a7cb;color:black;font-weight:1000">
                                {{$agent = $provider::AllAgent($cc->call_center_code)}}
                            </td>
                            <td style="background-color:#aa09cb;color:black;font-weight:1000">
                                @php
                                $agent = $provider::AllAgent($cc->call_center_code);
                                $zzz_act = $a + $mnp_act;
                                if($agent == 0)
                                {
                                    echo 0;
                                }
                                else{
                                    echo $av = round($zzz_act/$agent,2);
                                }
                                @endphp
                            </td>
                            <td>
                                @if($forecase == 0)
                                0
                                @else
                                {{round($forecase / $agent,2)}}
                                @endif
                            </td>
                            {{-- CalCenterTotalMonth --}}
                        </tr>

                    </tbody>
                </table>
                {{-- @endforeach --}}
                <div class="py-2"></div>
                <table class=" table-bordered text-center" style="font-weight:400;">
                    <thead>
                        {{-- @foreach ($channel_partner as $key => $channel) --}}

                        <tr style="background:#673ab7;color:#fff">
                            <th style="width:80px;">POINTS</th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$p = $provider::plan_below_150($channel_partner)}}
                            </th>
                            <th>
                                {{$pp =$p*0}}
                            </th>
                            <th style="width:80px;">150 to 225</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$z = $provider::plan_above_150_200_monthly($channel_partner)}}
                            </th>
                            <th>
                                {{$zz=  $z*75}}
                            </th>
                            <th style="width:80px;">225 & Above</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$k = $provider::plan_above_200($channel_partner)}}
                            </th>
                            <th>
                                {{$kk = $k*225}}
                            </th>

                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$total_plan = $provider::plan_total($channel_partner)}}
                            </th>
                            <th>
                                {{$total_1 = $pp+$zz+$kk}}
                            </th>
                        </tr>
                        {{-- <tr style="background:{{ $key == 1 ? '#673ab7' : '#000000' }};color:#fff">
                            <th style="width:80px;">{{$channel_partner}}</th>
                            <th style="width:80px;">150 & Above</th>
                            <th style="width:80px;">

                                {{$provider::plan_above_150($channel_partner)}}
                            </th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$provider::plan_below_150($channel_partner)}}
                            </th>
                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total($channel_partner)}}
                            </th>
                        </tr> --}}
                        {{-- @endforeach --}}
                    </thead>
                </table>
            </div>
                @if(!empty($total_1))
                {{$ft = $total_1 + $total_2}}
                @else
                {{$ft = 1}}
                {{$total_plan =1}}
                @endif
            <div class="container row">
                <div class="col-md-3 col-sm-3 col-xs-3 col-3">
                    <p>
                @if(isset($total_plan) != 0)
                Average: {{round($ft/$total_plan,3)}}
                @endif
            </p>
            {{-- Golden: {{}} --}}
            <p>
                Gold: {{$provider::plan_category_act('Gold',$channel_partner)}}
            </p>
            {{-- <p>
                Gold Plus: {{$provider::plan_category_act('gold plus',$channel_partner)}}
            </p> --}}
            <p>
                Platinum: {{$provider::plan_category_act('Platinum',$channel_partner)}}
            </p>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 col-3">
                    <p>
                        Daily:
                    </p>
                    <p>
                        TTF: {{$provider::CalCenterToday($cc->call_center_code,'1.02','postpaid','TTF')}}
                    </p>
                    <p>
                        ED: {{$provider::CalCenterToday($cc->call_center_code,'1.02','postpaid','ExpressDial')}}
                    </p>
                    <p>
                        MWH: {{$provider::CalCenterToday($cc->call_center_code,'1.02','postpaid','MWH')}}
                    </p>
                    <p style="font-weight: 1000;color:red">
                        @php
                        $combined = 'Combined';
                        @endphp
                        Combined: <span style="font-weight: 1000;color:red">{{$provider::CalCenterToday($cc->call_center_code,'1.02','postpaid',$combined)}}</span>
                    </p>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 col-3">
                    <p>
                        Monthly:
                    </p>
                    <p>
                        TTF: {{$provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid','TTF')}}
                    </p>
                    <p>
                        ED: {{$provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid','ExpressDial')}}
                    </p>
                    <p>
                        MWH: {{$provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid','MWH')}}
                    </p>
                    @php
                        $combined = 'Combined';
                    @endphp
                    <p style="font-weight: 1000;color:red">
                        Combined: <span >{{$provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid',$combined)}}</span>
                    </p>
                </div>
            </div>
            {{-- <h4 class="d-none hidden" style="display:none">COMBINE (HEADING WILL CHANGE/REMOVE LATER)</h4> --}}
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Emirate Summary TTF >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Emirates</th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>
                                Active
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        // $emirates = \App\emirate::orderby('name','asc')->get();
                        // $emirates = \App\emirate::all();
                        @endphp
                        {{-- @foreach ($emirates as $key => $item) --}}
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    1
                                </td>
                                <td>
                                   Dubai
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','1.02','postpaid','TTF')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    2
                                </td>
                                <td>
                                   Sharjah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','1.02','postpaid','TTF')}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    3
                                </td>
                                <td>
                                    Ajman
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','1,02','postpaid','TTF')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    4
                                </td>
                                <td>
                                    Umm Ul Quwain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','1.02','postpaid','TTF')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    5
                                </td>
                                <td>
                                    RAK
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','1.02','postpaid','TTF')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    6
                                </td>
                                <td>
                                    Fujairah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','1.02','postpaid','TTF')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    8
                                </td>
                                <td>
                                    Khorfakhan
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','1.02','postpaid','TTF')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    7
                                </td>
                                <td>
                                    Abu Dhabi
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','1.02','postpaid','TTF')}}
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    9
                                </td>
                                <td>
                                    Al Ain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','inprocess','postpaid','TTF')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','1.02','postpaid','TTF')}}
                                </td>
                            </tr>
                        {{-- @endforeach --}}


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                TTF
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','inprocess','postpaid','TTF')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','1.02','postpaid','TTF')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Emirate Summary Express Dial >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Emirates</th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>
                                Active
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        $emirates = \App\emirate::orderby('name','asc')->get();
                        @endphp
                        <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    1
                                </td>
                                <td>
                                    Dubai
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    2
                                </td>
                                <td>
                                    Sharjah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    3
                                </td>
                                <td>
                                    Ajman
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    4
                                </td>
                                <td>
                                    Umm ul Quwain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    5
                                </td>
                                <td>
                                    Rak
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    6
                                </td>
                                <td>
                                   Fujairah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    8
                                </td>
                                <td>
                                    Khorfakhan
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    7
                                </td>
                                <td>
                                    Abu Dhabi
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    9
                                </td>
                                <td>
                                    Al Ain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','inprocess','postpaid','ExpressDial')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','1.02','postpaid','ExpressDial')}}
                                </td>
                            </tr>


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                Express Dial
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','inprocess','postpaid','ExpressDial')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','1.02','postpaid','ExpressDial')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Emirate Summary MWH >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Emirates</th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>
                                Active
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        $emirates = \App\emirate::orderby('name','asc')->get();
                        @endphp
                        <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    1
                                </td>
                                <td>
                                    Dubai
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','1.02','postpaid','MWH')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    2
                                </td>
                                <td>
                                    Sharjah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','1.02','postpaid','MWH')}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    3
                                </td>
                                <td>
                                    Ajman
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','1.02','postpaid','MWH')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    4
                                </td>
                                <td>
                                    Umm ul Quwain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','1.02','postpaid','MWH')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    5
                                </td>
                                <td>
                                    Rak
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','1.02','postpaid','MWH')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    6
                                </td>
                                <td>
                                   Fujairah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','1.02','postpaid','MWH')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    8
                                </td>
                                <td>
                                    Khorfakhan
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','1.02','postpaid','MWH')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    7
                                </td>
                                <td>
                                    Abu Dhabi
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','1.02','postpaid','MWH')}}
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    9
                                </td>
                                <td>
                                    Al Ain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','inprocess','postpaid','MWH')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','1.02','postpaid','MWH')}}
                                </td>
                            </tr>


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                MWH
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','inprocess','postpaid','MWH')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','1.02','postpaid','MWH')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Emirate Summary Combined >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Emirates</th>
                            <th>In Process
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>Active
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        $emirates = \App\emirate::orderby('name','asc')->get();
                        @endphp
                        <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    1
                                </td>
                                <td>
                                    Dubai
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Dubai','1.02','postpaid','Combined')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    2
                                </td>
                                <td>
                                    Sharjah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Sharjah','1.02','postpaid','Combined')}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    3
                                </td>
                                <td>
                                    Ajman
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Ajman','1.02','postpaid','Combined')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    4
                                </td>
                                <td>
                                    Umm ul Quwain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Umm ul Quwain','1.02','postpaid','Combined')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    5
                                </td>
                                <td>
                                    RAK
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('RAK','1.02','postpaid','Combined')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    6
                                </td>
                                <td>
                                    Fujairah
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Fujairah','1.02','postpaid','Combined')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    8
                                </td>
                                <td>
                                    Khorfakhan
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Khorfakhan','1.02','postpaid','Combined')}}
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    7
                                </td>
                                <td>
                                    Abu Dhabi
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Abu Dhabi','1.02','postpaid','Combined')}}
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    {{-- {{++$key}} --}}
                                    9
                                </td>
                                <td>
                                    Al Ain
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','inprocess','postpaid','Combined')}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadEmirate('Al Ain','1.02','postpaid','Combined')}}
                                </td>
                            </tr>


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                Combined
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','inprocess','postpaid','Combined')}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadEmirateAll('Dubai','1.02','postpaid','Combined')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Category Summary Combined >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Plans</th>
                            <th>Active #
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        // $emirates = \App\emirate::orderby('name','asc')->get();
                        $collection = \App\numberdetail::select("numberdetails.type")
                        // ->where("remarks.user_agent_id", auth()->user()->id)
                        // ->where("numberdetails.id", $request->id)
                        ->where("numberdetails.status", 'Active')
                        ->where("numberdetails.type",'!=', 'Active')
                        // ->wherein('numberdetails.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                        ->wherein('numberdetails.channel_type', ['TTF'])
                        ->groupBy('numberdetails.type')
                        ->get();
                        @endphp
                        @foreach ($collection as $key => $item)
                        <tr>
                                <td>
                                    {{++$key}}
                                </td>
                                <td>
                                    {{$item->type}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadCategory($item->type,'active','postpaid','TTF')}}
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                TTF
                            </td>
                            <td>
                                {{$provider::CalCenterLeadCategoryAll('Dubai','inprocess','postpaid','TTF')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}
            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Category Summary Combined >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Plans</th>
                            <th>Active #
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        // $emirates = \App\emirate::orderby('name','asc')->get();
                        $collection = \App\numberdetail::select("numberdetails.type")
                        // ->where("remarks.user_agent_id", auth()->user()->id)
                        // ->where("numberdetails.id", $request->id)
                        ->where("numberdetails.status", 'Active')
                        ->where("numberdetails.type",'!=', 'Active')
                        // ->wherein('numberdetails.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                        ->wherein('numberdetails.channel_type', ['ExpressDial'])
                        ->groupBy('numberdetails.type')
                        ->get();
                        @endphp
                        @foreach ($collection as $key => $item)
                        <tr>
                                <td>
                                    {{++$key}}
                                </td>
                                <td>
                                    {{$item->type}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadCategory($item->type,'active','postpaid','ExpressDial')}}
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                ExpressDial
                            </td>
                            <td>
                                {{$provider::CalCenterLeadCategoryAll('Dubai','inprocess','postpaid','ExpressDial')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Category Summary Combined >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Plans</th>
                            <th>Active #
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        // $emirates = \App\emirate::orderby('name','asc')->get();
                        $collection = \App\numberdetail::select("numberdetails.type")
                        // ->where("remarks.user_agent_id", auth()->user()->id)
                        // ->where("numberdetails.id", $request->id)
                        ->where("numberdetails.status", 'Active')
                        ->where("numberdetails.type",'!=', 'Active')
                        // ->wherein('numberdetails.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                        ->wherein('numberdetails.channel_type', ['MWH'])
                        ->groupBy('numberdetails.type')
                        ->get();
                        @endphp
                        @foreach ($collection as $key => $item)
                        <tr>
                                <td>
                                    {{++$key}}
                                </td>
                                <td>
                                    {{$item->type}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadCategory($item->type,'active','postpaid','MWH')}}
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                MWH
                            </td>
                            <td>
                                {{$provider::CalCenterLeadCategoryAll('Dubai','inprocess','postpaid','MWH')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
            <div class="table-responsive">
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    Monthly Category Summary Combined >>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('l')}},
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Plans</th>
                            <th>Active #
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background:#009688;color:#fff">

                        @php
                        // $emirates = \App\emirate::orderby('name','asc')->get();
                        $collection = \App\numberdetail::select("numberdetails.type")
                        // ->where("remarks.user_agent_id", auth()->user()->id)
                        // ->where("numberdetails.id", $request->id)
                        ->where("numberdetails.status", 'Active')
                        ->where("numberdetails.type",'!=', 'Active')
                        ->wherein('numberdetails.channel_type', ['TTF','ExpressDial','MWH','Ideacorp'])
                        // ->wherein('numberdetails.channel_type', ['ExpressDial'])
                        ->groupBy('numberdetails.type')
                        ->get();
                        @endphp
                        @foreach ($collection as $key => $item)
                        <tr>
                                <td>
                                    {{++$key}}
                                </td>
                                <td>
                                    {{$item->type}}
                                </td>
                                <td>
                                    {{$provider::CalCenterLeadCategory($item->type,'active','postpaid','Combined')}}
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                Combined
                            </td>
                            <td>
                                {{$provider::CalCenterLeadCategoryAll('Dubai','inprocess','postpaid','Combined')}}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--  --}}

            </div>
        </div>
    </div>
</div>
{{-- @endforeach --}}
@endsection
