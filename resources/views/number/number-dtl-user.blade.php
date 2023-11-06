@extends('layouts.num-app')

@section('content')
@if (auth()->user()->agent_code != 'ARF')

<div class="form-container">

    <div class="container-fluid float-left left">
        {{-- <button class="btn btn-success float-left left" onclick="NumberDtl('Available','{{route('ajaxRequest.NumberByType2')}}')">All Numbers</button> --}}
        <button class="btn btn-info float-left left" onClick="window.location.reload();">Reseved Numbers</button>
        <button class="btn btn-dark float-left left" onclick="NumberDtl('Hold','{{route('ajaxRequest.NumberByType2')}}')">Leads</button>
        <button class="btn btn-dark float-left left" href="{{route('admin.dashboard')}}">Dashboard</button>
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
<div class="table-responsive" id="broom">
    <table class="table table-striped table-bordered zero-configuration">
        <thead>
            <tr>
                <th>Number</th>
                <th>Agent Name</th>
                <th>Channel</th>
                <th>Type</th>
                <th>Time</th>
                <th>Status</th>
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
                    {{$item->channel_type}}
                </td>
                <td style="font-size:18px;">
                    {{$item->type}}
                </td>
                <td style="font-size:18px;">
                    {{-- {{$item->created_at}} --}}
                    {{\Carbon\Carbon::createFromTimeStamp(strtotime($item->datetime))->diffForHumans()}}
                </td>
                <td>
                    {{$item->status}}
                </td>
                <td>
                <a href="{{route('admin.dashboard')}}" class="btn btn-info">For Details View Dashboard</a>
                {{-- <a href="#" onclick="RevNum('{{$item->id}}','{{route('ajaxRequest.RevNum')}}','{{$item->cid}}')" class="btn btn-info">Revive</a> --}}
                {{-- <button type="button" class="btn btn-success " data-toggle="modal" data-target="#myModal">Assign Lead</button> --}}

                {{-- <a href="#" onclick="HoldNum('{{$item->id}}','{{route('ajaxRequest.HoldNum')}}','{{$item->cid}}')" class="btn btn-success">Lead</a> --}}


                </td>
            </tr>
            <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
<div class="col-md-12 col-sm-12 col-xs-12 form-group ">
<label for="emirate">Select Emirate</label>

<select name="emirates" id="emirate" class="emirates form-control" required>
<option value="">Select Emirates</option>
@foreach($emirates as $emirate)
    <option value="{{ $emirate->name }}" @if (old('emirates') == $emirate->name) {{ 'selected' }} @endif>{{ $emirate->name }}</option>
@endforeach
</select>

</div>
    <div class="col-md-12 col-sm-12 col-xs-12 form-group ">
    <label for="emirate">User Location</label>
    <input type="text" class="form-control" id="lead_location" name="lead_location">
    <div id="result"></div>
</div>
</div>
        <div class="modal-footer">
            <a href="#" onclick="AssignLead('{{$item->id}}','{{route('ajaxRequest.AssignLead')}}','{{$item->cid}}')" class="btn btn-success">Assign Lead</a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
            @endforeach
        </tbody>

    </table>
</div>


@endif

@endsection
