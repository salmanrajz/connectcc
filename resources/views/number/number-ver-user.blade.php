@extends('layouts.num-app')

@section('content')
<div class="form-container">

    <div class="container-fluid float-left left">
        {{-- <button class="btn btn-success float-left left" onclick="NumberDtl('Available','{{route('ajaxRequest.NumberByType2')}}')">Show All Number</button> --}}
        <button class="btn btn-info float-left left" onClick="window.location.reload();">Refresh List</button>
        {{-- <button class="btn btn-dark float-left left" onclick="NumberDtl('Active','{{route('ajaxRequest.NumberByType2')}}')">Show Remove Number</button> --}}
    {{-- </div> --}}
    @if (auth()->user()->agent_code != 'ARF')
        <a class="btn btn-success" href="{{route('lead.show',1)}}">Lead Dashboard</a>
        @endif
    {{-- <div class="container-fluid float-right right"> --}}
        {{-- <li><a href="{{route('logout')}}"><i class="icon-power"></i> <span>Logout</span></a> --}}
        <a class="btn btn-success float-right right" href="{{route('logout')}}">Logout  ({{auth()->user()->name}})</a>
    </div>

</div>
<h3 class="text-center">
    Reserved Number List
</h3>
@if (auth()->user()->agent_code == 'ARF')
<div class="" id="broom">
    <table class="table ">
        <thead>
            <tr>
                <th>Number</th>
                <th>Saler Name</th>
                <th>Agent Group</th>
                <th>Type</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td style="font-size:18px;">
                    {{$item->number}}
                </td>
                <td style="font-size:18px;">
                    {{$item->name}}
                </td>
                <td style="font-size:18px;">
                    {{$item->UserAgentGroup}}
                </td>
                <td style="font-size:18px;">
                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
                    {{-- {{$item->created_at}} --}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@else
<div class="table-responsive" id="broom">
    <table class="table table-striped table-bordered zero-configuration">
       <thead>
            <tr>
                <th>Number</th>
                <th>Saler Name</th>
                <th>Agent Group</th>
                <th>Date</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td style="font-size:18px;">
                    {{$item->number}}
                </td>
                <td style="font-size:18px;">
                    {{$item->name}}
                </td>
                <td style="font-size:18px;">
                    {{$item->UserAgentGroup}}
                </td>
                <td style="font-size:18px;">
                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
                    {{-- {{$item->created_at}} --}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td>
                {{-- <a href="#" onclick="HoldNum('{{$item->id}}','{{route('ajaxRequest.HoldNum')}}','{{$item->cid}}')" class="btn btn-success">Lead</a> --}}
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>@endif

@endsection
