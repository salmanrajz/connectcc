@extends('layouts.num-app')

@section('content')
 <div class="container-fluid float-left left">
      @foreach ($r as $item)
        {{-- <div class="col-lg-2"> --}}
            {{-- <div class="card" id="active_div"> --}}
                {{-- <div class="card-body text-center"> --}}
                    <a class="btn btn-success" href="{{route('guest-number-select',$item->type)}}">{{$item->type}}</a>
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </div> --}}
        @endforeach
        <a class="btn btn-success float-left left" href="{{route('admin.dashboard')}}">All Number</a>
        {{-- <a class="btn btn-info float-left left" href="{{route('guest-res')}}">Show Reserved Number</a> --}}
        {{-- <button class="btn btn-dark float-left left" onclick="NumberDtl('Active','{{route('ajaxRequest.NumberByType2')}}')">Active Number</button> --}}
    {{-- </div> --}}
    {{-- <div class="container-fluid float-right right"> --}}
        {{-- <li><a href="{{route('logout')}}"><i class="icon-power"></i> <span>Logout</span></a> --}}
        <a class="btn btn-success float-right right" href="{{route('logout')}}">Logout ({{auth()->user()->name}})</a>
    </div>
{{-- {{auth()->user()->agent_code}} --}}
@if(auth()->user()->agent_code == 'CC3')
<h1 class="text-center" style="font-size:20px;">
    POOL NUMBER
</h1>
@endif
@if (auth()->user()->agent_code == 'CC10')
<table class="">
@else
<div class="table-responsive" id="broom">
<table class="table table-striped table-bordered zero-configuration">

        @endif
        <thead>
            <tr>
                <th>Number</th>
                {{-- <th>Agent Name</th> --}}
                {{-- <th>Channel</th> --}}
                <th>Type</th>
                {{-- <th>Time</th> --}}
                {{-- <th>Status</th> --}}
                @if (auth()->user()->agent_code == 'CC10' || auth()->user()->agent_code == 'CC3' || auth()->user()->agent_code == 'AAMT')
                @else
                <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{$item->number}}</td>
                    <td>{{$item->type}}</td>
                        @if (auth()->user()->agent_code == 'CC10' || auth()->user()->agent_code == 'CC3')
                        @else
                    <td>
                        @php
                        $wp = \App\User::select('call_centers.guest_number')
                        ->Join(
                            'call_centers','call_centers.call_center_name','users.agent_code'
                        )
                        ->where('users.id', auth()->user()->id)->first();
                        $wp_num = $wp->guest_number;
                        // $wp->agent_code;
                        // if ($wp->agent_code == 'CC3') {
                        //     $wp_num = '97143032007';
                        // }
                        // else if ($wp->agent_code == 'CC2') {
                        //     $wp_num = '971565507766';
                        // }
                        // else if ($wp->agent_code == 'CC4') {
                        //     $wp_num = '97143032080';
                        // }
                        // else if ($wp->agent_code == 'CC8') {
                        //     // $wp_num = '97148795753';
                        //     $wp_num = '97143789320';
                        // } else if ($wp->agent_code == 'CC9') {
                        //     $wp_num = '97143032128';
                        // }
                        // else if ($wp->agent_code == 'CC5') {
                        //     // $wp_num = '97148795766';
                        //     $wp_num = '97143789330';
                        // }
                        // else if ($wp->agent_code == 'CC6') {
                        //     $wp_num = '97143032008';
                        // }
                        // elseif($wp->agent_code == 'CC1'){
                        //     $wp_num = '97148795748';
                        // }
                        // elseif($wp->agent_code == 'CC7'){
                        //     $wp_num = '97143032100';
                        // }
                        // elseif($wp->agent_code == 'CC11'){
                        //     $wp_num = '97143032080';
                        // }
                        // elseif($wp->agent_code == 'CC12'){
                        //     $wp_num = '917827250250';
                        // }
                        // elseif($wp->agent_code == 'AAMT'){
                        //     $wp_num = '971'
                        // }
                        @endphp
                        @if ($wp->agent_code == 'CC10' || $wp->agent_code == 'AAMT')
                        @else
                        <a href="https://api.whatsapp.com/send?phone={{$wp_num}}&text=Hi, I would like to avail this number ({{$item->number}})">Chat on Whatsapp</a>
                        @endif
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>

    </table>

@if(auth()->user()->agent_code == 'CC2' || auth()->user()->agent_code == 'CC9')
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '401209320774200');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=401209320774200&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
@endif

@endsection
