@extends('layouts.num-app')

@section('content')
@if (auth()->user()->agent_code != 'ARF')

<div class="form-container">

    <div class="container-fluid float-left left">
        {{-- <button class="btn btn-success float-left left" onclick="NumberDtl('Available','{{route('ajaxRequest.NumberByType2')}}')">All Numbers</button> --}}
        <button class="btn btn-info float-left left" onClick="window.location.reload();">Leads</button>
        <button class="btn btn-dark float-left left" onclick="NumberDtl('Active','{{route('ajaxRequest.NumberByType2')}}')">Active</button>
    {{-- </div> --}}
    {{-- <div class="container-fluid float-right right"> --}}
        {{-- <li><a href="{{route('logout')}}"><i class="icon-power"></i> <span>Logout</span></a> --}}
        <a class="btn btn-success float-right right" href="{{route('logout')}}">Logout ({{auth()->user()->name}})</a>
    </div>

</div>
@else
<div class="form-container">

    <div class="container-fluid float-left left">
        <button class="btn btn-success float-left left" onclick="NumberDtl('Available','{{route('ajaxRequest.NumberByType2')}}')">All Number</button>
        <button class="btn btn-info float-left left" onClick="window.location.reload();">Show Reserved Number</button>
        <button class="btn btn-dark float-left left" onclick="NumberDtl('Active','{{route('ajaxRequest.NumberByType2')}}')">Active Number</button>
    {{-- </div> --}}
    {{-- <div class="container-fluid float-right right"> --}}
        {{-- <li><a href="{{route('logout')}}"><i class="icon-power"></i> <span>Logout</span></a> --}}
        <a class="btn btn-success float-right right" href="{{route('logout')}}">Logout ({{auth()->user()->name}})</a>
    </div>

</div>
@endif
<h3 class="text-center">
    Reserved Number List
</h3>
@if (auth()->user()->agent_code == 'ARF')
<div class="" id="broom">
    <table class="table ">
        <thead>
            <tr>
                <th>Number</th>
                <th>Agent Name</th>
                <th>Passcode</th>
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
                    {{$item->passcode}}
                </td>
                <td style="font-size:18px;">
                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
                    {{-- {{$item->created_at}} --}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td>
                <a href="#" onclick="RevNum('{{$item->id}}','{{route('ajaxRequest.RevNum')}}','{{$item->cid}}')" class="btn btn-info">Revive</a>
                <a href="#" onclick="VerifyNum('{{$item->id}}','{{route('ajaxRequest.VerifyNum')}}','{{$item->cid}}')" class="btn btn-success">Remove</a>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@else
<div class="" id="broom">

<table class="table table-striped table-bordered zero-configuration">
       <thead>
            <tr>
                <th>Number</th>
                <th>Agent Name</th>
                <th>Agent Group</th>
                <th>Passcode</th>
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
                    {{$item->passcode}}
                </td>
                <td style="font-size:18px;">
                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
                    {{-- {{$item->created_at}} --}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td>
                <a href="#" onclick="VerifyNum('{{$item->id}}','{{route('ajaxRequest.VerifyNum')}}','{{$item->cid}}')" class="btn btn-success">Active</a>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>

    @endif

@endsection
