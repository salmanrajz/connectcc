@extends('layouts.app')
{{-- resources/views/report/ --}}

@section('content')
<div class="container">
    {{-- <h3 class="text-left display-6">{{$channel_partner}}</h3> --}}
</div>
<div class="container">
    <div class="row">
        <div class="md-offset-4">
            <div class="table-responsive">
                {{-- @foreach ($channel_partner as $channel) --}}
                <div class="table-responsive">


                {{-- @endforeach --}}
                {{-- @foreach ($channel_partner as $channel) --}}
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

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($callcenter as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}
                        <tr style="background:#009688;color:#fff">
                            <td>
                                @php $k = 0;@endphp
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
                                {{$a = $provider::CalCenterLeadtypeUser($cc->id,'1.02','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanUser($cc->id,'Gold','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypePlanUser($cc->id,'Platinum','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{-- sweet home --}}
                                {{$provider::plan_below_150_monthly_call_centeruser($cc->id,$channel_partner)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_month_user($cc->id,$channel_partner)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_monthly_user($cc->id,$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'verified','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                                                        <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'inprocess','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::AllLeadsCallCenterUser($cc->id,'postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'nonverified','postpaid',$channel_partner)}}
                            </td>


                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'rejected','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterLeadtypeUser($cc->id,'followup','postpaid',$channel_partner)}}
                            </td>


                        </tr>
                        @endforeach
                        <tr style="background:ffeb3b85;color:#000;font-weight:bold">
                            <td colspan="3">
                                {{$channel_partner}}
                            </td>


                            <td>
                                {{$a= $provider::CalCenterTotalMonthUser($cc->agent_code,'1.02','postpaid',$channel_partner)}}
                            </td>
                            {{-- <td>
                                {{$a= $provider::CalCenterTotalMonth($cc->call_center_code,'1.02','postpaid',$channel_partner)}}
                            </td> --}}
                                                        <td>
                                {{$provider::CalCenterMonthlyPlanUser($cc->agent_code,'Gold','postpaid',$channel_partner,$call_center_id)}}
                            </td>
                            <td>
                                {{$provider::CalCenterMonthlyPlanUser($cc->agent_code,'Platinum','postpaid',$channel_partner,$call_center_id)}}
                            </td>
                            <td>
                                {{$provider::plan_below_150_user($cc->agent_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$zzz = $provider::plan_above_150_200_monthly_user($cc->agent_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$xxx = $provider::plan_above_200_user($cc->agent_code,$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'verified','postpaid',$channel_partner)}}
                                {{-- {{$provider::CalCenterLeadtype($cc->id,'1.03,1.05,1.07,1.08,1.09,1.10','postpaid',$channel_partner)}}
                                --}}
                            </td>
                             <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'inprocess','postpaid',$channel_partner)}}
                            </td>
                                                        <td>
                                {{$provider::AllLeadsMonthlyUser($cc->agent_code,'postpaid',$channel_partner)}}
                            </td>

                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'nonverified','postpaid',$channel_partner)}}
                            </td>


                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'rejected','postpaid',$channel_partner)}}
                            </td>
                            <td>
                                {{$provider::CalCenterTotalMonthUser($cc->agent_code,'followup','postpaid',$channel_partner)}}
                            </td>

                        </tr>

                    </tbody>
                </table>
                </div>

                {{-- @endforeach --}}
                <div class="py-2"></div>

            </div>

        </div>
    </div>
</div>
<x-footer></x-footer>
{{-- @endforeach --}}
@endsection
