@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h3 class="text-left display-6">{{$channel->name}}</h3> --}}
</div>
<div class="container">
    <div class="row">
        <div class="md-offset-4">
            <div class="table-responsive">
                @foreach ($channel_partner as $channel)
                <table class=" table-bordered text-center" style="font-weight:400;">
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    <thead>
                        <tr>
                            <th colspan="15" style="background:#FFC107">
                                <h3>
                                    {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M , Y')}}
                                    {{-- Till --}}
                                    {{-- {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}} --}}
                                </h3>
                            </th>
                        </tr>
                        <tr style="background:black;color:#fff">
                            <th>S#</th>
                            {{-- <th>Agents</th> --}}
                            <th>Channel Partner</th>
                            <th>Lead Number</th>
                            <th>Customer Name
                            </th>
                            <th>Customer Number
                            </th>
                            <th>Sub Request Number
                                {{-- (1.01) --}}
                            </th>
                            <th>Lead Creation Date
                                {{-- (1.03,1.05,1.07,1.08,1.09,1.10) --}}
                            </th>
                            <th>Package
                                {{-- (1.02) --}}
                            </th>
                            <th>Lead Assign Date
                                {{-- (1.04) --}}
                            </th>
                            <th>Region
                                {{-- (1.03) --}}
                            </th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Remarks</th>
                            <th>Activation Date</th>
                            <th>PiOINTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $r = \App\lead_sale::select('lead_sales.customer_name','plans.plan_name','lead_sales.selected_number','lead_sales.customer_number','lead_sales.created_at as create_date', 'lead_locations.created_at as assign_date','activation_forms.created_at as activation_date','lead_sales.emirates','lead_locations.location_url','lead_sales.sim_type','activation_forms.activation_sr_no')
                            ->LeftJoin(
                            'activation_forms','activation_forms.lead_id','=','lead_sales.id'
                            )
                            ->LeftJoin(
                            'plans','plans.id','=','lead_sales.select_plan'
                            )
                            ->LeftJoin(
                            'lead_locations',
                            'lead_locations.lead_id','=','lead_sales.id'
                            )
                            ->where('lead_sales.status','1.02')
                            ->where('lead_sales.channel_type',$channel->name)
                            ->get();
                        @endphp
                        @foreach ($r as $key => $item)
                            <tr>
                            <td>{{++$key}}</td>
                            <td>{{$channel->name}}</td>
                                <td>{{++$key}}</td>
                                <td>{{$item->customer_name}}</td>
                                <td>{{$item->selected_number}}</td>
                                <td>{{$item->activation_sr_no}}</td>
                                <td>{{\Carbon\Carbon::parse($item->create_date)->format('d-M-Y')}}</td>
                                <td>{{$item->plan_name}}</td>
                                <td>{{\Carbon\Carbon::parse($item->assign_date)->format('d-M-Y')}}</td>
                                <td>{{$item->emirates}}</td>
                                <td>{{$item->emirates}}</td>
                                <td>Activated</td>
                                <td>{{$item->sim_type}}</td>
                                <td>{{\Carbon\Carbon::parse($item->activation_date)->format('d-M-Y')}}</td>
                                <td>Points</td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                @endforeach
        </div>
    </div>
</div>
{{-- @endforeach --}}
@endsection
