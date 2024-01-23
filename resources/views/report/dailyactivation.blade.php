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
                            <th>CM Name</th>
                            <th>CM Number</th>
                            <th>Selected Number</th>
                            {{-- <th>Paid</th> --}}
                            <th>Plan
                                {{-- (1.02) --}}
                            </th>
                            <th>Date of Activation
                                {{-- (1.02) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($call_center_daily as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}

                        <tr style="background:#6363d2;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            <td>
                                {{$cc->customer_name}}
                            </td>
                            <td>
                                {{$cc->customer_number}}
                            </td>
                            <td>
                                {{$cc->activation_selected_no}}
                            </td>
                            <td>
                                {{$cc->plan_name}}
                            </td>
                            <td>
                                {{$cc->activation_date}}
                            </td>
                        </tr>
                        @endforeach



                    </tbody>

                </table>
                </div>



                {{-- @endforeach --}}
                {{-- @foreach ($channel_partner as $channel) --}}
                <div class="table-responsive">

                {{--  --}}
                <table class="table table-striped table-bordered zero-configuration" style="font-weight:400;width:200px;">
                 {{-- <table class=" table-bordered text-center" style="font-weight:400;"> --}}
                    @inject('provider', 'App\Http\Controllers\ReportController')
                    {{-- <h3>
                        {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, Y')}},
                        Till
                        {{$day = \Carbon\Carbon::parse($date = \Carbon\Carbon::today())->format('M, d, Y')}}
                    </h3> --}}
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
                            <th>CM Name</th>
                            <th>CM Number</th>
                            <th>Selected Number</th>
                            {{-- <th>Paid</th> --}}
                            <th>Plan
                                {{-- (1.02) --}}
                            </th>
                            <th>Plan Revenue
                                {{-- (1.02) --}}
                            </th>
                            <th>Date of Activation
                                {{-- (1.02) --}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($call_center_monthly as $k => $cc)
                        {{-- {{$k == 1}} --}}
                        {{-- {{$i == 0}} --}}

                        <tr style="background:#6363d2;color:#fff">
                            <td>
                                {{++$k}}
                            </td>
                            <td>
                                {{$cc->customer_name}}
                            </td>
                            <td>
                                {{$cc->customer_number}}
                            </td>
                            <td>
                                {{$cc->activation_selected_no}}
                            </td>
                            <td>
                                {{$cc->plan_name}}
                            </td>
                            <td>
                                {{$cc->revenue}}
                            </td>
                            <td>
                                {{$cc->activation_date}}
                            </td>
                        </tr>
                        @endforeach



                    </tbody>
                </table>
                </div>

                {{-- @endforeach --}}

            </div>

        </div>
    </div>
</div>
<x-footer></x-footer>
{{-- @endforeach --}}
@endsection
