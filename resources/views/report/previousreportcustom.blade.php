@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h3 class="text-left display-6">{{$channel_partner}}</h3> --}}
</div>
<div class="container">
    <div class="row">
        <div class="md-offset-4">
            <div class="table-responsive">

                {{-- @foreach ($channel_partner as $channel) --}}

                {{--  --}}
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\CustomerReportController')
                    <thead>
                        <tr>
                            <th colspan="13" style="background:#FFC107">
                                <h3>
                                    {{-- {{\Carbon\Carbon::now()->submonth(,$start_date,$end_date)}} --}}
                                    {{$start_date}} - {{$end_date}}
                                    {{-- {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, Y',$start_date,$end_date)}}, --}}
                                    {{-- {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::create()->day(1)->month(2))->format('M',$start_date,$end_date)}}, --}}
                                    {{-- Till --}}
                                    {{-- {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y',$start_date,$end_date)}} --}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Name of Call Center</th>
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
                            {{$provider::NumberOfAgent($cc->call_center_code,$start_date,$end_date)}}
                            </td> --}}
                            <td>
                                <a href="{{route('daily.report.callcenter',$cc->call_center_name,$start_date,$end_date)}}">{{$cc->call_center_name}}</a>
                                {{-- {{$cc->call_center_name}} --}}
                            </td>
                            {{-- <td>
                                {{$provider::TotalPaidMonthlyCallCenter($cc->call_center_code,'postpaid',$channel_partner,$start_date,$end_date)}}
                            </td> --}}
                            <td>
                                {{$a = $provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'1.02','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanPreviousCustom($cc->call_center_code,'Gold','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanPreviousCustom($cc->call_center_code,'Platinum','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_monthly_call_center_previousCustom($cc->call_center_code,$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_month_previousCustom($cc->call_center_code,$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_monthly_previousCustom($cc->call_center_code,$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'verified','postpaid',$channel_partner,$start_date,$end_date)}}
                                {{-- {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner,$start_date,$end_date)}}
                                --}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'inprocess','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenterPreviousCustom($cc->call_center_code,'postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'nonverified','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>


                            <td>
                                {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'rejected','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'followup','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$point =round($provider::plan_sum_monthly_previousCustom($cc->call_center_code,'followup','postpaid',$channel_partner,$start_date,$end_date))}}
                            </td>
                            <td>
                                {{-- A | {{$a}} --}}
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_monthly_previousCustom($cc->call_center_code,'followup','postpaid',$channel_partner,$start_date,$end_date) / $a,2)}}
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
                                    {{$fa = $fr/$a}}
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold">
                            <td colspan="2">
                                {{$channel_partner}}
                            </td>

                            {{-- <td>
                                {{$provider::TotalPaidMonthly($cc->call_center_code,'postpaid',$channel_partner,$start_date,$end_date)}}
                            </td> --}}

                            <td>
                                {{$a= $provider::CalCenterTotalMonthPreviousCustom($cc->call_center_code,'1.02','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterMonthlyPlanPreviousCustom($cc->call_center_code,'Gold','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyPlanPreviousCustom($cc->call_center_code,'Platinum','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_previousCustom($channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_monthly_previousCustom($channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_previousCustom($channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthPreviousCustom($cc->call_center_code,'verified','postpaid',$channel_partner,$start_date,$end_date)}}
                                {{-- {{$provider::CalCenterLeadtypePreviousCustom($cc->call_center_code,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner,$start_date,$end_date)}}
                                --}}
                            </td>
                             <td>
                                {{$provider::CalCenterTotalMonthPreviousCustom($cc->call_center_code,'inprocess','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsMonthlyPreviousCustom($cc->call_center_code,'postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTotalMonthPreviousCustom($cc->call_center_code,'nonverified','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>


                            <td>
                                {{$provider::CalCenterTotalMonthPreviousCustom($cc->call_center_code,'rejected','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthPreviousCustom($cc->call_center_code,'followup','postpaid',$channel_partner,$start_date,$end_date)}}
                            </td>
                            {{-- <td>
                                0
                            </td> --}}
                            <td>
                                {{$point = $total_2 = $provider::plan_sum_monthly_combine_previousCustom($cc->call_center_code,'followup','postpaid',$channel_partner,$start_date,$end_date)}}
                                {{-- {{round($provider::plan_sum_monthly_combine_previousCustom($cc->call_center_code,'followup','postpaid',$channel_partner),$start_date,$end_date)}} --}}
                                {{-- {{round($provider::plan_sum_monthly_combine_previousCustom($cc->call_center_code,'followup','postpaid',$channel_partner),$start_date,$end_date)}} --}}
                            </td>
                            <td>
                                @if($a != 0)
                                {{$k = number_format($provider::plan_sum_monthly_combine_previousCustom($cc->call_center_code,'followup','postpaid',$channel_partner,$start_date,$end_date) / $a,2)}}
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
                                    {{$fa = $fr/$a}}
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


                        <tr style="background:{{ 1 == 1 ? '#673ab7' : '#000000' }};color:#fff">
                            <th style="width:80px;">POINTS</th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$p = $provider::plan_below_150_previousCustom($channel_partner,$start_date,$end_date)}}
                            </th>
                            <th>
                                {{$pp =$p*0}}
                            </th>
                            <th style="width:80px;">150 to 225</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$z = $provider::plan_above_150_200_monthly_previousCustom($channel_partner,$start_date,$end_date)}}
                            </th>
                            <th>
                                {{$zz=  $z*75}}
                            </th>
                            <th style="width:80px;">225 & Above</th>
                            <th style="width:80px;">
                                {{-- plan_below_150 --}}
                                {{$k = $provider::plan_above_200_previousCustom($channel_partner,$start_date,$end_date)}}
                            </th>
                            <th>
                                {{$kk = $k*225}}
                            </th>

                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$total_plan = $provider::plan_total_previousCustom($channel_partner,$start_date,$end_date)}}
                            </th>
                            <th>
                                {{$total_1 = $pp+$zz+$kk}}
                            </th>
                        </tr>
                        {{-- <tr style="background:{{ $key == 1 ? '#673ab7' : '#000000' }};color:#fff">
                            <th style="width:80px;">{{$channel_partner}}</th>
                            <th style="width:80px;">150 & Above</th>
                            <th style="width:80px;">

                                {{$provider::plan_above_150($channel_partner,$start_date,$end_date)}}
                            </th>
                            <th style="width:80px;">Less Than 150</th>
                            <th style="width:80px;">
                                {{$provider::plan_below_150($channel_partner,$start_date,$end_date)}}
                            </th>
                            <th style="width:80px;">Total</th>
                            <th style="width:80px;">
                                {{$provider::plan_total($channel_partner,$start_date,$end_date)}}
                            </th>
                        </tr> --}}
                        {{-- @endforeach --}}
                    </thead>
                </table>
            </div>
            {{$ft = $total_1 + $total_2}}
            <p>
                @if($total_plan != 0)
                Average: {{$ft/$total_plan}}
                @endif
            </p>
            {{-- Golden: {{}} --}}
            <p>
                Golden: {{$provider::plan_category_act_previousCustom('Gold',$channel_partner,$start_date,$end_date)}}
            </p>
            <p>
                Platinum: {{$provider::plan_category_act_previousCustom('Platinum',$channel_partner,$start_date,$end_date)}}
            </p>

        </div>
    </div>
</div>
{{-- @endforeach --}}
@endsection
