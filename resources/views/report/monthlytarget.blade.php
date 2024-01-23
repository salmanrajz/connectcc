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
                @foreach ($callcenter as $item)
                @php
                    echo $itc = $item->call_center_code;
                @endphp
                <h3 style="background:black;color:#fff">
                    {{$item->call_center_name}}
                    {{Carbon\Carbon::now()->daysInMonth}}
                </h3>
                @inject('provider', 'App\Http\Controllers\ReportController')

                <table class="table-bordered text-center" style="font-weight:400;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Activation</th>
                            <th>Activation Rate</th>
                            <th>Required</th>
                            <th>ShortFall</th>
                        </tr>
                    </thead>
                    <tbody>
                    @inject('HomeCount', 'App\Http\Controllers\HomeController')
                        @php
                            // $firstDay = $startDate->firstOfMonth();

                            // $now = \Carbon\Carbon::now();
                            $startDate = \Carbon\Carbon::now(); //returns current day
                            $now = $startDate->firstOfMonth();
                            $first_date = $startDate->firstOfMonth();
                            $start = \Carbon\Carbon::now()->startOfMonth();
                            // echo $start = \Carbon\Carbon('first day of this month');
                            $dates = [$now->format('M d, Y')];
                            $a = $HomeCount::days('2020-11');
                            $b = $HomeCount::MyCount('300');
                            $c = $b / $a;
                            $c3 = $c*2;
                            $c2 = $c + $c;
                            @endphp
                        {{-- <tr>
                            <td>
                                {{$now->format('M d, Y')}}
                            </td>
                            <td>
                                {{$provider::ActivationCallAgent($itc,$now->format('Y-m-d'))}}
                            </td>
                            <td>
                                {{$k = $provider::ActivationCallAgentBetween($itc,$start,$now->format('Y-m-d'))}}
                            </td>
                            <td>
                                {{$c}}
                            </td>
                            <td>
                                {{$z = $k - $c}}
                            </td>
                        </tr> --}}

                            @for($i = 1; $i < Carbon\Carbon::now()->daysInMonth; $i++)
                                <tr>
                                @if($i == 1)
                                <td>
                                {{$now->format('M d, Y')}}

                                    {{-- {{$a = $now->addDays(1)->format('M d, Y')}} --}}
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                </td>
                                @else
                                <td>
                                    {{$a = $now->addDays(1)->format('M d, Y')}}
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                </td>
                                @endif
                                {{-- // echo $now->addDays(1)->format('M d, Y') ; --}}

                                <td>
                                    {{-- {{$now->format('Y-m-d')}} --}}
                                {{$provider::ActivationCallAgent($itc,$now->format('Y-m-d'))}}
                                     {{-- {{$k = $provider::ActivationUser($item->id,$a)}} --}}
                                </td>
                                <td>
                                    {{-- {{$now->format('Y-m-d')}} --}}
                                    {{$k = $provider::ActivationCallAgentBetween($itc,$start,$now->format('Y-m-d'))}}
                                </td>
                                <td>
                                    {{-- @if($i == 1) --}}
                                    {{-- s/ --}}
                                    {{-- {{$c = $c3}} --}}
                                    {{-- @else --}}
                                {{$m = $c*$i}}
                                    {{-- @endif --}}
                                </td>
                                <td>
                                {{$z = $k - $m}}
                                </td>
                                {{-- } --}}
                                {{-- // @php --}}
                                {{-- // $startDate = \Carbon\Carbon::now(); //returns current day --}}
                                {{-- // $now = $startDate->firstOfMonth(); --}}
                                {{-- // echo $now->format('M d, Y') --}}
                                {{-- //  --}}
                                {{-- <td> --}}

                                {{-- @endphp --}}
                            {{-- </th> --}}
                            </tr>

                            @endfor
                            <tr>
                                {{-- @if($i == 1) --}}
                                {{-- --}}
                                <td>
                                    {{$a = $now->addDays(1)->format('M d, Y')}}
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                </td>
                                {{-- @endif --}}
                                {{-- // echo $now->addDays(1)->format('M d, Y') ; --}}

                                <td>
                                    {{-- {{$now->format('Y-m-d')}} --}}
                                    {{$provider::ActivationCallAgent($itc,$now->format('Y-m-d'))}}
                                     {{-- {{$k = $provider::ActivationUser($item->id,$a)}} --}}
                                </td>
                                <td>
                                    {{-- {{$now->format('Y-m-d')}} --}}
                                    {{$k = $provider::ActivationCallAgentBetween($itc,$start,$now->format('Y-m-d'))}}
                                </td>
                                <td>
                                    {{-- @if($i == 1) --}}
                                    {{-- s/ --}}
                                    {{-- {{$c = $c3}} --}}
                                    {{-- @else --}}
                                {{$m = $c*$i}}
                                    {{-- @endif --}}
                                </td>
                                <td>
                                {{$z = $k - $m}}
                                </td>
                                {{-- } --}}
                                {{-- // @php --}}
                                {{-- // $startDate = \Carbon\Carbon::now(); //returns current day --}}
                                {{-- // $now = $startDate->firstOfMonth(); --}}
                                {{-- // echo $now->format('M d, Y') --}}
                                {{-- //  --}}
                                {{-- <td> --}}

                                {{-- @endphp --}}
                            {{-- </th> --}}
                            </tr>
                    </tbody>

                </table>
                @endforeach
                <h1>
                    Grand Total: ({{$provider::ActivationGrandTotal()}})
                </h1>

            </div>
        </div>
    </div>
    {{-- @endforeach --}}
    @endsection
