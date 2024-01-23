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
                <h1>Attendance  Report </h1>
                @foreach ($callcenter as $item)
                @php
                    echo $itc = $item->call_center_code;
                @endphp
                <h3 style="background:black;color:#fff">
                    {{$item->call_center_name}}
                    {{Carbon\Carbon::now()->daysInMonth}}
                </h3>
                @inject('provider', 'App\Http\Controllers\ReportController')
                    @inject('HomeCount', 'App\Http\Controllers\HomeController')

                <table class="table-bordered text-center" style="font-weight:400;">
                    <thead>
                        <tr>
                            <th>Saler ID</th>
                            @php
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
                            @for($i = 1; $i <= Carbon\Carbon::now()->daysInMonth; $i++)
                                <th>
                                @if($i == 1)
                                {{$now->format('M d, Y')}}

                                    {{-- {{$a = $now->addDays(1)->format('M d, Y')}} --}}
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                @else
                                    {{$a = $now->addDays(1)->format('M d, Y')}}
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                @endif

                            @endfor
                        </th>
                    </thead>
                    <tbody>
                        {{-- {{$item->agent_code}} --}}
                        @php
                        $user = \App\User::where('agent_code',$itc)
                        ->whereIn("users.role", array('sale', 'NumberAdmin','FloorManager'))
                        ->get();
                        @endphp
                        @foreach ($user as $item)
                        <tr>
                            <td>
                                {{$item->email}}
                            </td>
                            @php
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
                            @for($i = 1; $i <= Carbon\Carbon::now()->daysInMonth; $i++)

                                @if($i == 1)
                                {{-- {{$now->format('Y-m-d')}} --}}
                                {{-- {{$item->id}} --}}

                                    @php
                                    if($now->format('l') == 'Sunday'){
                                        echo "<td style='background: #feb321;color:white;'> Holiday </td>";
                                    }else{
                                        $checker = \App\attendance_management::whereDate('date',$now->format('Y-m-d'))->where('userid',$item->id)->first();
                                        if($checker){
                                            // echo '<td>' . $checker->status . '</td>';
                                            if($checker->status == 'Absent'){
                                        echo "<td style='background: #fe3021;color:white;'> Absent </td>";

                                            }else if($checker->status == 'Late'){
                                                // echo $checker->timing;
                                        echo "<td style='background: #28fe21;color:white;'> Late (".$checker->timing."))</td>";

                                            }else{
                                            echo "<td style='background: #695429;color:white;'> Present </td>";

                                            }
                                        }else{
                                            echo "<td style='background: #9b21fe;color:white;'> Not Att Yet </td>";
                                        }
                                    }
                                    @endphp
                                    {{-- {{$a = $now->addDays(1)->format('M d, Y')}} --}}
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                @else
                                    {{-- {{$a = $now->addDays(1)->format('M d, Y')}} --}}
                                    @php
                                    // $checker = \App\attendance_management::whereDate('date',$now->addDays(1)->format('Y-m-d'))->where('userid',$item->id)->first();
                                    // if($checker){
                                    //     echo $checker->status;
                                    // }else{
                                    //     echo "Not Att Yet";
                                    // }
                                    if($dateFriday = $now->addDays(1)->format('l') == 'Sunday'){
                                        echo "<td style='background: #feb321;color:white;'> Holiday </td>";
                                    }else{
                                        $checker = \App\attendance_management::whereDate('date',$now->format('Y-m-d'))->where('userid',$item->id)->first();
                                        if($checker){
                                            // echo '<td>' . $checker->status . '</td>';
                                            if($checker->status == 'Absent'){
                                        echo "<td style='background: #fe3021;color:white;'> Absent </td>";

                                            }else if($checker->status == 'Late'){
                                        echo "<td style='background: #28fe21;color:white;'> Late (".$checker->timing.")</td>";

                                        // echo "<td style='background: #28fe21;color:white;'> Late </td>";

                                            }else{
                                            echo "<td style='background: #695429;color:white;'> Present </td>";

                                            }
                                        }else{
                                            echo "<td style='background: #9b21fe;color:white;'> Not Att Yet </td>";
                                        }
                                    }
                                    @endphp
                                    {{-- {{$dates[] = $now->subDays(1)->format('M d, Y')}} --}}
                                @endif
                                {{-- </td> --}}

                            @endfor
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
