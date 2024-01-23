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
                <h3 style="background:black;color:#fff">
                    {{$item->call_center_name}}
                    {{Carbon\Carbon::now()->daysInMonth}}
                </h3>
                @inject('provider', 'App\Http\Controllers\ReportController')

                <table class="table-bordered text-center" style="font-weight:400;">
                    <thead>
                        <tr style="background:#FFC107">
                            <th class="name-col" style="background:black;color:#fff">Agent Name</th>
                            <th>
                                @php
                                $startDate = \Carbon\Carbon::now(); //returns current day
                                $now = $startDate->firstOfMonth();
                                echo $now->format('M d, Y')
                                @endphp
                            </th>
                            @php
                            // $firstDay = $startDate->firstOfMonth();

                            // $now = \Carbon\Carbon::now();
                            $startDate = \Carbon\Carbon::now(); //returns current day
                            $now = $startDate->firstOfMonth();


                            $dates = [$now->format('M d, Y')];

                            for($i = 0; $i < Carbon\Carbon::now()->daysInMonth - 1; $i++) {
                                echo '<th>' . $now->addDays(1)->format('M d, Y') . '</th>';
                                // echo '<th>' . $dates[] = $now->subDays($i)->format('M d, Y') . '</th>';
                                }
                                @endphp
                                <th class="missed-col">Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $itc = $item->call_center_code;
                        $u =
                        \App\User::select('users.*')->whereIn('role',['Sale','NumberAdmin'])->where('agent_code',$item->call_center_code)->get();
                        @endphp
                        @foreach ($u as $item)
                        <tr class="student">
                            <td class="name-col">

                                {{$item->name}}
                            </td>
                            {{-- @if($) --}}
                            @php
                                if($item->name == 'CC202'){
                                    $bg = 'red';
                                }
                                else{
                                    $bg = 'blue';
                                }
                            @endphp
                            <td class="name-col" style="background:{{$bg}}; color:#000">
                                @php
                                $startDate = \Carbon\Carbon::now(); //returns current day
                                $now = $startDate->firstOfMonth();
                                $k = $provider::ActivationUser($item->id,$now->format('Y-m-d'));
                                @endphp
                                @if($k < 2)
                                {{-- <p> --}}
                                    {{$k}}
                                    {{-- p --}}
                                {{-- </p> --}}
                                @else
                                    {{-- <p> --}}
                                        {{$k}}
                                    {{-- </p> --}}

                                    {{-- <style>.scoring_point{background:white;color:#000}</style> --}}
                                    {{-- p --}}
                                @endif
                                {{-- {{$now->format('Y-m-d')}} --}}
                                {{-- {{}} --}}
                            </td>
                            @php
                                $dates = [$now->format('M d, Y')];
                                if($item->name == 'CC202'){
                                    $bg = 'red';
                                }
                                else{
                                    $bg = 'white';
                                }
                            @endphp
                                @for ($i = 0; $i < Carbon\Carbon::now()->daysInMonth - 1; $i++)
                                <td class="scoring_point" style="background:{{$bg}}">
                                    {{-- {{$now->addDays(1)->format('Y-m-d')}} --}}
                                @php $k = $provider::ActivationUser($item->id,$now->addDays(1)->format('Y-m-d')) @endphp
                                {{-- @if ($k < 2 )
                                {{$k}} --}}
                                {{-- <style>.scoring_point{background:red;color:#fff}</style> --}}
                                {{-- @else --}}
                                @if($k < 2)
                                {{$k}}
                                    {{-- p --}}
                                {{-- </p> --}}
                                @else
                                    {{-- <p> --}}
                                        {{$k}}
                                    {{-- </p> --}}

                                    {{-- <style>.scoring_point{background:white;color:#000}</style> --}}
                                    {{-- p --}}
                                @endif
                                </td>
                                @endfor
                                <td  style="background:#FFC107;color:#000">
                                    {{$provider::ActivationUserGrandTotal($item->id)}}
                                    {{-- Grand Total --}}
                                </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td style="background:black;color:#fff">
                                Total Leads
                            </td>
                            <td style="background:black;color:#fff">
                                @php
                                $startDate = \Carbon\Carbon::now(); //returns current day
                                $now = $startDate->firstOfMonth();
                                @endphp
                                {{-- {{$itc}} --}}
                                {{-- {{$now->format('Y-m-d')}} --}}
                                {{$k = $provider::ActivationCallAgent($itc,$now->format('Y-m-d'))}}

                            </td>
                            @php
                            $dates = [$now->format('M d, Y')];
                                @endphp
                                @for ($i = 0; $i < Carbon\Carbon::now()->daysInMonth - 1; $i++)
                                <td style="background:black;color:#fff">
                                    {{-- {{$now->addDays(1)->format('Y-m-d')}} --}}
                                    {{$provider::ActivationCallAgent($itc,$now->addDays(1)->format('Y-m-d'))}}
                                </td>
                                @endfor
                                <td style="background:#FFC107;color:#000">
                                    {{$provider::ActivationCallAgentGrandTotal($itc)}}
                                    {{-- Grand Total --}}
                                </td>
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
