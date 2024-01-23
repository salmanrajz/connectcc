@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h3 class="text-left display-6">{{$channel->name}}</h3> --}}
</div>
<div class="container">
    <div class="row">
        <div class="md-offset-4">
            <div class="table-responsive">
                {{-- DailyActivation --}}
                <h1>Progress Report </h1>
                {{-- @foreach ($callcenter as $item)
                @php
                    echo $itc = $item->call_center_code;
                @endphp
                <h3 style="background:black;color:#fff">
                    {{$item->call_center_name}}
                    {{Carbon\Carbon::now()->daysInMonth}}
                </h3> --}}
                @inject('provider', 'App\Http\Controllers\ReportController')

                <table class="table-bordered text-center" style="font-weight:400;">
                    <thead>
                        <tr>
                            <th>S#</th>
                            <th>Date</th>
                            @foreach ($callcenter as $item)
                                <th>{{$item->call_center_name}}</th>
                            @endforeach
                            <th>Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                        // $firstDay = $startDate->firstOfMonth();

                        // $now = \Carbon\Carbon::now();
                        $startDate = \Carbon\Carbon::now(); //returns current day
                        $now = $startDate->firstOfMonth();
                        $first_date = $startDate->firstOfMonth();
                        $start = \Carbon\Carbon::now()->startOfMonth();
                        // echo $start = \Carbon\Carbon('first day of this month');
                        $dates = [$now->format('M d, Y')];
                        // $a = $HomeCount::days('2020-11');
                        // $b = $HomeCount::MyCount('300');
                        // $c = $b / $a;
                        // $c3 = $c*2;
                        // $c2 = $c + $c;
                        @endphp
                    @for($i = 1; $i < Carbon\Carbon::now()->daysInMonth; $i++)
                        <tr>
                            <td>
                                {{$i}}
                            </td>
                           @if($i == 1)
                            <td>
                            {{$now->format('M d, Y')}}
                            </td>
                            @else
                            <td>
                                {{$a = $now->addDays(1)->format('M d, Y')}}
                                {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                            </td>
                            @endif

                            @foreach ($callcenter as $item)
                                <td>
                                {{-- {{$now->format('Y-m-d')}} --}}
                                {{-- {{$a}} --}}
                                {{-- // $a = --}}
                                {{$provider::TotalCountCallCenter($now->format('Y-m-d'),$item->call_center_code,'Postpaid','TTF')}}
                                {{-- ; --}}

                                    {{-- {{$item->call_center_name}} --}}
                                </td>
                            @endforeach
                            <td>
                                {{-- {{$now->format('Y-m-d')}} --}}
                                {{$provider::ActivationGrandTotalDaily($now->format('Y-m-d'))}}
                                {{-- {{0}} --}}
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                <h1>
                    Grand Total: ({{$provider::ActivationGrandTotal()}})
                </h1>

            </div>
        </div>
    </div>
    {{-- @endforeach --}}
    @endsection
