@extends('layouts.num-app')

@section('content')
<div class="form-container">

    <div class="container-fluid float-left left">
        {{-- <button class="btn btn-success float-left left" onclick="NumberDtl('Available','{{route('ajaxRequest.NumberByType2')}}')">Show All Number</button> --}}
        <button class="btn btn-info float-left left" onClick="window.location.reload();">Reserved Number</button>
        <button class="btn btn-dark float-left left" onclick="NumberDtl('Active','{{route('ajaxRequest.NumberByType2')}}')">Active Number</button>
    {{-- </div> --}}
    {{-- <div class="container-fluid float-right right"> --}}
        {{-- <li><a href="{{route('logout')}}"><i class="icon-power"></i> <span>Logout</span></a> --}}
        <a class="btn btn-success float-right right" href="{{route('logout')}}">Logout ({{auth()->user()->name}})</a>
    </div>

</div>
<h3 class="text-center">
    Reserved Number List
</h3>
@if (auth()->user()->agent_code == 'ARF')
<div class="" id="broom">

</div>
@else
<div class="table-responsive" id="broom">

</div>
@endif
    <input type="hidden" id="lat">
    <input type="hidden" id="lng">
    <div id="map"></div>

</html>
@endsection
